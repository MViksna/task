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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/debt', 'DebtController@index')->name('debt');
Route::post('/store-debt', 'DebtController@store')->name('store-debt');

Route::get('/payment', 'PaymentController@index')->name('payment');
Route::post('/store-payment', 'PaymentController@store')->name('store-payment');


Route::get('/reports', 'ReportsController@index')->name('reports');
Route::get('/report-lists', 'ReportsController@reportLists')->name('report-lists');
Route::post('/report-payment', 'ReportsController@reportPayment')->name('report-payment');
Route::post('/report-debt', 'ReportsController@reportDebt')->name('report-debt');
Route::post('/report-total', 'ReportsController@reportTotal')->name('report-total');
