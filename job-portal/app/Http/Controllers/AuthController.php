<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserDetail;
use App\Mail\PasswordResetMail;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('pages.public.register');
    }

    public function showLoginForm()
    {
        return view('pages.public.login');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Create user details
        UserDetail::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Auto login after registration
        Auth::login($user);

        // Regenerate session for security
        $request->session()->regenerate();

        // Store security information
        Session::put('ip_address', $request->ip());
        Session::put('user_agent', $request->userAgent());
        Session::put('login_time', now());

        return redirect()->route('home')->with('success', 'Registration successful!');
    }

    /**
     * Handle user login with secure session
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check remember me
        $remember = $request->boolean('remember');

        // Rate limiting - prevent brute force attacks
        if ($this->hasTooManyLoginAttempts($request)) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Coba lagi dalam beberapa menit.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $remember)) {
            // Clear login attempts
            $this->clearLoginAttempts($request);

            // Regenerate session ID for security
            $request->session()->regenerate();

            // Store security information
            Session::put('ip_address', $request->ip());
            Session::put('user_agent', $request->userAgent());
            Session::put('login_time', now());

            // Update session in database with user info
            $this->updateSessionWithUserInfo($request);

            // Log login activity
            $this->logLoginActivity($request);

            // Redirect based on user role
            $intendedUrl = $request->session()->get('url.intended', '/');

            $user = Auth::user();
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            } else {
                return redirect()->route('home')->with('success', 'Login successful!');
            }
        }

        // Increment login attempts
        $this->incrementLoginAttempts($request);

        // Check if email exists but password is wrong
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Email exists but password is wrong
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->withInput($request->only('email'));
        } else {
            // Email doesn't exist
            return back()->withErrors([
                'email' => 'Email does not exist in our records.',
            ])->withInput($request->only('email'));
        }
    }

    /**
     * Handle logout with session cleanup
     */
    public function logout(Request $request)
    {
        // Log logout activity
        $this->logLogoutActivity($request);

        // Remove session from database
        DB::table('sessions')->where('id', Session::getId())->delete();

        // Logout user
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate token
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Successfully logged out.');
    }

    /**
     * Show user profile page
     */
    public function profile()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;

        return view('pages.public.profile', compact('user', 'userDetail'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'cv_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'ktp_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Update user basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Handle file uploads
        $cvPath = null;
        $ktpPath = null;

        if ($request->hasFile('cv_path')) {
            $file = $request->file('cv_path');
            $filename = time() . '_cv_' . $file->getClientOriginalName();
            $cvPath = $file->storeAs('user_documents', $filename, 'public');
        }

        if ($request->hasFile('ktp_path')) {
            $file = $request->file('ktp_path');
            $filename = time() . '_ktp_' . $file->getClientOriginalName();
            $ktpPath = $file->storeAs('user_documents', $filename, 'public');
        }

        // Update or create user details
        $userDetail = $user->userDetail;
        if ($userDetail) {
            $userDetail->phone = $request->phone;
            $userDetail->address = $request->address;
            $userDetail->birth_date = $request->birth_date;
            $userDetail->gender = $request->gender;

            // Only update file paths if new files were uploaded
            if ($cvPath) {
                $userDetail->cv_path = $cvPath;
            }
            if ($ktpPath) {
                $userDetail->ktp_path = $ktpPath;
            }

            $userDetail->save();
        } else {
            UserDetail::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'cv_path' => $cvPath,
                'ktp_path' => $ktpPath,
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Force logout from all devices
     */
    public function logoutAllDevices(Request $request)
    {
        $user = Auth::user();

        // Delete all sessions for this user
        DB::table('sessions')->where('user_id', $user->id)->delete();

        // Logout from current session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out from all devices successfully!');
    }

    /**
     * Show active sessions
     */
    public function showActiveSessions()
    {
        $user = Auth::user();

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_human = date('Y-m-d H:i:s', $session->last_activity);
                $session->is_current = $session->id === Session::getId();
                return $session;
            });

        return view('auth.sessions', compact('sessions'));
    }

    /**
     * Update session with user information
     */
    private function updateSessionWithUserInfo(Request $request)
    {
        DB::table('sessions')
            ->where('id', Session::getId())
            ->update([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_activity' => time(),
            ]);
    }

    /**
     * Log login activity
     */
    private function logLoginActivity(Request $request)
    {
        // You can implement login logging here
        // For example, log to a separate table or log file
        Log::info('User login', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Log logout activity
     */
    private function logLogoutActivity(Request $request)
    {
        if (Auth::check()) {
            Log::info('User logout', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }
    }

    /**
     * Check if the user has too many login attempts
     */
    private function hasTooManyLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        $attempts = Session::get($key . '_attempts', 0);
        $lastAttempt = Session::get($key . '_last_attempt');

        if ($attempts >= 5 && $lastAttempt && now()->diffInMinutes($lastAttempt) < 15) {
            return true;
        }

        return false;
    }

    /**
     * Increment login attempts
     */
    private function incrementLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        $attempts = Session::get($key . '_attempts', 0) + 1;

        Session::put($key . '_attempts', $attempts);
        Session::put($key . '_last_attempt', now());
    }

    /**
     * Clear login attempts
     */
    private function clearLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        Session::forget($key . '_attempts');
        Session::forget($key . '_last_attempt');
    }

    /**
     * Get the throttle key for the request
     */
    private function throttleKey(Request $request)
    {
        return 'login_attempts_' . $request->ip() . '_' . strtolower($request->input('email'));
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('pages.public.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan dalam sistem'
        ]);

        try {
            // Generate reset token
            $token = Str::random(64);
            $email = $request->email;

            // Get user data
            $user = User::where('email', $email)->first();

            // Store reset token in database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'email' => $email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            // Check if we're in demo mode
            $demoMode = env('PASSWORD_RESET_DEMO_MODE', false);

            if ($demoMode) {
                // Demo mode: redirect directly to reset page
                Log::info('Password reset requested in demo mode', ['email' => $email]);
                return redirect()->route('password.reset', ['token' => $token, 'email' => $email])
                    ->with('success', '🎯 Demo Mode: Anda telah diarahkan langsung ke halaman reset password. Di production, link akan dikirim ke email.');
            } else {
                // Production mode: send email
                Mail::to($email)->send(new PasswordResetMail($token, $email, $user));
                return back()->with('success', '✉️ Link reset password telah dikirim ke email Anda. Silakan periksa inbox dan folder spam.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'email' => $email ?? null,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengirim email. Silakan coba lagi nanti.');
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->route('token');
        $email = $request->get('email');

        // Verify token exists and not expired (24 hours)
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$resetRecord || !Hash::check($token, $resetRecord->token)) {
            return redirect()->route('login')->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        return view('pages.public.reset-password', compact('token', 'email'));
    }

    /**
     * Handle reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan dalam sistem',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ]);

        // Verify token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
