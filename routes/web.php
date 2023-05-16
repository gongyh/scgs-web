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
    Route::any('update', 'LabsController@update')->middleware('ban_labs_update');
    Route::any('delete', 'LabsController@delete')->middleware('ban_operation');
    Route::any('create', 'LabsController@create')->middleware('auth');
    Route::get('projects', 'ProjectsController@selectProj');
});

Route::middleware('auth')->group(function () {
    Route::any('projects/', 'ProjectsController@index')->withoutMiddleware('auth');
    Route::any('projects/update', 'ProjectsController@update');
    Route::any('projects/delete', 'ProjectsController@delete');
    Route::any('projects/create', 'ProjectsController@create');
});

Route::middleware('auth')->group(function () {
    Route::get('samples/', 'SamplesController@index')->withoutMiddleware('auth');
    Route::any('samples/update', 'SamplesController@update');
    Route::any('samples/create', 'SamplesController@create');
    Route::any('samples/delete', 'SamplesController@delete');
    Route::any('samples/upload', 'SamplesController@upload');
    Route::get('samples/template/download', 'SamplesController@download');
    Route::any('samples/fileUpload', 'SamplesController@file_upload');
});

Route::middleware('auth')->group(function () {
    Route::get('workspace/', 'WorkspaceController@index');
    Route::get('workspace/myLab', 'WorkspaceController@myLab');
    Route::get('workspace/myProject', 'WorkspaceController@myProject');
    Route::get('workspace/myLab/projects', 'WorkspaceController@myProject');
    Route::any('workspace/samples', 'SamplesController@index');
    Route::get('workspace/runningSample', 'RunController@index');
    Route::any('workspace/addSampleFiles', 'WorkspaceController@addSamples');
    Route::any('workspace/addSampleFiles/upload', 'WorkspaceController@addSampleFiles');
    Route::get('workspace/manageRunning', 'WorkspaceController@manageRunning');
    Route::any('workspace/manageRunning/terminate', 'WorkspaceController@runningTerminate');
    Route::get('workspace/manageWaiting', 'WorkspaceController@manageWaiting');
    Route::any('workspace/manageWaiting/terminate', 'WorkspaceController@waitingTerminate');
    Route::get('workspace/ncbifilesList', 'WorkspaceController@ncbifilesList');
    Route::get('workspace/ncbi_download_status', 'WorkspaceController@ncbi_download_status');
    Route::get('workspace/weblog_clear', 'WorkspaceController@weblog_clear');
});

Route::middleware('ban_operation')->group(function () {
    Route::get('workspace/institutions/', 'InstitutionsController@index');
    Route::any('workspace/institutions/create', 'InstitutionsController@create');
    Route::any('workspace/institutions/delete', 'InstitutionsController@delete');
    Route::any('workspace/institutions/update', 'InstitutionsController@update');
});

Route::middleware('ban_operation')->group(function () {
    Route::get('workspace/species/', 'SpeciesController@index');
    Route::any('workspace/species/update', 'SpeciesController@update');
    Route::any('workspace/species/create', 'SpeciesController@create');
    Route::any('workspace/species/delete', 'SpeciesController@delete');
    Route::any('workspace/species/template/download', 'SpeciesController@download');
    Route::any('workspace/species/upload', 'SpeciesController@upload');
});

Route::middleware('ban_operation')->group(function () {
    Route::get('workspace/applications', 'ApplicationsController@index');
    Route::any('workspace/applications/update', 'ApplicationsController@update');
    Route::any('workspace/applications/create', 'ApplicationsController@create');
    Route::any('workspace/applications/delete', 'ApplicationsController@delete');
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
    Route::get('/', 'ResultController@success_running')->middleware('browse_result');
    Route::post('/home', 'ResultController@home');
    Route::post('/quast', 'ResultController@quast');
    Route::post('/blob', 'ResultController@blob_body');
    Route::post('/get_blob_header', 'ResultController@get_blob_header');
    Route::any('/blob_classify', 'ResultController@blob_classify');
    Route::any('/resultDownload', 'ResultController@download_result');
    Route::post('/preseq', 'ResultController@preseq');
    Route::post('/arg', 'ResultController@arg');
    Route::post('/bowtie', 'ResultController@bowtie');
    Route::post('/checkM', 'ResultController@checkM');
    Route::post('/eukcc', 'ResultController@eukcc');
});

Route::get('/multiqc', 'ResultController@multiqc');
Route::get('/kraken', 'ResultController@kraken');
Route::get('/blob', 'ResultController@blob_result');
Route::get('/failedRunning', 'ResultController@failed_running');
Route::get('/ramanResult', 'RamanResultController@index');

Route::get('activity/{token}', 'Auth\RegisterController@activity')->name('user.activity');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/aboutus', 'HomeController@aboutus');

Route::get('/docs/{page_id?}', 'DocController@index')->name('docs');

Route::get('/contact', 'Contact2Controller@index');
Route::post('/contact', 'Contact2Controller@store');

Route::get('/health', 'ScgsHealthcheckController@handle');

Route::post('/authenticate', 'AuthenticateController@index');

