<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>投稿一覧</title>
</head>

<body>
<a href="/users">ユーザー一覧</a>
    <h1>投稿一覧</h1>
    <a href="{{ route('posts.create') }}">新規投稿</a>

    @foreach ($posts as $post)
    <div>
        <h2>{{ $post->title }}</h2>
        <p>投稿者: {{ $post->user->name }}</p>
        <p>{{ $post->content }}</p>
        <p>公開日: {{ $post->formatted_published_at }}</p>

        {{-- 編集ボタン --}}
        <a href="{{ route('posts.edit', $post->id) }}">編集</a>

        {{-- 削除ボタン --}}
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
        </form>
    </div>
    @endforeach
</body>

</html>