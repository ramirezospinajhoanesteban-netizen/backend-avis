<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Knowledge;

class KnowledgeController extends Controller
{
    // GET /api/knowledge
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Knowledge::orderBy('created_at', 'desc')->get()
        ]);
    }

    // POST /api/knowledge
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pregunta'  => 'required|string',
            'respuesta' => 'required|string',
            'categoria' => 'nullable|string|max:255',
        ]);

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
            'respuesta' => 'sometimes|required|string',
            'categoria' => 'sometimes|nullable|string|max:255',
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
