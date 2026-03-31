<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}"></link>
        <title>ログイン機能付き予約管理サービス 管理画面 @yield('title')</title>
    </head>

<body>
    @auth('admin')
    <div class="admin-top">
    <a href ="/admin/top">管理画面Top</a><br>
    <a href ="/admin/user/list">院一覧</a><br>
    <a href ="/admin/user/monthranking">月間来院数ランキング</a><br>
    <a href ="/admin/user/yearranking">年間来院数ランキング</a><br>
    <a href="/admin/logout">ログアウト</a> <br>
    </div>
    @endauth
    @yield('contents')
</body>

</html>