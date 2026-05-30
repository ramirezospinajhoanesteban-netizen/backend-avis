<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    /**
     * Guardar una nueva sugerencia (ruta pública — sin autenticación).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'        => 'required|string|max:100',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string|min:20',
            'email'       => 'nullable|email|max:255',
        ]);

        $sugerencia = Suggestion::create($validated);

        return response()->json([
            'message'    => 'Sugerencia enviada correctamente.',
            'sugerencia' => $sugerencia,
        ], 201);
    }

    /**
     * Listar todas las sugerencias (solo admin).
     */
    public function index(Request $request)
    {
        $query = Suggestion::latest();

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== 'todas') {
            $query->where('estado', $request->estado);
        }

        // Filtro por tipo
        if ($request->has('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', $request->tipo);
        }

        // Búsqueda
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sugerencias = $query->paginate(15);

        $customResponse = array_merge($sugerencias->toArray(), [
            'total_nueva'    => Suggestion::where('estado', 'nueva')->count(),
            'total_revisada' => Suggestion::where('estado', 'revisada')->count(),
            'total_resuelta' => Suggestion::where('estado', 'resuelta')->count(),
        ]);

        return response()->json($customResponse);
    }

    /**
     * Actualizar estado de una sugerencia (solo admin).
     */
    public function update(Request $request, $id)
    {
        $sugerencia = Suggestion::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:nueva,revisada,resuelta',
        ]);

        $sugerencia->update($validated);

        return response()->json([
            'message'    => 'Estado actualizado.',
            'sugerencia' => $sugerencia,
        ]);
    }

    /**
     * Eliminar una sugerencia (solo admin).
     */
    public function destroy($id)
    {
        $sugerencia = Suggestion::findOrFail($id);
        $sugerencia->delete();

        return response()->json(['message' => 'Sugerencia eliminada.']);
    }
}
