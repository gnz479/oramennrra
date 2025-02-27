<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//crear excel con data de quickbase
Route::post('/data-excel', 'Admin\ExcelController@generateExcel')->middleware(['api.token', 'throttle:create_csv']);
Route::get('/download-csv/{filename}', 'Admin\ExcelController@downloadCsv')->name('download.csv')
        ->middleware(['api.token', 'throttle:download_csv']);
