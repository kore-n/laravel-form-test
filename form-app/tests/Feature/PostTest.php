<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 投稿が作成できる()
    {
        $response = $this->post('/posts', [
            'title' => 'テスト投稿',
            'content' => 'これはテスト投稿の内容です。',
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', ['title' => 'テスト投稿']);
    }

    /** @test */
    public function タイトルが空だと投稿できない()
    {
        $response = $this->post('/posts', [
            'title' => '',
            'content' => 'これはテスト投稿の内容です。',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function 内容が空だと投稿できない()
    {
        $response = $this->post('/posts', [
            'title' => 'テスト投稿',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['content']);
    }
}