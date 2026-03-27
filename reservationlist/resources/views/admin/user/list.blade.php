@extends('admin.layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="admin">
    <h1>院一覧</h1>
    <table border="1">
        <tr>
            <th>院ID</th>
            <th>院名</th>
        </tr>
@foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
        </tr>
@endforeach
    </table>
    <br>
        <!-- ページネーション -->
         {{-- {{$user->links() }} --}}
        現在{{ $users->currentPage() }}ページ目<br>
        @if($users->onFirstPage() === false)
        <a href="{{route('admin.user.list')}}">最初のページ</a>
        @else
        最初のページ
        @endif
        @if($users->previousPageUrl() !== null)
        <a href="{{ $user->previousPageUrl() }}">前に戻る</a>
        @else
        前に戻る
        @endif
        @if($users->nextPageUrl() !== null)
        <a href="{{ $user->nextPageUrl() }}">次に進む</a>
        @else
        次に進む
        @endif
</div>
@endsection