<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/summary', 'AdminController@summary')->name('admin.summary');

Route::post('/submitFlag', 'HomeController@submitFlag')->middleware(['throttle:3,1'])->name('user.submit.flag');
// 3 requests per minute

Route::middleware(['check.if.admin', 'auth'])->prefix('/admin')->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.home');

    Route::post('/store/box', 'AdminController@storeBox')->name('admin.store.box');
    Route::post('/store/team', 'AdminController@storeTeam')->name('admin.store.team');

    Route::get('/box/{id}', 'AdminController@showBox')->name('admin.show.box');
    Route::delete('/box/{id}', 'AdminController@deleteBox')->name('admin.delete.box');
    Route::post('/storeFlag', 'AdminController@storeFlag')->name('admin.store.flag');
    Route::post('/deleteFlag', 'AdminController@deleteFlag')->name('admin.delete.flag');

    Route::get('/team/{id}', 'AdminController@showTeam')->name('admin.show.team');
});


