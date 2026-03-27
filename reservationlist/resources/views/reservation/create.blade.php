@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')

<div class="reservation-detail">
    <h1>予約登録</h1>
        @if($errors->any())
        <div>
        @foreach($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
        </div>
    @endif
    <form action="{{ route('reservation.register') }}" method="post">
        @csrf
        予約日 : <input type="date" name="reservation_date" id="reservation_date" value="{{ $selectedDate }}" required><br>
        予約時間 : <input type="text" name="reservation_time" value="{{ $selectedTime }}" readonly><br>
        担当スタッフ : <input type="text" name="staff_name" value="{{ $selectedStaff }}" readonly><br>
        お客様名 : <input type="text" name="customer_name" value="{{ old('customer_name') }}" required><br>
        備考欄 : <textarea name="memo" value="{{ old('memo') }}"></textarea><br>
        @if(!session('error_message'))
        <button type="submit">予約を確定する</button>
        @endif
        @if(session('error_message'))
       {{ session('error_message') }}<br>
        <button type="submit" name="action" value="force">登録する</button>
    @endif
    </form>
    <br>
    <div class="detail-update">
        <a href="{{ route('reservation.list') }}">一覧へ戻る</a> 
    </div>
</div>
@endsection