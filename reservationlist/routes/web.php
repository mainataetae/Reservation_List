<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//ログイン画面
Route::get('/',[AuthController::class, 'index'])->name('front.index');
Route::post('/login',[AuthController::class, 'login']);

//新規院登録
Route::prefix('/user')->group(function(){
Route::get('/register',[UserController::class, 'index'])->name('front.user.register');
Route::post('/register',[UserController::class,'register'])->name('front.user.register.post');
});

//予約画面
Route::middleware(['auth'])->group(function(){
    Route::prefix('/reservation')->group(function(){
        Route::get('/list', [ReservationController::class, 'list'])->name('reservation.list');
        Route::get('/create',[ReservationController::class,'create'])->name('reservation.create');
        Route::get('/detail/{reservation_id}',[ReservationController::class,'detail'])->whereNumber('reservation_id')->name('reservation.detail');
        Route::delete('/delete/{reservation_id}',[ReservationController::class,'delete'])->name('reservation.delete');
        Route::get('/edit/{reservation_id}',[ReservationController::class,'edit'])->whereNumber('reservation_id')->name('reservation.edit');
        Route::put('/edit/{reservation_id}',[ReservationController::class,'editSave'])->whereNumber('reservation_id')->name('reservation.edit_save');
        Route::post('/register',[ReservationController::class,'register'])->name('reservation.register');
    });
    Route::get('/logout',[AuthController::class,'logout']);
});

//管理画面
Route::prefix('/admin')->group(function(){
    Route::get('/',[AdminAuthController::class,'index'])->name('admin.index');
    Route::post('/login',[AdminAuthController::class,'login']);
    Route::middleware(['auth:admin'])->group(function(){
        Route::get('/top',[AdminHomeController::class,'top'])->name('admin.top');
        Route::get('/logout',[AdminAuthController::class,'logout']);
        Route::get('/user/list',[AdminUserController::class,'list'])->name('admin.user.list');
        Route::get('/user/monthranking',[AdminUserController::class,'monthranking'])->name('admin.user.monthranking');
        Route::get('/user/yearranking',[AdminUserController::class,'yearranking'])->name('admin.user.yearranking');
    });
    //管理者登録
    Route::get('register',[AdminUserController::class,'index'])->name('admin.user.register');
    Route::post('register',[AdminUserController::class,'register'])->name('admin.user.register.post');
});