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

// Landing page
Route::get('/', 'HomeController@index')->name('landing-page');

// Authentication Controller
Auth::routes();

// Main Controller
Route::get('/home', 'MainController@index')->name('home');
Route::any('/search','MainController@search')->name('search');
Route::get('/household/{eacode}/{hcn}/{shsn}/{hh}','MainController@household')->name('household');
Route::get('/listings/{eacode}','MainController@listings')->name('listings');
Route::get('/indiv/{eacode}/{hcn}/{shsn}','MainController@individuals')->name('individuals');
Route::get('/indiv/{eacode}/{hcn}/{shsn}/{memcode}/{surname}/{givenname}','MainController@individual')->name('individual');
Route::get('/replacement/{eacode}/{hcn}/{shsn}/{hh}', 'MainController@tickReplacement')->name('replacement');

// Update
Route::patch('/update-household/{eacode}/{hcn}/{shsn}/{hh}','MainController@updateHousehold')->name('update-household');
Route::patch('/update-individuals/{eacode}/{hcn}/{shsn}/{memcode}/{surname}/{givenname}','MainController@updateIndividuals')->name('update-individuals');
Route::patch('/save-replacement/{eacode}/{hcn}/{shsn}/{hh}','MainController@saveReplacement')->name('save-replacement');

// Delete
Route::get('/to-delete/{eacode}/{hcn}/{shsn}/{hh}', 'MainController@todeleteListings')->name('to-delete-listings');
Route::delete('/delete/{eacode}/{hcn}/{shsn}/{hh}', 'MainController@delete')->name('delete-listings');
Route::get('/to-delete-all-tables/{eacode}/{hcn}/{shsn}/{hh}', 'MainController@todeleteAllTables')->name('to-delete-all-tables');
Route::delete('/delete-all-tables/{eacode}/{hcn}/{shsn}/{hh}', 'MainController@deleteAllTables')->name('delete-all-tables');
Route::get('/to-delete-individual/{eacode}/{hcn}/{shsn}/{memcode}/{surname}/{givenname}', 'MainController@todeleteIndividual')->name('to-delete-individual');
Route::delete('/delete-individual/{eacode}/{hcn}/{shsn}/{memcode}/{surname}/{givenname}','MainController@deleteIndividual')->name('delete-individual');
