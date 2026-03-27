@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="login">
    <h1>新規院登録</h1>
    @if($errors->any())
        <div>
        @foreach($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
        </div>
    @endif
    <form action="/user/register" method="post">
        @csrf
        院名 : <input type="name" name="name" value="{{ old('name') }}"><br>
        email : <input type="email" name="email" value="{{ old('email') }}"><br>
        パスワード : <input type="password" name="password"><br>
        パスワード（再度） : <input type="password" name="password_confirmation"><br>
        <button>登録する</button>
    </form>
</div>
@endsection