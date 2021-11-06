<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetPGPKey
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
        #Get auth user
        $user = auth()->user();

        if (is_null($user->pgp_key)) {
            return redirect()->route('setpgpkey');
        }

        return $next($request);
    }
}
