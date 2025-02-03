<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

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

    /** @test */
    public function 公開済みの投稿だけ取得できる()
    {
        // 公開済みの投稿
        $published = Post::factory()->create(['published_at' => now()]);
        // 未公開の投稿
        $draft = Post::factory()->create(['published_at' => null]);

        $posts = Post::published()->get();

        $this->assertTrue($posts->contains($published));
        $this->assertFalse($posts->contains($draft));
    }

    /** @test */
    public function 投稿を最新順に取得できる()
    {
        $older = Post::factory()->create(['published_at' => now()->subDays(5)]);
        $newer = Post::factory()->create(['published_at' => now()]);

        $posts = Post::recent()->get();

        $this->assertEquals($newer->id, $posts->first()->id);
    }

    /** @test */
    public function 投稿データとユーザー情報を一緒に取得できる()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $retrievedPost = Post::with('user')->first();

        $this->assertEquals($user->id, $retrievedPost->user->id);
    }
}