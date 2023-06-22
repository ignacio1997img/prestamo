<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Loggin
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
        try {
            if(setting('configuracion.development') && !auth()->user()->hasRole('admin')){
                return redirect('development');
            }

            if(Auth::user()->status == 0){
                Auth::logout();
                // return redirect('development');
            }
        } catch (\Throwable $th) {}

        // Evitar que registre cuando el usuario ingrese a la lista de logs
        if (!str_contains(request()->url(), 'admin/compass')) {
            try {
                if(!Auth::user()->hasRole('admin'))
                {
                    $data = [
                        'user_id' => Auth::user()->id,
                        'role' => Auth::user()->role->name,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'ip' => request()->ip(),
                        // 'user_agent' => request()->userAgent(),
                        'url' => request()->url(),
                        'method' => request()->method(),
                        'input' => request()->except(['password', '_token', '_method']),
                    ];
                    Log::channel('requests')->info('Petición HTTP al sistema.', $data);
                }
            } catch (\Throwable $th) {
                $data = [
                    'ip' => request()->ip(),
                    // 'user_agent' => request()->userAgent(),
                    'url' => request()->url(),
                    'method' => request()->method(),
                    'input' => request()->except(['password', '_token', '_method']),
                ];
                Log::channel('requests')->info('Petición HTTP al sistema.', $data);
            }
        }

        return $next($request);
    }
}
