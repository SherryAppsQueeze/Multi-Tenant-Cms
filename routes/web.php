<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Tanents\Tanent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TanentController;
use App\Http\Controllers\Auth\AuthController;


Route::get('/', function () {
    return redirect()->route('home');
});


Route::get('/dd', function (Request $request) {
    $subdomain = explode('.', $request->host())[0];

    $tenant = Tanent::where('subdomain', $subdomain)->first();

    dd($tenant);
    app()->instance('currentTenant', $tenant);
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [TanentController::class, 'index'])->name('home');
    Route::get('/stickers/{identifier}', [TanentController::class, 'stickersShow'])->name('stickers.show');
    Route::get('/edit-sticker-category', [TanentController::class, 'edit'])->name('stickers.edit');
    Route::post('/update-sticker-category', [TanentController::class, 'update'])->name('stickers.update');

    Route::get('/category-create', [TanentController::class, 'createIndex'])->name('category.create.index');
    Route::post('/category-create', [TanentController::class, 'create'])->name('category.create');
    Route::get('/category-posts', [TanentController::class, 'categoryPosts'])->name('category.post.show');

    Route::get('/create-category-posts-index', [PostController::class, 'postCreateIndex'])->name('post.create.index');
    Route::post('/create-category-posts', [PostController::class, 'postCreate'])->name('posts.store');
    Route::get('/post-detail-index', [PostController::class, 'postDetail'])->name('post.show');
    Route::put('/post/update/{id}', [PostController::class, 'update'])->name('post.update');


    Route::get('/stickers-category-delete', [TanentController::class, 'delete'])->name('stickers.delete.index');
    Route::get('/stickers-edit-index', [TanentController::class, 'edit'])->name('stickers.edit.index');
    Route::post('/stickers-edit', [TanentController::class, 'update'])->name('stickers.edit.sticker');
    Route::get('/stickers-create-index', [TanentController::class, 'createIndex'])->name('stickers.create.index');
    Route::post('/stickers-create', [TanentController::class, 'create'])->name('stickers.create');


    Route::post('/user-logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('admin-profile', function () {
        return Inertia::render('Auth/Profile');
    })->name('profile');
    Route::post('/admin-profile-update', [TanentController::class, 'updateProfile'])->name('profile.update');
    Route::post('/admin-password-update', [TanentController::class, 'updatePassword'])->name('profile.password.update');
});


//Auth loginS
Route::get('/user-login', function () {
    return Inertia::render('Auth/Login');
})->middleware('guest')->name('login');

Route::get('/user-regitser', function () {
    return Inertia::render('Auth/Register');
})->middleware('guest')->name('user-register');

Route::post('/user-register', [AuthController::class, 'register'])->name('register');
Route::post('/user-login', [AuthController::class, 'login'])->name('user-login');
