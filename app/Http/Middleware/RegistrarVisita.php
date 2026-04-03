<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrarVisita
{
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->is('api/*') ||
            $request->is('login') ||
            $request->is('register') ||
            str_contains($request->path(), '.')
        ) {
            return $next($request);
        }

        try {
            DB::table('visitas')->insert([
                'user_id' => auth()->check() ? auth()->id() : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'url' => $request->fullUrl(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silencioso para no romper la app
        }

        return $next($request);
    }
}
