<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        // Caché de 5 minutos: el dashboard no necesita datos en tiempo real
        return Cache::remember('dashboard_stats', 300, function () {

            // ── 1. Visitas por día ─────────────────────────────────────────────
            $visitas = DB::table('visitas')
                ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(*) as total'))
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            // ── 2. Registros de usuarios por día ──────────────────────────────
            $usuarios = DB::table('users')
                ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(*) as total'))
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            // ── 3. Porcentaje de no registrados (1 sola consulta con CASE) ────
            $visitasAgregadas = DB::table('visitas')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('COUNT(*) FILTER (WHERE user_id IS NULL) as sin_cuenta')
                )
                ->first();

            $porcentajeNoRegistrados = $visitasAgregadas->total > 0
                ? ($visitasAgregadas->sin_cuenta / $visitasAgregadas->total) * 100
                : 0;

            // ── 4. Errores por día ─────────────────────────────────────────────
            $errores = DB::table('errores')
                ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(*) as total'))
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            // ── 5. Estadísticas de knowledge (1 sola consulta con COUNT+CASE) ─
            $knowledge = DB::table('knowledge')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw("COUNT(*) FILTER (WHERE status = 'pendiente') as pendiente"),
                    DB::raw("COUNT(*) FILTER (WHERE status = 'respondida') as respondida"),
                    DB::raw("COUNT(*) FILTER (WHERE status = 'en_revision') as en_revision")
                )
                ->first();

            return [
                'visitas'        => $visitas,
                'usuarios'       => $usuarios,
                'no_registrados' => round($porcentajeNoRegistrados, 2),
                'errores'        => $errores,
                'knowledge'      => [
                    'total'       => (int) $knowledge->total,
                    'pendiente'   => (int) $knowledge->pendiente,
                    'respondida'  => (int) $knowledge->respondida,
                    'en_revision' => (int) $knowledge->en_revision,
                ],
            ];
        });
    }
}
