<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Use by default "admin" route prefix
 * Use by default "auth:admin" Middleware
 */

Route::group(['middleware' =>'guest:admin'],function(){
    Route::get('/',[AdminController::class,'index'])->name('admin_login')->withoutMiddleware('auth:admin');
    Route::post('/login-action',[AdminController::class,'loginAction'])->name('admin_login_action')->withoutMiddleware('auth:admin');
});



Route::get('/home',[AdminController::class,'home'])->name('admin_home');

