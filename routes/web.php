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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/{id}', 'HomeController@show')->name('home.show');
Route::post('/allLists','HomeController@store')->name('allLists');
Route::get('api/get-state-list', 'HomeController@getState')->name('stateList');
Route::get('api/get-city-list', 'HomeController@getCity')->name('cityList');
Route::get('api/getFiles', 'HomeController@getFiles')->name('getFiles');

Route::post('/list/{id}/edit', 'HomeController@edit')->name('list.edit');
Route::put('/list/{id}', 'HomeController@update')->name('list.update');
Route::get('/productlist', 'CountryCityStateController@index')->name('productList');

Route::post('projects/media', 'HomeController@storeMedia')->name('projects.storeMedia');
Route::post('projects', 'HomeController@storeImage')->name('projects.store');

Route::get('stripe', 'PaymentController@checkout')->name('stripform');
Route::post('stripe', 'PaymentController@afterpayment')->name('checkout.credit-card');