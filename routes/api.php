<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/chat/index', [ChatsController::class, 'index'])->name('admin.chat.index');
        Route::get('/profile', [AdminProfileController::class, 'show'])->name('admin.profile.show');
        Route::put('/profile-information', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::get('/fetch-messages-for-chat', [ChatsController::class, 'fetchChatUsers'])->name('admin.fetch.messages');
        Route::post('/messages', [ChatsController::class, 'sendMessage'])->name('admin.sendMessage');
        Route::post('/messages/read/{userId}', [ChatsController::class, 'readMessage'])->name('admin.message.read');
        Route::resource('role', RoleController::class, ['names' => 'admin.role']);
    });

    Route::get('/manage/staffs', [StaffController::class, 'index'])->name('admin.staffs.index');
    Route::post('/manage/staffs', [StaffController::class, 'store'])->name('admin.staffs.store');
    Route::put('/staffs/{id}', [StaffController::class, 'update'])->name('admin.staffs.update');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
