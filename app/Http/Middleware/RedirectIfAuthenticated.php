<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect berdasarkan role
                if ($user->role === 'dosen') {
                    return redirect()->route('dosen.dashboard');
                } elseif ($user->role === 'mahasiswa') {
                    return redirect()->route('mahasiswa.dashboard');
                }
                
                // Fallback ke login jika role tidak dikenal
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
