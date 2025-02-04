<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿編集</title>
</head>
<body>
    <h1>投稿編集</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>タイトル:</label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}">
        <br>

        <label>内容:</label>
        <textarea name="content">{{ old('content', $post->content) }}</textarea>
        <br>

        <label>公開日時:</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '' ) }}">
        <br>

        <button type="submit">更新</button>
    </form>

    <a href="{{ route('posts.index') }}">戻る</a>
</body>
</html>