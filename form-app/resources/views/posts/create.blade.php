<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規投稿</title>
</head>
<body>
    <h1>新規投稿</h1>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <label>タイトル:</label>
        <input type="text" name="title" value="{{ old('title') }}">
        <br>
        <label>内容:</label>
        <textarea name="content">{{ old('content') }}</textarea>
        <br>
        <button type="submit">投稿</button>
    </form>

    <a href="{{ route('posts.index') }}">戻る</a>
</body>
</html>