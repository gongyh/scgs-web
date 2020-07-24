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

Route::group(['prefix' => 'institutions/labs'], function () {
    Route::get('/', 'LabsController@index');
    Route::any('/update', 'LabsController@update')->middleware('ban-labs-update');
    Route::any('/delete', 'LabsController@delete')->middleware('ban-labs-delete');
    Route::any('/create', 'LabsController@create')->middleware('auth');
    Route::get('/projects', 'LabsController@next');
});

// Route::group(['prefix' => 'projects'], function () {
//     Route::get('/', 'ProjectsController@index');
//     Route::any('/update/{id}', 'ProjectsController@update');
//     Route::any('/delete/{id}', 'ProjectsController@delete');
//     Route::get('/projectInfo/{id}', 'ProjectsController@detail');
// });

// Route::group(['prefix' => 'samples'], function () {
//     Route::get('/', 'SamplesController@index');
//     Route::any('/update/{id}', 'SamplesController@update');
//     Route::any('/delete/{id}', 'SamplesController@delete');
// });

Route::get('/status', 'StatusController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
