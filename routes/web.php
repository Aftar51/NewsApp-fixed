<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CategoryController;

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

Route::get('/', [\App\Http\Controllers\Frontend\FrontendController::class,'index'] );
Route::get('/detail/news/{slug}', [\App\Http\Controllers\Frontend\FrontendController::class, 'detailNews'])->name('detailNews');
Route::get('/detail/category/{slug}', [\App\Http\Controllers\Frontend\FrontendController::class, 'detailCategory'])->name('detailCategory');


Auth::routes();

//handle redirect register to login
// Route::match(['get','post'], '/register', 
//     function(){
//         return redirect('/login');
// });

//Route Middleware
Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile', [\App\Http\Controllers\Profile\ProfileController::class,'index'])->name('profile.index');
    Route::get('/change-password',[\App\Http\Controllers\Profile\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/update-password',[\App\Http\Controllers\Profile\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/create-profile', [\App\Http\Controllers\Profile\ProfileController::class, 'createProfile'])->name('createProfile');
    Route::post('/store-profile',[\App\Http\Controllers\Profile\ProfileController::class,'storeProfile'])->name('storeProfile');
    Route::get('/edit-profile',[\App\Http\Controllers\Profile\ProfileController::class,'editProfile'])->name('editProfile');
    Route::put('/update-profile',[\App\Http\Controllers\Profile\ProfileController::class,'updateProfile'])->name('updateProfile');
    

    //Route for admin
    // middleware admin diamana kita membuat middleware sendiri
    // middleware ini akan memeriksa apakah user yang login adalah admin
    // jika ya, maka user akan diarahkan ke halaman admin
    // jika tidak, maka user akan diarahkan ke halaman home
    // diambil dari app/Http/Middleware/isAdmin.php 
    // yang didaftrakan di app/Http/Kernel.php
    Route::middleware(['auth', 'admin'])
        ->group(function () {
            //Route for News using Resource
            Route::resource('news', NewsController::class);
            //Route for Category using Resource

            // fungsi except('show') itu untuk menghilangkan fungsi show
            // karena kita tidak akan menggunakan show
            // fungsi only('index') itu untuk menampilkan fungsi index saja
            // karena kita hanya akan menggunakan index
            Route::resource('category', CategoryController::class)->except('show');

            //get all user
            Route::get('/all-user',[\App\Http\Controllers\Profile\ProfileController::class, 'allUser'])->name('allUser');
            // reset password user
            Route::put('/reset-password/{id}',[\App\Http\Controllers\Profile\ProfileController::class, 'resetPassword'])->name('resetPassword');

    });
});

//storage:link
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'storage:link berhasil dijalankan';
});

//config:cache
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'config:cache berhasil dijalankan';
});

//config:clear
Route::get('/config-clear', function () {
    Artisan::call('config:clear');
    return 'config:clear berhasil dijalankan';
});

//cache:clear
Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    return 'confiq:cear berhasil dijalankan';
});

//view:clear
Route::get('/view-clear', function () {
    Artisan::call('view:clear');
    return 'view:clear berhasil dijalankan';
});

//view:cache
Route::get('/view-cache', function () {
    Artisan::call('view:cache');
    return 'view:cache berhasil dijalankan';
});

//route:clear
Route::get('/route-clear', function () {
    Artisan::call('route:clear');
    return 'route:clear berhasil dijalankan';
});

//route:cache
Route::get('/route-cache', function () {
    Artisan::call('route:cache');
    return 'route:cache berhasil dijalankan';
});
