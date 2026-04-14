<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginPostRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function login(AdminLoginPostRequest $request)
    {
        $datum = $request->validated();

        //認証失敗
        if(Auth::guard('admin')->attempt($datum) === false){
            return back()
                ->withInput()
                ->withErrors(['auth' => 'ログインIDかパスワードに誤りがあります'])
                ;
        }

        //認証成功
        $request->session()->regenerate();
        return redirect('/admin/top');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();
        $request->session()->regenerate();
        return redirect(route('admin.index'));
    }
}
