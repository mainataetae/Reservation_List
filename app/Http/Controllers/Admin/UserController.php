<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRegisterPostRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser as AdminUserModel;
use App\Models\User as UserModel;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    //管理者登録画面
    public function index()
    {
        return view('admin.register');
    }

    //管理者登録処理
    public function register(AdminUserRegisterPostRequest $request)
    {
        $datum = $request->validated();

        try{
            //パスワードをハッシュ化
            $datum['password']=Hash::make($datum['password']);
            //インサート
            $r = AdminUserModel::create($datum);
        } catch (\Throwable $e){
            \Log::error('管理者登録に失敗しました:' . $e->getMessage());
            return back()->withInput()->with('error_message', '保存エラーが発生しました。');
        }

        //管理者登録成功
        $request->session()->flash('admin.user_register_success',true);
        return redirect(route('admin.index'));
    }

    //ユーザー一覧取得
    public function list()
    {
        $per_page = 5;
        $list = UserModel::orderBy('id')
                        ->paginate($per_page);

        return view('admin.user.list',['users' => $list]);
    }

    //月間ランキング表示
    public function monthranking(Request $request)
    {
        $per_page = 5;
        $group_by_column = ['users.id','users.name'];
        //今月のデータ
        $targetMonth = $request->input('month', now()->month);
        $targetYear = $request->input('year',now()->year);
        //基準日の作成
        $currentDate = Carbon::create($targetYear,$targetMonth,1);

        //Carbonの破壊的変更という性質をさせないため、コピーして操作する
        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();
        
        $monthranking = UserModel::withCount([
                        'reservations as reservation_num' => function($query) use ($targetMonth , $targetYear){
                            $query->whereMonth('reservation_date', $targetMonth)
                                   ->whereYear('reservation_date', $targetYear);
                        }])
                        ->orderBy('reservation_num', 'DESC')
                        ->orderBy('users.name','ASC')
                        ->paginate($per_page);        
        
        return view('admin.user.monthranking',[
            'users'=> $monthranking,
            'targetMonth' => $targetMonth,
            'targetYear' => $targetYear,
            'prevYear' => $prevDate->year,
            'prevMonth' => $prevDate->month,
            'nextYear' => $nextDate->year,
            'nextMonth' => $nextDate->month
            ]);
    }

    //年間ランキング表示
    public function yearranking(Request $request)
    {
        $per_page = 5;
        $group_by_column = ['users.id','users.name'];
        //今年のデータ
        $targetYear = $request->input('year',now()->year);
        // 前後年の計算
        $prevYear = $targetYear - 1;
        $nextYear = $targetYear + 1;
        
        $yearranking = UserModel::withCount(['reservations as reservation_num' => function ($query) use ($targetYear){
                            $query->whereYear('reservation_date',$targetYear);
                      }])
                        ->orderBy('reservation_num', 'DESC')
                        ->orderBy('users.name','ASC')
                        ->paginate($per_page);
        
        return view('admin.user.yearranking',[
            'users'=> $yearranking,
            'targetYear' => $targetYear,
            'prevYear' => $prevYear,
            'nextYear' => $nextYear
            ]);
    }
}
