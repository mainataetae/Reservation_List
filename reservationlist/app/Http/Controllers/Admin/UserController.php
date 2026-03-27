<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRegisterPostRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser as AdminUserModel;
use App\Models\User as UserModel;

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
        
        return view('admin.user.list',['users'=> $list]);
    }
}
