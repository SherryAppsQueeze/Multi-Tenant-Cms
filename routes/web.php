<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Tanents\Tanent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TanentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthController;


Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/test-tenant', function () {
    $tenant = app('currentTenant');
    return response()->json($tenant);
});

Route::get('/ddsd', function (Request $request) {
    return response()->json(app('currentTenant'));
});


Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', [TanentController::class, 'index'])->name('home');

    Route::get('/category-create', [CategoryController::class, 'createIndex'])->name('category.create.index');
    Route::post('/category-create', [CategoryController::class, 'create'])->name('category.create');
    Route::get('/category-posts', [CategoryController::class, 'categoryPosts'])->name('category.post.show');
    Route::get('/category-edit', [CategoryController::class, 'categoryedit'])->name('category.edit');
    Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');


    Route::get('/create-category-posts-index', [PostController::class, 'postCreateIndex'])->name('post.create.index');
    Route::post('/create-category-posts', [PostController::class, 'postCreate'])->name('posts.store');
    Route::get('/post-detail-index', [PostController::class, 'postDetail'])->name('post.show');
    Route::put('/post/update/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/delete/{id}', [PostController::class, 'destroy'])->name('post.delete');

    Route::get('admin-profile', function () {
        return Inertia::render('Auth/Profile');
    })->name('profile');
    Route::post('/admin-profile-update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/admin-password-update', [AuthController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/user-logout', [AuthController::class, 'logout'])->name('logout');
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
