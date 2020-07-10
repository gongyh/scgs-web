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
    return view('homepage');
});

Route::get('/projects','ProjectsController@index');

Route::get('/labs', 'LabsController@index');

Route::get('/institutions', 'InstitutionsController@index');

Route::get('/samples', 'SamplesController@index');

Route::get('/status', 'StatusController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
