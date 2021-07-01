<?php

use Illuminate\Support\Facades\Route;

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

// Admin Section
Route:: middleware(['auth'])->prefix('admin')->group(function () {
    //Route::get('/', 'AdminController@index')->name('dashboard');
    Route::get('/', function () {
        return redirect('/admin/search');
    });
    Route::get('/my-numbers', 'NumbersController@myNumbers')->name('my-numbers');
    Route::get('/search', 'NumbersController@search')->name('search');
    Route::post('/reserve', 'NumbersController@reserveNumbers');

    // Forward Numbers
    Route::get('/forward', 'NumbersController@forwardNumbers');
    Route::post('/forward', 'NumbersController@forwardCreate');
    Route::get('/forward/{id}', 'NumbersController@forwardEdit');
    Route::put('/forward/{id}', 'NumbersController@forwardUpdate');

    // Admin Roles
    Route::group(['middleware' => ['role:admin']], function () {
        // Register new users
        Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('/register', 'Auth\RegisterController@register');
        // Delete user
        //Route::delete('delete/{id}');

        // Show all numbers
        Route::get('all-numbers', 'NumbersController@myNumbers')->name('all-numbers');

    });

    //Redirect
    Route::get('/reserve', function() {
        return redirect('/admin/search');
    });
});

// Forgot Password
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

// Reset Password
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

// Login
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');