<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. API Key Authentication
        $apiKey = $request->header('X-API-Key') ?? $request->get('api_key');
        $validApiKeys = explode(',', env('API_KEYS', 'default-key-123,demo-key-456'));

        if (!$apiKey || !in_array($apiKey, $validApiKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key',
                'error_code' => 'INVALID_API_KEY'
            ], 401);
        }

        // 2. Rate Limiting (100 requests per minute per IP)
        $clientIp = $request->ip();
        $rateLimitKey = "api_rate_limit_{$clientIp}";
        $currentRequests = Cache::get($rateLimitKey, 0);

        if ($currentRequests >= 100) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit exceeded. Maximum 100 requests per minute.',
                'error_code' => 'RATE_LIMIT_EXCEEDED'
            ], 429);
        }

        // Increment request counter
        Cache::put($rateLimitKey, $currentRequests + 1, now()->addMinute());

        // 3. Security Headers
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. CORS Headers for API
        if ($request->getMethod() === 'OPTIONS') {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-API-Key');
        }

        // 5. Log API Usage
        Log::channel('api')->info('API Request', [
            'ip' => $clientIp,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'api_key' => substr($apiKey, 0, 8) . '...' // Log partial key for security
        ]);

        return $response;
    }
}
