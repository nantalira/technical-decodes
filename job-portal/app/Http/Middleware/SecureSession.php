<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SecureSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Session Security Checks
        $this->validateSessionSecurity($request);

        // 2. Update session activity
        $this->updateSessionActivity($request);

        // 3. Clean up expired sessions
        $this->cleanupExpiredSessions();

        return $next($request);
    }

    /**
     * Validate session security measures
     */
    private function validateSessionSecurity(Request $request)
    {
        // Check IP consistency (optional - can be disabled for mobile users)
        if (config('session.validate_ip', false)) {
            $sessionIp = Session::get('ip_address');
            $currentIp = $request->ip();

            if ($sessionIp && $sessionIp !== $currentIp) {
                Session::flush();
                Auth::logout();
                abort(403, 'IP address mismatch detected');
            }
        }

        // Check User-Agent consistency
        $sessionUserAgent = Session::get('user_agent');
        $currentUserAgent = $request->userAgent();

        if ($sessionUserAgent && $sessionUserAgent !== $currentUserAgent) {
            Session::flush();
            Auth::logout();
            abort(403, 'User-Agent mismatch detected');
        }

        // Store security info on first visit
        if (!Session::has('ip_address')) {
            Session::put('ip_address', $request->ip());
            Session::put('user_agent', $request->userAgent());
            Session::put('login_time', now());
        }
    }

    /**
     * Update session activity in database
     */
    private function updateSessionActivity(Request $request)
    {
        if (Auth::check()) {
            DB::table('sessions')
                ->where('id', Session::getId())
                ->update([
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_activity' => time(),
                ]);
        }
    }

    /**
     * Clean up expired sessions (runs occasionally)
     */
    private function cleanupExpiredSessions()
    {
        // Run cleanup randomly (2% chance)
        if (random_int(1, 100) <= 2) {
            $expiredTime = time() - (config('session.lifetime') * 60);

            DB::table('sessions')
                ->where('last_activity', '<', $expiredTime)
                ->delete();
        }
    }
}
