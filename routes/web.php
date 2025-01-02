<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Livewire\HomePage;
use App\Livewire\Notifications;
use App\Livewire\ShowPost;
use Illuminate\Support\Facades\Route;
use App\Livewire\Chat;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', HomePage::class)->name('home');

Route::get('/edit-profile', [UserController::class, 'showEditProfileForm'])->name('edit-profile')->middleware('auth');
Route::put('/edit-profile', [UserController::class, 'updateProfile']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/@{username}', [UserController::class, 'profile'])->middleware('verified')->name('profile');
Route::post('/', [PostController::class, 'store'])->middleware('auth')->name('posts.store');

Route::get('/@{username}/question/{id}', ShowPost::class)->name('post.show');

Route::get('/@{username}/question/{id}/edit', [PostController::class, 'edit'])->middleware('auth')->name('post.edit');
Route::put('/@{username}/question/{id}', [PostController::class, 'update'])->middleware('auth')->name('post.update');

Route::delete('/question/{id}', [PostController::class, 'destroy'])->middleware('auth')->name('post.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', Notifications::class)->name('notifications');
});

Route::get('/chat/{chatRoomId?}', Chat::class)->middleware('auth');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');