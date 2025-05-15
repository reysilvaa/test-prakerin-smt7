<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        // Jika tidak ada role yang diberikan, lanjutkan request
        if (!$role) {
            return $next($request);
        }
        
        if ($request->user()->role !== $role) {
            return redirect()->route($request->user()->role === 'admin' ? 'departemens.index' : 'pegawai.dashboard');
        }

        return $next($request);
    }
}