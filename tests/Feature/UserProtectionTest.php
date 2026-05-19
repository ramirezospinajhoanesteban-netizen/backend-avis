<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserProtectionTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_users_list(): void
    {
        // El endpoint /api/users requiere autenticación y rol de admin
        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }
}
