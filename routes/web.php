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

Route::get('/', function () {
    return view('welcome');
});

//social login
Route::get('login/{provider?}', 'SocialLoginController@redirectToProvider');
Route::get('callback/{provider}', 'SocialLoginController@handleProviderCallback');

Route::get('/logout', 'SocialLoginController@logout');
