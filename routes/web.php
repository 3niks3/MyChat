<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatGroupsController;
use App\Http\Middleware\ChatAdminValidation;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[HomeController::class,'home'])->name('home');
Route::get('/new-layout',function(){
    return view('layouts.new-layout');
});

//Not Authorization users urls
Route::group(['middleware' =>'guest:web'],function(){
    Route::get('registration',[HomeController::class,'registration'])->name('registration');
    Route::get('forget-password',[HomeController::class,'forgetPassword'])->name('forget_password');
    Route::post('registration-receiver',[HomeController::class,'registrationReceiver'])->name('registration_receiver');
    Route::post('forget-password-receiver',[HomeController::class,'forgetPasswordReceiver'])->name('forget_password_receiver');
    Route::post('login',[HomeController::class,'login'])->name('login');
});

Route::get('logout',[HomeController::class,'logout'])->name('logout');

//Authorization users urls
Route::group(['middleware'=>['auth:web']],function(){

    //profile URLs
    Route::group(['prefix' => 'profile'], function(){
        Route::get('/',[ProfileController::class,'index'])->name('profile');
        Route::get('/edit',[ProfileController::class,'profileEdit'])->name('profile-edit');
        Route::get('/change-password',[ProfileController::class,'passwordChange'])->name('profile-change-password');


        Route::post('/change-password-receiver',[ProfileController::class,'passwordChangeReceiver'])->name('profile-change-password-receiver');
        Route::post('/edit-receiver',[ProfileController::class,'profileEditReceiver'])->name('profile-edit-receiver');
    });

    //Chat groups URLs
    Route::group(['prefix' => 'chat-group'], function(){

        Route::get('create',[ChatGroupsController::class,'createChatGroup'])->name('chat_group_create');
        Route::get('list',[ChatGroupsController::class,'list'])->name('chat_group_list');
        Route::get('chat/{chat_group}/show',[ChatGroupsController::class,'show'])->name('chat_group_show');
        Route::get('chat/{chat_group}/users',[ChatGroupsController::class,'users'])->name('chat_group_users');
        Route::get('chat/{chat_group}/info',[ChatGroupsController::class,'info'])->name('chat_group_info');
        Route::get('chat/{chat_group}/edit',[ChatGroupsController::class,'edit'])->name('chat_group_edit')->middleware([ChatAdminValidation::class]);


        Route::post('create-receiver',[ChatGroupsController::class,'createChatGroupReceiver'])->name('chat_group_create_receiver');
        Route::post('chat/{chat_group}/edit-receiver',[ChatGroupsController::class,'chatGroupEditReceiver'])->name('chat_group_edit_receiver');
        Route::post('chat/{chat_group}/leave',[ChatGroupsController::class,'leaveGroup'])->name('chat_group_leave');
        Route::post('chat/{chat_group}/join',[ChatGroupsController::class,'joinGroup'])->name('chat_group_join');
        Route::post('chat/{chat_group}/send-message',[ChatGroupsController::class,'sendMessage'])->name('chat_group_send_message');
        Route::post('chat/{chat_group}/load-Messages',[ChatGroupsController::class,'loadMessages'])->name('chat_group_show_load_messages');


        Route::post('datatables-user-chat-group-search',[ChatGroupsController::class,'dataTablesUserChatGroupSearch'])->name('datatables_user_chat_groups_search');
        Route::post('datatables-chat-group-search',[ChatGroupsController::class,'dataTablesChatGroupSearch'])->name('datatables_chat_groups_search');
        Route::post('chat/{chat_group}/datatables-chat-group-members',[ChatGroupsController::class,'dataTablesChatGroupMembersSearch'])->name('datatables_chat_groups_members_search');
    });

});

/*
 * Testing
 */

Route::get('runUserFactory',[\App\Http\Controllers\TestController::class,'runUserFactory']);
Route::get('testMediaLibrary',[\App\Http\Controllers\TestController::class,'testMediaLibrary']);





