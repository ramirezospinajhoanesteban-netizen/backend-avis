<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KnowledgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_access_knowledge_base(): void
    {
        // El endpoint /api/knowledge es público
        $response = $this->getJson('/api/knowledge');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }
}
