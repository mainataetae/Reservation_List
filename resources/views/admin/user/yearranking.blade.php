@extends('admin.layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="admin">
    <h1>{{ ($targetYear) }}年来院数</h1>
    <div class="ranking-year">
    <a href="{{ route('admin.user.yearranking', ['year' => $prevYear]) }}">←前年</a>
    <a href="{{ route('admin.user.yearranking', ['year' => $nextYear]) }}">翌年→</a>
    </div>
    <table class="admin-table">
        <tr>
            <th>順位</th>
            <th>院名</th>
            <th>予約数</th>
        </tr>
@foreach($users as $user)
        <tr>
            <td>{{ $loop->iteration }}位</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->reservation_num }}</td>
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