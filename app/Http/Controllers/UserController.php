<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page    = $request->input('page', 1);

        // Clave de caché única por página y por tamaño de página
        $cacheKey = "users_list_page_{$page}_per_{$perPage}";

        $users = Cache::remember($cacheKey, 120, function () use ($perPage) {
            return User::select('id', 'name', 'email', 'role', 'is_active')
                       ->paginate($perPage);
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'role'      => 'required|in:admin,instructor,aprendiz',
            'password'  => 'required|string|min:8',
            'is_active' => 'boolean'
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $user = User::create($validated);

        // Invalida el caché de usuarios al crear uno nuevo
        $this->clearUsersCache();

        return response()->json([
            'message' => 'User created successfully',
            'user'    => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role,
                'is_active' => $user->is_active
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'email'     => 'sometimes|email|unique:users,email,' . $id,
            'role'      => 'sometimes|in:admin,instructor,aprendiz',
            'is_active' => 'sometimes|boolean',
            'password'  => 'sometimes|nullable|string|min:8',
        ]);

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Invalida el caché de usuarios al modificar
        $this->clearUsersCache();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot delete yourself'], 400);
        }

        $user->delete();

        // Invalida el caché de usuarios al eliminar
        $this->clearUsersCache();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Elimina todas las entradas de caché de listado de usuarios.
     * Se llama tras cada mutación (create/update/delete).
     */
    private function clearUsersCache(): void
    {
        // Limpia páginas comunes; suficiente para la mayoría de casos
        for ($page = 1; $page <= 20; $page++) {
            Cache::forget("users_list_page_{$page}_per_10");
            Cache::forget("users_list_page_{$page}_per_25");
            Cache::forget("users_list_page_{$page}_per_50");
        }
    }
}
