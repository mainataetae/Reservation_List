<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation as ReservationModel;
use App\Http\Requests\ReservationRegisterPostRequest;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    //予約管理表一覧表示
    public function list(Request $request)
    {
        //基本データ
        $staffs = ['カウンセラーA', 'カウンセラーB', 'カウンセラーC', 'カウンセラーD'];
        $elses = ['院長(Dr)','架電依頼','ナースコース消化'];
        $hours = ['10:00','10:30','11:00','11:30', '12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30', '16:00','16:30', '17:00','17:30'];
        //スタッフとその他を合致させた配列
        $names = array_merge($staffs,$elses);
        //今日の日付
        $targetDate = $request->query('date', date('Y-m-d'));
        //画面が変わっても同じ日付を表示させる
        session(['viewing_date' => $targetDate]);
        
        //今日の予約データを全て取得
        $allReservations = ReservationModel::where('store_id', Auth::id())
                                            ->where('reservation_date', $targetDate)
                                            ->get();
        //予約データを並び替えて保存
        $reservations = [];
        foreach($allReservations as $reservation){
            $reservations[$reservation->staff_name][$reservation->reservation_time] = $reservation;
        }

        // 前後日の計算
        $prevDate = date('Y-m-d', strtotime($targetDate . ' -1 day'));
        $nextDate = date('Y-m-d', strtotime($targetDate . ' +1 day'));

        //bladeで使えるように置き換え
        return view('reservation.list',[
            'staffs' => $staffs,
            'elses' => $elses,
            'hours' => $hours,
            'names' => $names,
            'reservations' => $reservations,
            'targetDate' => $targetDate,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate
            ]);
    }

    //新規作成画面
    public function create(Request $request)
    {
        //既に選択されているデータを取得
        return view('reservation.create', [
            'selectedDate' => $request->query('date', session('viewing_date', date('Y-m-d'))),
            'selectedTime'=> $request->query('time'),
            'selectedStaff' => $request->query('staff')
            ]);
    }
    
    //新規登録処理
    public function register(ReservationRegisterPostRequest $request)
    {
        $datum = $request->validated();
        //登録情報にログイン中の店舗IDを追加する
        $datum['store_id'] = Auth::id();

        //強制登録ボタン
        if ($request->input('action') === 'force') {
            try {
                ReservationModel::create($datum);
                return redirect()->route('reservation.list',['date' => $datum['reservation_date']]);
            } catch (\Throwable $e) {
                \Log::error('強制登録に失敗しました:' . $e->getMessage());
                return back()->withInput()->with('error_message', '保存エラーが発生しました。');
            }
        }

        //名前の重複チェック
        $exists = ReservationModel::where('store_id', Auth::id())
                                    ->where('customer_name', $datum['customer_name'])
                                    ->exists();

        // 重複があれば、情報を保持しながらメッセージと一緒に「入力画面」に戻す
        if ($exists) {
            return back()
                ->withInput()
                ->with('error_message', '※同じ名前の予約があります。同じ名前で登録しますか？');
        }

       //重複がなかった時の、通常の処理
        try {
            ReservationModel::create($datum);
            return redirect()->route('reservation.list',['date' => $datum['reservation_date']]);
        } catch (\Throwable $e) {
            \Log::error('通常登録に失敗しました:' . $e->getMessage());
            return back()->withInput()->with('error_message', '保存エラーが発生しました。');
        }
    }

    //詳細画面
    public function detail($reservation_id)
    {
         $reservation = $this->getReservationModel($reservation_id);

        $reservationItem = [
            '予約日' => $reservation->reservation_date,
            '時間' => $reservation->reservation_time,
            '担当スタッフ' => $reservation->staff_name,
            '患者様名' => $reservation->customer_name . ' 様',
            '備考・メモ' => $reservation->memo ?? 'なし',
        ];
        
        return view('reservation.detail',[
            'reservation' => $reservation,
            'reservationItem' => $reservationItem
        ]);
    }

    //削除処理
    public function delete($reservation_id)
    {
        $reservation = $this->getReservationModel($reservation_id);
        if($reservation !== null){
            $reservation->delete();
        }

        return redirect()->route('reservation.list');
    }

    //編集画面
    public function edit($reservation_id, Request $request)
    {
        $reservation = $this->getReservationModel($reservation_id);
        $names = ['カウンセラーA', 'カウンセラーB', 'カウンセラーC', 'カウンセラーD','院長(Dr)','架電依頼','ナースコース消化'];
        $hours = ['10:00','10:30','11:00','11:30', '12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30', '16:00','16:30', '17:00','17:30'];

        return view('reservation.edit', [
            'reservation' => $reservation,
            'selectedDate' => $request->query('date', session('viewing_date', date('Y-m-d'))),
            'names' => $names,
            'hours' => $hours
            ]);
    }

    //更新処理
    public function editSave(ReservationRegisterPostRequest $request,$reservation_id)
    {
        $datum = $request->validated();
        $reservation = $this->getReservationModel($reservation_id);

        $reservation->customer_name = $datum['customer_name'];
        $reservation->staff_name = $datum['staff_name'];
        $reservation->reservation_date = $datum['reservation_date'];
        $reservation->reservation_time = $datum['reservation_time'];
        $reservation->memo = $datum['memo'];
        $reservation->status = $request->status;

        $reservation->save();

        return redirect()->route('reservation.list',['date' => $datum['reservation_date']]);
    }
    
    //単一モデルの取得
    protected function getReservationModel($reservation_id)
    {
        $reservation = ReservationModel::find($reservation_id);
        if($reservation === null){
            return redirect()->route('reservation.list');
        }

        if($reservation->store_id !== Auth::id()){
            return redirect()->route('reservation.list');
        }

        return $reservation;
    }
}