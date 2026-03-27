@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')

<div class="reservation-detail">
    <h1>予約詳細</h1>

    @foreach($reservationItem as $label => $value)
        <div class="detail-item">
            <div class="detail-label">{{ $label }}</div>
            <div class="detail-value">{{ $value }}</div>
        </div>
    @endforeach

    <form action="{{ route('reservation.delete',['reservation_id' => $reservation->id]) }}" method="post">
        @csrf
        @method("DELETE")
        <button onclick='return confirm("この予約を削除します。（削除したら戻せません）。よろしいですか？");'>削除する</button>
    </form>

    <div class="detail-update">
        <a href="{{ route('reservation.list',['date' => $reservation->reservation_date]) }}">一覧に戻る</a> /
        <a href="{{ route('reservation.edit',['reservation_id' => $reservation->id]) }}">編集する</a> 
    </div>
</div>
