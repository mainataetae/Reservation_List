@extends('admin.layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="login">
    <h1>管理者登録</h1>
    @if($errors->any())
        <div>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    @if(session('error_message'))
        <div>
            {{session('error_message')}}
        </div>
    @endif

    <form action="/admin/register" method="post">
        @csrf
        ログインID : <input type="text" name="login_id"><br>
        パスワード : <input type="password" name="password"><br>
        パスワード（再度） : <input type="password" name="password_confirmation"><br>
        <button>登録する</button>
    </form>
</div>
@endsection