@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')
<div class="reserve-list">
    <h1>予約管理表</h1>
    <div class="reserve-date">
        <a href="{{ route('reservation.list', ['date' => $prevDate]) }}">←前日</a>
        <div class="today-date"> < {{ date('Y年m月d日', strtotime($targetDate)) }} ></div>
        <a href="{{ route('reservation.list', ['date' => $nextDate]) }}">翌日→</a>
    </div>
</div>
    <table class="reserve-table">
    <thead>
        <tr class="list-head">
            <th class="list-time">時間</th>
            @foreach($names as $name)
                <th class="{{ in_array($name, $elses) ? 'list-else' : 'list-staff' }}">
                    {{ $name }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($hours as $hour)
        <tr>
            <td class="time-cell">{{ $hour }}</td>

            @foreach($names as $name)

            @php
                //予約があるかチェック
                $res = $reservations[$name][$hour] ?? null;
                //予約の有無でリンク先を決める
                if($res){
                    $url = route('reservation.detail', ['reservation_id' => $res->id]);
                } else{
                    $url =  route('reservation.create', ['staff' => $name, 'time' => $hour]);
                }
            @endphp

            <!-- 予約状態によって色を変えるため、クラス名にステータス番号を結合 -->
            <td class="reserve-cell {{ $res ? 'is-reserved status-' . $res->status : '' }}">
                <a href="{{ $url }}" class="cell-link">
                    {{ $res ? $res->customer_name : ' ' }} <!-- 予約があれば名前を表示 -->
                </a>
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    </table>

    <br>
    <a href="/logout">ログアウト</a>
@endsection