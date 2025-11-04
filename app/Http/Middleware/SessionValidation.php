<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SessionValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Validate session data
            if (!session()->has('user_id') || session('user_id') !== $user->id) {
                Log::warning('Invalid session detected', [
                    'session_user_id' => session('user_id'),
                    'auth_user_id' => $user->id,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                
                // Fix session data
                session(['user_id' => $user->id, 'user_role' => $user->role]);
            }
            
            // Check role consistency
            if (session('user_role') !== $user->role) {
                Log::warning('Role mismatch detected', [
                    'session_role' => session('user_role'),
                    'user_role' => $user->role,
                    'user_id' => $user->id
                ]);
                
                session(['user_role' => $user->role]);
            }
        }
        
        return $next($request);
    }
}