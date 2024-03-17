<?php

/*=========================================================================================
  File Name: Web Routes Admin As  User  ---
  
  ----------------------------------------------------------------------------------------
  Item Name: Sky Code Lab 
  Author: Sky Code Lab
  Author URL: https://www.skycodelab.io/
==========================================================================================*/
use App\Http\Controllers\Admin\ChatsController;
use App\Http\Controllers\UserChatsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\User\Auth\RegisteredUserController;
use App\Http\Controllers\UserDashboardController;

use App\Http\Controllers\ProfileController;


/* Admin Routes  */

Route::middleware([
	'auth:sanctum',
])->group(function () {

	Route::group(['prefix' => 'admin'], function () {
		/* ! admin dashboard  */
		Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
		
		// Admin Profile
		Route::get('/profile', [AdminProfileController::class, 'show'])->name('admin.profile.show');
		Route::put('/profile-information', [AdminProfileController::class, 'update'])
			->name('admin.profile.update');


    ///  Chat  
	Route::get('/chat', [ChatsController::class, 'index'])->name('admin.chat.index');
	Route::get('/fetch-messages', [ChatsController::class, 'fetchChatUsers'])->name('admin.fetch.messages');
	Route::post('/messages', [ChatsController::class, 'sendMessage'])->name('admin.sendMessage');
	Route::post('/messages/read/{userId}', [ChatsController::class, 'readMessage'])->name('admin.message.read');

		/* ! Roles & Permissions  */
		Route::resource('role', RoleController::class, ['names' => 'admin.role']);
	
	});

	/***********************************
	 * THIS IS A  staffs Controller *
	 ***********************************/
	Route::get('/manage/staffs', [StaffController::class, 'index'])->name('admin.staffs.index');
	Route::post('/manage/staffs', [StaffController::class, 'store'])->name('admin.staffs.store');
	Route::put('/staffs/{id}', [StaffController::class, 'update'])->name('admin.staffs.update');

	
	});

	
Route::get('/', function () {
	return to_route('user.dashboard');
})->name('home');

/* User Routes */

Route::middleware([
	'auth:sanctum',
	'email.verified',
	'admin.redirect',
	'user.is.active',
])->group(function () {

	Route::prefix('user')->group(function () {
		Route::get('/dashboard', [UserDashboardController::class, 'dashboardUser'])->name('user.dashboard');

		Route::get('/chat', [UserChatsController::class, 'index'])->name('user.chat.index');
		Route::get('/messages', [UserChatsController::class, 'fetchMessages'])->name('user.fetchMessages');;
		Route::post('/messages', [UserChatsController::class, 'sendMessage'])->name('user.sendMessage');;

		
		// Profile
		Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')
			->withoutMiddleware(['admin.redirect']);
		Route::put('/profile-information', [ProfileController::class, 'update'])
			->name('user-profile-information.update')->withoutMiddleware(['admin.redirect']);
		});
	});

	
	/*| Register new user*/
Route::get('/signup', [RegisteredUserController::class, 'UserRegistrationForm'])->name('register');
Route::post('/registration', [RegisteredUserController::class, 'register'])->name('register.store');
/* ====================== */

require __DIR__ . '/auth.php';
