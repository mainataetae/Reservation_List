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
    public function index()
    {
        return view('admin.register');
    }

    public function register(AdminUserRegisterPostRequest $request)
    {
        $datum = $request->validated();

        try{
            //パスワードをハッシュ化
            $datum['password']=Hash::make($datum['password']);
            //インサート
            $r = AdminUserModel::create($datum);
        } catch (\Throwable $e){
            echo $e->getMessage();
            exit;
        }

        //管理者登録成功
        $request->session()->flash('admin.user_register_success',true);
        return redirect(route('admin.index'));
    }

    public function list()
    {
        $per_page = 20;
        $list = UserModel::orderBy('id')
                        ->paginate($per_page);

        return view('admin.user.list',['users' => $list]);
    }

    public function monthranking(Request $request)
    {
        $per_page = 20;
        $group_by_column = ['users.id','users.name'];
        //今月のデータ
        $targetMonth = $request->input('month', now()->month);
        $targetYear = $request->input('year',now()->year);
        //基準日の作成
        $currentDate = Carbon::create($targetYear,$targetMonth,1);

        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();
        
        $monthranking = UserModel::select($group_by_column)
                        ->selectRaw('count(reservations.id) AS reservation_num')
                        ->leftjoin('reservations', 'users.id', '=', 'reservations.store_id')
                        ->whereMonth('reservation_date', $targetMonth)
                        ->whereYear('reservation_date', $targetYear)
                        ->groupBy($group_by_column)
                        ->orderBy('reservation_num','DESC')
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

    public function yearranking(Request $request)
    {
        $per_page = 20;
        $group_by_column = ['users.id','users.name'];
        //今年のデータ
        $targetYear = $request->input('year',now()->year);
        // 前後年の計算
        $prevYear = $targetYear - 1;
        $nextYear = $targetYear + 1;
        
        $yearranking = UserModel::select($group_by_column)
                        ->selectRaw('count(reservations.id) AS reservation_num')
                        ->leftjoin('reservations', 'users.id', '=', 'reservations.store_id')
                        ->whereYear('reservation_date', $targetYear)
                        ->groupBy($group_by_column)
                        ->orderBy('reservation_num','DESC')
                        ->paginate($per_page);
        
        return view('admin.user.yearranking',[
            'users'=> $yearranking,
            'targetYear' => $targetYear,
            'prevYear' => $prevYear,
            'nextYear' => $nextYear
            ]);
    }
}
