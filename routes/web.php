<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('test');
});

Route::get('/send-email', 'Email\EmailController@sendEmail')->name('email.send');
Route::get('/get-tasks-to-notifications', 'Email\EmailController@getTasksToNotificacion');
Route::get('/set-tasks-complete/{id}', 'Email\EmailController@taskCompleted')->name('taskComplete');

//carga de archivos
Route::get('/drag-and-drop-files', 'UploadFiles\UploadFileController@dragAndDrop');

//
Route::get('/task-test', 'Email\EmailController@taskAdded');