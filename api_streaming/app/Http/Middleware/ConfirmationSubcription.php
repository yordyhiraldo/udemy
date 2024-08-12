<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmationSubcription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
         // SI EL USUARIO ESTA AUTENTICADO
         if(Auth::guard("api")->check()){
            $user = Auth::guard("api")->user();
            if(!$user->isActiveSubscription()){
                return response()->json(["error" => "EL USUARIO ESTA CON UNA SUSCRIPCIÓN INACTIVA","type" => 'INACTIVO'], 403);
            }
            return $next($request);
        }else{
            return response()->json(["error" => "EL USUARIO NO ESTA AUTENTICADO"],403);
        }
    }
}
