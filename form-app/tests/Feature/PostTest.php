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
    public function ログインユーザーが投稿できる()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'テスト投稿',
            'content' => 'テスト内容',
            'published_at' => now(),
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'テスト投稿',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function タイトルが空だと投稿できない()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => '',
            'content' => 'これはテスト投稿の内容です。',
            'user_id' => $user->id,
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function 内容が空だと投稿できない()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'テスト投稿',
            'content' => '',
            'user_id' => $user->id,
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

    /** @test */
    public function 投稿を編集できる()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create([
            'title' => '元のタイトル',
            'content' => '元の内容',
        ]);

        $response = $this->put("/posts/{$post->id}", [
            'title' => '新しいタイトル',
            'content' => '新しい内容',
            'published_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', ['title' => '新しいタイトル']);
    }

    /** @test */
    public function 投稿を削除できる()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete("/posts/{$post->id}");

        $response->assertRedirect('/posts');
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    /** @test */
    public function タイトルが大文字で保存される()
    {
        $post = new Post();
        $post->title = 'Laravelの勉強';
        $post->content = 'テスト投稿';
        $post->save();

        $this->assertEquals('LARAVELの勉強', $post->fresh()->title);
    }
}
