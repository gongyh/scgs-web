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
    Route::any('/upload', 'SamplesController@upload');
    Route::get('/template/download', 'SamplesController@download');
    Route::any('/fileUpload','SamplesController@file_upload');
});

Route::group(['prefix' => 'workspace'], function () {
    Route::get('/', 'WorkspaceController@index')->middleware('auth');
    Route::get('/myLab', 'WorkspaceController@myLab')->middleware('auth');
    Route::get('/myProject', 'WorkspaceController@myProject')->middleware('auth');
    Route::get('/myLab/projects', 'WorkspaceController@myProject')->middleware('auth');
    Route::any('/samples', 'SamplesController@index')->middleware('auth');
    Route::get('/runningSample', 'RunController@index')->middleware('auth');
    Route::get('/addSampleFiles', 'WorkspaceController@addSamples')->middleware('auth');
    Route::any('/addSampleFiles/upload', 'WorkspaceController@addSampleFiles')->middleware('auth');
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
    Route::any('/template/download', 'SpeciesController@download')->middleware('ban_operation');
    Route::any('/upload', 'SpeciesController@upload')->middleware('ban_operation');
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
    Route::get('/start', 'ExecparamsController@start');
    Route::post('/start', 'ExecparamsController@ajax');
});

Route::group(['prefix' => 'successRunning'], function () {
    Route::get('/', 'ResultController@success_running');
    Route::post('/', 'ResultController@ajax');
    Route::any('/resultDownload', 'ResultController@download_result');
});

Route::get('/failedRunning', 'ResultController@failed_running');
Route::get('/ramanResult', 'RamanResultController@index');

Route::get('activity/{token}','Auth\RegisterController@activity')->name('user.activity');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/aboutus', function () {
    return view('aboutus');
});
