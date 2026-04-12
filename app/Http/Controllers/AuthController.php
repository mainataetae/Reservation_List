<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginPostRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //ログイン画面表示
    public function index()
    {
        return view('index');
    }

    //ログイン処理
    public function login(LoginPostRequest $request)
    {
        $datum = $request->validated();

        //認証失敗
        if(Auth::attempt($datum) === false){
            return back()
                ->withInput()
                ->withErrors(['auth' => 'emailかパスワードに誤りがあります'])
                ;
        }
        
        //認証成功
        $request->session()->regenerate();
        return redirect('/reservation/list');
    }
    
    //ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerateToken();
        $request->session()->regenerate();
        return redirect(route('front.index'));
    }
}
