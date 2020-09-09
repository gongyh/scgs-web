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

Route::group(['prefix' => 'labs'], function () {
    Route::any('/update', 'LabsController@update');
    Route::any('/delete', 'LabsController@delete');
    Route::any('/create', 'LabsController@create')->middleware('auth');
    Route::get('/projects', 'ProjectsController@selectProj');
});

Route::group(['prefix' => 'projects'], function () {
    Route::any('/', 'ProjectsController@index');
    Route::any('/update', 'ProjectsController@update');
    Route::any('/delete', 'ProjectsController@delete');
    Route::any('/create', 'ProjectsController@create')->middleware('auth');
    Route::get('/projectInfo', 'ProjectsController@detail');
});

Route::group(['prefix' => 'samples'], function () {
    Route::get('/', 'SamplesController@index');
    Route::any('/update', 'SamplesController@update');
    Route::any('/create', 'SamplesController@create');
    Route::any('/delete', 'SamplesController@delete');
});

Route::group(['prefix' => 'workspace'], function () {
    Route::get('/', 'WorkspaceController@index')->middleware('auth');
    Route::get('/myLab', 'WorkspaceController@myLab')->middleware('auth');
    Route::get('/myProject', 'WorkspaceController@myProject')->middleware('auth');
    Route::get('/myLab/projects', 'WorkspaceController@selectMyProj')->middleware('auth');
    Route::get('/samples', 'WorkspaceController@selectSamples')->middleware('auth');
});

Route::group(['prefix' => 'workspace/institutions'], function () {
    Route::get('/', 'InstitutionsController@index')->middleware('ban_operation');
    Route::any('/create', 'InstitutionsController@create')->middleware('ban_operation');
    Route::any('/delete', 'InstitutionsController@delete')->middleware('ban_operation');
    Route::any('/update', 'InstitutionsController@update')->middleware('ban_operation');
});

Route::group(['prefix' => 'workspace/species'], function () {
    Route::get('/', 'SpeciesController@index')->middleware('ban_operation');
    Route::any('/update', 'SpeciesController@update')->middleware('ban_operation');
    Route::any('/create', 'SpeciesController@create')->middleware('ban_operation');
    Route::any('/delete', 'SpeciesController@delete')->middleware('ban_operation');
});

Route::group(['prefix' => 'workspace/applications'], function () {
    Route::get('/', 'ApplicationsController@index')->middleware('ban_operation');
    Route::any('/update', 'ApplicationsController@update')->middleware('ban_operation');
    Route::any('/create', 'ApplicationsController@create')->middleware('ban_operation');
    Route::any('/delete', 'ApplicationsController@delete')->middleware('ban_operation');
});

Route::group(['prefix' => 'workspace/pipelineParams'], function () {
    Route::get('/', 'PipelineParamsController@index');
    Route::any('/update', 'PipelineParamsController@update');
});

Route::group(['prefix' => 'execute'], function () {
    Route::any('/', 'ExecparamsController@index');
    Route::any('/run', 'ExecparamsController@run');
});

Route::get('/aboutus', function () {
    return view('aboutus');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
