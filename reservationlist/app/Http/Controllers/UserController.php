<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterPostRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User as UserModel;

class UserController extends Controller
{
    public function index()
    {
        return view('user.register');
    }

    public function register(UserRegisterPostRequest $request)
    {
        $datum = $request->validated();

        //テーブルへインサート
        try{
            //パスワードをハッシュ化
            $datum['password'] = Hash::make($datum['password']);
            //インサート
            $r = UserModel::create($datum);
        } catch(\Throwable $e){
            echo $e->getMessage();
            exit;
        }

        //新規院登録成功
        $request->session()->flash('front.user_register_success', true);
        $request->session()->flash('register_name',$datum['name']);
        return redirect(route('front.index'));
    }
}