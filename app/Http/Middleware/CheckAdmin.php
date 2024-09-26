<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(\Auth::check()){
            $user = \Auth::user();
            if($user->role_id==2){
                return $next($request);
            }
            return redirect()->route('page.index');
        }else{
            return redirect()->route('formLogin');
        }
    }
}
