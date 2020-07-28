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
    Route::get('/', 'InstitutionsController@index');
    Route::any('/update/{id}', 'InstitutionsController@update')->middleware('ban-insti-opt');
    Route::any('/delete/{id}', 'InstitutionsController@delete')->middleware('ban-insti-opt');
    Route::any('/create', 'InstitutionsController@create')->middleware('ban-insti-opt');
});

Route::group(['prefix' => 'labs'], function () {
    Route::get('/', 'LabsController@index');
    Route::any('/update', 'LabsController@update')->middleware('ban-labs-update');
    Route::any('/delete', 'LabsController@delete')->middleware('ban-labs-delete');
    Route::any('/create', 'LabsController@create')->middleware('auth');
});

Route::group(['prefix' => 'projects'], function () {
    Route::get('/', 'ProjectsController@index');
    Route::any('/update', 'ProjectsController@update');
    Route::any('/delete', 'ProjectsController@delete');
    Route::any('/create', 'ProjectsController@create');
    Route::get('/projectInfo', 'ProjectsController@detail');
});

Route::group(['prefix' => 'samples'], function () {
    Route::get('/', 'SamplesController@index');
    Route::any('/update', 'SamplesController@update');
    Route::any('/create', 'SamplesController@create');
    Route::any('/delete', 'SamplesController@delete');
});

Route::get('/status', 'StatusController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
