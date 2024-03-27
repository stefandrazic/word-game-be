<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WordControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testValidWord(): void
    {
        $response = $this->postJson('/api/word', ['word' => 'hello']);
        $response->assertStatus(200)->assertJson(['score' => 4]);
    }

    public function testInvalidWord(): void
    {
        $response = $this->postJson('/api/word', ['word' => 'xyzz']);

        $response->assertStatus(400)->assertJson(['error' => 'Invalid word']);
    }

    public function testPalindromeWord(): void
    {
        $response = $this->postJson('/api/word', ['word' => 'radar']);

        $response->assertStatus(200)->assertJson(['score' => 6]);
    }

    public function testAlmostPalindromeWord(): void
    {
        $response = $this->postJson('/api/word', ['word' => 'engage']);

        $response->assertStatus(200)->assertJson(['score' => 6]);
    }
}
