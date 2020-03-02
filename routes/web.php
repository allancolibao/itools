<?php

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

Route::get('/', 'MainController@index')->name('home');
Route::any('/search','MainController@search')->name('search');
Route::get('/household/{eacode}/{hcn}/{shsn}','MainController@household')->name('household');
Route::get('/indiv/{eacode}/{hcn}/{shsn}','MainController@individuals')->name('individuals');
Route::get('/indiv/{eacode}/{hcn}/{shsn}/{memcode}/{surname}/{givenname}','MainController@individual')->name('individual');
