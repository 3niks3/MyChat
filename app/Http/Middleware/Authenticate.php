<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next, ...$guards)
    {
       $allowed = false;
       $default_guard = config('auth.defaults.guard');
       $guards = empty($guards)?[$default_guard]:$guards;

        foreach($guards as $guard)
        {
            if(auth()->guard($guard)->check()) {
                $allowed = true;
                break;
            }
        }

        if(!$allowed)
        {
            throw new AuthenticationException(
                'Unauthenticated.', $guards, $this->redirectTo($request)
            );
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        abort(403, 'Unauthorized action.');
    }
}
