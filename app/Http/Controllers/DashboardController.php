<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats() 
    {
        $visitas = DB::table('visitas')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $usuarios = DB::table('users')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        $noRegistrados = DB::table('visitas')
            ->whereNull('user_id')
            ->count();

        $totalVisitas = DB::table('visitas')->count();

        $porcentajeNoRegistrados = $totalVisitas > 0
            ? ($noRegistrados / $totalVisitas) * 100
            : 0;

        $errores = DB::table('errores')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return response()->json([
            'visitas' => $visitas,
            'usuarios' => $usuarios,
            'no_registrados' => round($porcentajeNoRegistrados, 2),
            'errores' => $errores
        ]);
    }
}
