<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;


class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
       $role_arr=explode('|',$role);

        foreach($role_arr as $role){
            if($request->user()->role==$role){
                return $next($request);
            }
        }
        
        return response()->json(['message' =>'Access Denied! You are not authorized to visit this page.','status'=>'This Route is only available for '. implode(',',$role_arr)], 403);
        
    }
}