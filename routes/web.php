<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/summary', 'AdminController@summary')->name('admin.summary');

Route::get('/countdown', function () {
    return view('countdown');
})->name('countdown');

Route::get('/rules', function () {
    return view('rules');
})->name('rules');

// 1 requests per minute
Route::post('/submitFlag', 'HomeController@submitFlag')->middleware(['throttle:1,1'])->name('user.submit.flag');

// 1 requests per minute
Route::post('/uploadReport', 'HomeController@uploadReport')->middleware(['throttle:1,1'])->name('user.upload.report');
Route::post('/requestHint', 'HomeController@handleRequestHint')->middleware(['throttle:1,10'])->name('user.request.hint');

Route::middleware(['check.if.admin', 'auth'])->prefix('/admin')->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::post('/saveSettings', 'AdminController@saveSettings')->name('admin.save.settings');
    Route::get('/hintRequests', 'AdminController@showHintRequests')->name('admin.show.hint.requests');
    Route::post('/toggleActiveStatus', 'AdminController@toggleActiveStatus')->name('admin.toggle.active');
    Route::post('/updateCost', 'AdminController@updateCost')->name('admin.update.cost');

    Route::post('/store/box', 'AdminController@storeBox')->name('admin.store.box');
    Route::post('/store/team', 'AdminController@storeTeam')->name('admin.store.team');

    Route::get('/box/{id}', 'AdminController@showBox')->name('admin.show.box');
    Route::delete('/box/{id}', 'AdminController@deleteBox')->name('admin.delete.box');
    Route::post('/storeFlag', 'AdminController@storeFlag')->name('admin.store.flag');
    Route::post('/deleteFlag', 'AdminController@deleteFlag')->name('admin.delete.flag');
    Route::delete('/team/{id}', 'AdminController@deleteTeam')->name('admin.delete.team');

    Route::get('/team/{id}', 'AdminController@showTeam')->name('admin.show.team');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
