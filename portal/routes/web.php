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
    return view('auth.login');
});

Auth::routes(['register' => true, 'reset' => false, 'verify' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/get-home-data', [App\Http\Controllers\HomeController::class, 'homeData'])->name('home-data');
Route::post('/changepassword', [App\Http\Controllers\HomeController::class, 'changepassword'])->name('changepassword');
 
Route::get('/list', [App\Http\Controllers\HomeController::class, 'list'])->name('list');
Route::get('/failedProductLinks', [App\Http\Controllers\HomeController::class, 'failedProductLinks'])->name('failedProductLinks');
Route::get('/logslink', [App\Http\Controllers\HomeController::class, 'logslink'])->name('logslink');
// Route::get('/logs', [App\Http\Controllers\HomeController::class, 'logs'])->name('logs');


Route::get('/resend', [App\Http\Controllers\HomeController::class, 'resend'])->name('resend');
Route::post('/resend', [App\Http\Controllers\HomeController::class, 'postresend'])->name('postresend');
Route::get('/retrigger', [App\Http\Controllers\HomeController::class, 'retrigger'])->name('retrigger');

Route::get('/trailLinks', [App\Http\Controllers\HomeController::class, 'trailLinks'])->name('trailLinks');

// Route::get('/linksreportsdownload', [App\Http\Controllers\HomeController::class, 'linksreportsdownload'])->name('linksreportsdownload');

Route::post('/linksreportsdownload', [App\Http\Controllers\HomeController::class, 'linksreportsdownloadexcel'])->name('linksreportsdownloadexcel');

Route::post('/traillinksreportsdownload', [App\Http\Controllers\HomeController::class, 'traillinksreportsdownloadexcel'])->name('traillinksreportsdownloadexcel');

Route::post('/failedproductlinksreportdownload', [App\Http\Controllers\HomeController::class, 'failedproductlinksreportdownloadexcel'])->name('failedproductlinksreportdownloadexcel');

Route::get('/reports-by-date/{type}/{from}/{to?}', [App\Http\Controllers\ExportController::class, 'reportsByDate'])->name('reportsByDate'); 
Route::get('/documentPushCron', [App\Http\Controllers\CronController::class, 'documentPushCron'])->name('documentPushCron'); 
Route::get('/bulkUploadData', [App\Http\Controllers\CronController::class, 'bulkUploadData'])->name('bulkUploadData'); 
                 //new

Route::get('/healthvitallist', [App\Http\Controllers\HomeController::class, 'healthvitallist'])->name('healthvitallist');
Route::post('/healthlinksreportsdownloadexcel', [App\Http\Controllers\HomeController::class, 'healthlinksreportsdownloadexcel'])->name('healthlinksreportsdownloadexcel');

                     //logs
Route::get('/logs', [App\Http\Controllers\HomeController::class, 'logs'])->name('logs');
Route::post('/logs', [App\Http\Controllers\HomeController::class, 'logsPost'])->name('logsPost');
Route::post('/logs', [App\Http\Controllers\HomeController::class, 'logsDetails'])->name('logsDetails');
Route::get('/logsreportdownload/{application_no}', [App\Http\Controllers\HomeController::class, 'logsreportdownload'])->name('logsreportdownloadexcel');
Route::post('/logsreportdownload', [App\Http\Controllers\HomeController::class, 'logsreportdownloadexcel'])->name('logsreportdownloadexcel');

Route::get('/retriggerall', [App\Http\Controllers\HomeController::class, 'retriggerall'])->name('retriggerall');
Route::post('/upload-excel', [App\Http\Controllers\HomeController::class, 'uploadExcel'])->name('excel.upload');

Route::get('/bulkupload', [App\Http\Controllers\HomeController::class, 'bulkupload'])->name('bulkupload');
//Route::post('/bulkupload-excel', [App\Http\Controllers\HomeController::class, 'bulkuploadExcel'])->name('excel.bulkupload');
Route::match(['get', 'post'], '/bulkupload-excel', [App\Http\Controllers\HomeController::class, 'bulkuploadExcel'])->name('excel.bulkupload');

//Route::post('/bulkdownload', [App\Http\Controllers\HomeController::class, 'bulkdownload'])->name('bulkdownload');
//Route::post('/bulkupload-excel', [App\Http\Controllers\HomeController::class, 'bulkuploadExcel'])->name('excel.bulkupload');
// Route::match(['get', 'post'], '/bulkdownload-excel', [App\Http\Controllers\HomeController::class, 'bulkdownloadExcel'])->name('excel.bulkdownload');

//Route::post('/bulkdownloadexcel', [App\Http\Controllers\HomeController::class, 'bulkdownloadexcel'])->name('bulkdownloadexcel');


Route::match(['get', 'post'], '/bulkdownloadexcel', [App\Http\Controllers\HomeController::class, 'bulkdownloadexcel'])->name('bulkdownloadexcel');
