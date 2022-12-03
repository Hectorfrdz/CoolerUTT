<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @param  string[]  $rol
     */
    public function handle(Request $request, Closure $next,$role)
    {
        $role = array_slice(func_get_args(), 2);
        foreach($role as $rol)
        {
            if($request->user()->rol_id == (integer)$rol)
            {
                return $next($request);
            }
        }
            return response()->json([
                "Nivel de usuario no permitido"
            ],400);
    }
}
