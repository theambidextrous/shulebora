<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    protected function createCode($length = 20) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // return redirect(RouteServiceProvider::HOME);
            // return redirect(route('school'));
            if( Auth::user()->is_admin ){
                return redirect(route('school'));
            }
            if( Auth::user()->is_cop ){
                return redirect(route('corporate'));
            }
            if( Auth::user()->is_teacher ){
                return redirect(route('teacher'));
            }
            if( Auth::user()->is_learner ){
                if(!Auth::user()->is_paid){
                    Session::put('order', $this->createCode(6));
                }
                return redirect(route('learner'));
            }
        }

        return $next($request);
    }
}
