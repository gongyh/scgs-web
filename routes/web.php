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
    Route::any('/addSampleFiles', 'WorkspaceController@addSamples')->middleware('auth');
    Route::any('/addSampleFiles/upload', 'WorkspaceController@addSampleFiles')->middleware('auth');
    Route::get('/manageRunning', 'WorkspaceController@manageRunning')->middleware('auth');
    Route::any('/manageRunning/terminate', 'WorkspaceController@runningTerminate')->middleware('auth');
    Route::get('/manageWaiting', 'WorkspaceController@manageWaiting')->middleware('auth');
    Route::any('/manageWaiting/terminate', 'WorkspaceController@waitingTerminate')->middleware('auth');
    Route::get('/ncbifilesList', 'WorkspaceController@ncbifilesList');
    Route::get('/ncbi_download_status','WorkspaceController@ncbi_download_status');
    Route::get('/weblog_clear','WorkspaceController@weblog_clear');
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
    Route::any('/', 'ExecparamsController@index')->middleware('auth');
    Route::get('/start', 'ExecparamsController@start')->middleware('auth');
    Route::post('/start', 'ExecparamsController@start');
    Route::post('/start/status', 'ExecparamsController@get_status');
});

Route::group(['prefix' => 'successRunning'], function () {
    Route::get('/', 'ResultController@success_running');
    Route::post('/', 'ResultController@preseq_arg_bowtie_checkM');
    Route::post('/home', 'ResultController@home');
    Route::post('/quast', 'ResultController@quast');
    Route::post('/blob', 'ResultController@blob_body');
    Route::post('/get_blob_header', 'ResultController@get_blob_header');
    Route::any('/resultDownload', 'ResultController@download_result');
    Route::any('/blob_classify', 'ResultController@blob_classify');
});

Route::get('/multiqc','ResultController@multiqc_result');
Route::get('/kraken','ResultController@kraken_result');
Route::get('/blob','ResultController@blob_result');
Route::get('/failedRunning', 'ResultController@failed_running');
Route::get('/ramanResult', 'RamanResultController@index');

Route::get('activity/{token}','Auth\RegisterController@activity')->name('user.activity');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/aboutus', 'HomeController@aboutus');

Route::get('/docs/{page_id?}', 'DocController@index')->name('docs');

Route::get('/contact', 'Contact2Controller@index');
Route::post('/contact', 'Contact2Controller@store');

Route::get('/healthcheck', [ScgsHealthcheckController::class, 'handle']);

