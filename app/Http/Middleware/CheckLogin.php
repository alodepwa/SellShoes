<?php

namespace App\Http\Middleware;

use Closure;
class CheckLogin
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
            if(!$request->ajax()){
                if(\Auth::check()){
                    return $next($request);
                }else{
                    return redirect()->route('formLogin');
                    // return response()->json('',404);/
                }
            }else{
                if(\Auth::check()){
                    return $next($request);
                }else{
                    // return redirect()->route('formLogin');
                    return response()->json('',404);
                }
            }
            
        
    }
}
