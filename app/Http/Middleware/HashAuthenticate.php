<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class HashAuthenticate
{

    /**
     * Авторизация по hash
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::check()) {
            $user = User::where('hash', $request->header('hash'))->first();
            if ($user) {
                \Auth::login($user);
            }
        }
        return $next($request);
    }
}
