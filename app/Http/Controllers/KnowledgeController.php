<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Knowledge;

class KnowledgeController extends Controller
{
    // GET /api/knowledge
    public function index(Request $request)
    {
        $query = Knowledge::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $words = explode(' ', $search);
            
            $query->where(function($q) use ($words) {
                foreach ($words as $word) {
                    if (trim($word) === '') continue;
                    $q->where(function($sq) use ($word) {
                        $sq->where('pregunta', 'like', "%{$word}%")
                          ->orWhere('respuesta', 'like', "%{$word}%")
                          ->orWhere('categoria', 'like', "%{$word}%");
                    });
                }
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('created_at', 'desc')->get()
        ]);
    }

    // POST /api/knowledge
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pregunta'  => 'required|string',
            'respuesta' => 'nullable|string',
            'categoria' => 'nullable|string|max:255',
            'status'    => 'nullable|string|in:pendiente,respondida,en_revision'
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = $request->filled('respuesta') ? 'respondida' : 'pendiente';
        }

        $knowledge = Knowledge::create($validated);

        return response()->json([
            'message'   => 'Conocimiento creado correctamente',
            'knowledge' => $knowledge
        ], 201);
    }

    // PUT /api/knowledge/{id}
    public function update(Request $request, $id)
    {
        $knowledge = Knowledge::findOrFail($id);

        $validated = $request->validate([
            'pregunta'  => 'sometimes|required|string',
            'respuesta' => 'sometimes|nullable|string',
            'categoria' => 'sometimes|nullable|string|max:255',
            'status'    => 'sometimes|required|string|in:pendiente,respondida,en_revision'
        ]);

        $knowledge->update($validated);

        return response()->json([
            'message'   => 'Conocimiento actualizado correctamente',
            'knowledge' => $knowledge->fresh()
        ]);
    }

    // DELETE /api/knowledge/{id}
    public function destroy($id)
    {
        $knowledge = Knowledge::findOrFail($id);
        $knowledge->delete();

        return response()->json([
            'message' => 'Conocimiento eliminado correctamente'
        ]);
    }
}
