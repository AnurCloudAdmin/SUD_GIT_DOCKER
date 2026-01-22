<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PreventConcurrentLogins
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->session_id && $user->session_id !== Session::getId()) {
                Auth::logout();

                return redirect('/login')->withErrors(['message' => 'You have been logged out because your account was logged in from another location.']);
            }
        }

        return $next($request);
    }
}
