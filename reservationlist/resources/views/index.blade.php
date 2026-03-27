@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="login">
    <h1>ログイン</h1>
    @if($errors->any())
        <div>
        @foreach($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
        </div>
    @endif
    @if(session('front.user_register_success') == true)
        {{ session('register_name') }}を登録しました！！
    @endif
    <form action="/login" method="post">
        @csrf
        email : <input type="email" name="email" value="{{ old('emaik') }}"><br>
        パスワード : <input type="password" name="password"><br>
        <button>ログインする</button>
    </form>
    <br>
    <a href="/user/register">新規院登録</a>
</div>
@endsection