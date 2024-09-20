<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat pengguna dan mendapatkan token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
    }

    /** @test */
    public function it_can_create_a_post()
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'New Post Title',
            'content' => 'Content for the new post.',
            'user_id' => $this->user->id,  // Menambahkan user_id
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'title' => 'New Post Title',
            'content' => 'Content for the new post.',
            'user_id' => $this->user->id,  // Memastikan user_id sesuai
        ]);
    }

    /** @test */
    public function it_can_retrieve_posts()
    {
        // Membuat pos
        Post::create([
            'title' => 'Existing Post Title',
            'content' => 'Content for the existing post.',
            'user_id' => $this->user->id,  // Menambahkan user_id
        ]);

        $response = $this->getJson('/api/posts', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            ['title' => 'Existing Post Title'],
        ]);
    }
}
