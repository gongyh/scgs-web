<?php

use App\Http\Controllers\InstitutionsController;
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
    return view('homepage');
});

Route::group(['prefix' => 'institutions'], function () {
    Route::get('/', 'InstitutionsController@index')->middleware('auth');
    Route::any('/update/{id}', 'InstitutionsController@update');
    Route::any('/delete/{id}', 'InstitutionsController@delete');
});

Route::group(['prefix' => 'labs'], function () {
    Route::get('/', 'LabsController@index')->middleware('auth');
    Route::any('/update/{id}', 'LabsController@update');
    Route::any('/delete/{id}', 'LabsController@delete');
});

Route::group(['prefix' => 'projects'], function () {
    Route::get('/', 'ProjectsController@index')->middleware('auth');
    Route::any('/update/{id}', 'ProjectsController@update');
    Route::any('/delete/{id}', 'ProjectsController@delete');
    Route::get('/projectInfo/{id}', 'ProjectsController@detail');
});

Route::group(['prefix' => 'samples'], function () {
    Route::get('/', 'SamplesController@index');
    Route::any('/update/{id}', 'SamplesController@update');
    Route::any('/delete/{id}', 'SamplesController@delete');
});

Route::get('/status', 'StatusController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
