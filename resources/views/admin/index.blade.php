@extends('admin.layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="login">
    <h1>管理画面 ログイン</h1>
    @if(session('admin.user_register_success') == true)
    管理者を登録しました！
    @endif
    @if($errors->any())
        <div>
        @foreach($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
        </div>
    @endif
    <form action="/admin/login" method="post">
        @csrf
        ログインID : <input type="text" name="login_id" value="{{ old('login_id','hogemin') }}"><br>
        パスワード : <input type="password" name="password"><br>
        (※デモ用パスワード：pass)<br>
        <button>登録する</button>
    </form>
    <br>
    <a href='/admin/register'>管理者登録</a>
    <br><br>
    <a href='/reservation/list'>各院予約サイト</a>
</div>
@endsection