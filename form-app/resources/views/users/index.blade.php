<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ユーザー一覧</title>
</head>

<body>
<a href="/posts">投稿一覧</a>
    <h1>ユーザー一覧</h1>
    <table border="1">
        <tr>
            <th>名前</th>
            <th>投稿数</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->posts_count }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>