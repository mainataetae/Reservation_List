@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')

<div class="reservation-detail">
    <h1>予約編集</h1>
        @if($errors->any())
        <div>
        @foreach($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
        </div>
    @endif
    <form action="{{ route('reservation.edit_save',['reservation_id' => $reservation->id]) }}" method="post">
        @csrf
        @method("PUT")
        予約日 : <input type="date" name="reservation_date" id="reservation_date" value="{{ old('reservation_date') ?? $reservation->reservation_date }}" required><br>
        予約時間 : 
        <select name="reservation_time" required>
            @foreach($hours as $hour)
                <option value="{{ $hour }}" {{ (old('reservation_time') ?? $reservation->reservation_time) == $hour ? 'selected' : ''}}>
                   {{ $hour }}
                </option>
            @endforeach
        </select><br>
        担当スタッフ : 
        <select name="staff_name" required>
            @foreach($names as $name)
                <option value="{{ $name }}" {{ (old('staff_name') ?? $reservation->staff_name) == $name ? 'selected' : '' }} >
                    {{ $name }}
                </option>
            @endforeach
        </select><br>
        患者様名 : <input type="text" name="customer_name" value="{{ old('customer_name') ?? $reservation->customer_name }}" readonly><br>
        状態 : 
        <select name="status" class="status-select">
        <option value="0" {{ (old('status') ?? $reservation->status) == 0 ? 'selected' : '' }}>予約</option>
        <option value="1" {{ (old('status') ?? $reservation->status) == 1 ? 'selected' : '' }}>来院中</option>
        <option value="2" {{ (old('status') ?? $reservation->status) == 2 ? 'selected' : '' }}>カウンセリング中</option>
        <option value="3" {{ (old('status') ?? $reservation->status) == 3 ? 'selected' : '' }}>帰宅</option>
        </select><br>
        備考欄 : <textarea name="memo">{{ old('memo',$reservation->memo) }}</textarea><br>
        <button onclick='return confirm("この予約を更新します。よろしいですか？");'>予約を更新する</button>
    </form>
    <br>
    <div class="detail-update">
        <a href="{{ route('reservation.list') }}">一覧へ戻る</a> /
        <a href="{{ route('reservation.detail',['reservation_id' => $reservation->id]) }}">詳細画面へ</a>
    </div>
</div>
@endsection