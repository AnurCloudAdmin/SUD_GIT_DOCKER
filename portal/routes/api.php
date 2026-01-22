<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ComputeHashFromJSON;
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


//Route::post('validatePIVCLink', 'ApiController@validatePIVCLink');
Route::post('/createpivclink',[ApiController::class, 'createPIVCLink']);
Route::get('/linkgenApi/{proposal}',[ApiController::class, 'linkgenApi']);
Route::get('/testApi',[ApiController::class, 'testApi']);
Route::get('/testuploadApi',[ApiController::class, 'testuploadApi']);
Route::get('/testdecryptApi',[ApiController::class, 'testdecryptApi']);

Route::post('/validatePIVCLink',[ApiController::class, 'validatePIVCLink']);
Route::post('/addImage',[ApiController::class, 'addImage']); 
Route::post('/disagreeScreen',[ApiController::class, 'disagreeScreen']);
Route::post('/agreeScreen',[ApiController::class, 'agreeScreen']);
Route::post('/pivclinkstatus',[ApiController::class, 'checkStatus']);
Route::post('/medicalAgree',[ApiController::class, 'medicalAgree']);

Route::post('/getAndSaveLocation',[ApiController::class, 'getAndSaveLocation']);
Route::post('/deviceDetails',[ApiController::class, 'deviceDetails']);
Route::post('/addCapturedImage',[ApiController::class, 'addCapturedImage']);
Route::post('/getProposalPIVCLink',[ApiController::class, 'getProposalPIVCLink']);
Route::post('/generateOTP',[ApiController::class, 'generateOTP']);
Route::post('/addCapturedScreenShot',[ApiController::class, 'addCapturedScreenShot']);
Route::post('/updateLinkResponse',[ApiController::class, 'updateLinkResponse']);
Route::post('/updateEditLinkResponse',[ApiController::class, 'updateEditLinkResponse']);
Route::post('/medicalQuestions',[ApiController::class, 'medicalQuestions']);
Route::post('/addIDCard',[ApiController::class, 'addIDCard']);
Route::post('/addVideo',[ApiController::class, 'addVideo']);
Route::post('/CompleteStatus',[ApiController::class, 'CompleteStatus']);
Route::post('/feedback',[ApiController::class, 'feedback']);
Route::post('/location',[ApiController::class, 'location']);
Route::post('/LangProposalPIVCLink',[ApiController::class, 'LangProposalPIVCLink']);
Route::get('/dataVideoS3Upload',[ApiController::class, 'dataVideoS3Upload']);

Route::post('/getFormProposalPIVCLink', [ApiController::class, 'getFormProposalPIVCLink'])->name('getFormProposalPIVCLink');

Route::get('/pdfgen', [ApiController::class, 'pdfgen'])->name('pdfgen');
Route::get('/genPdf', [ApiController::class, 'genPdf'])->name('genPdf');

//getPDFdownload

Route::get('/pdfDocUpload', [ApiController::class, 'pdfDocUpload'])->name('pdfDocUpload');



Route::post('/resendOTP',[ApiController::class, 'resendOTP']);
Route::post('/resendSMS',[ApiController::class, 'resendSMS']);
Route::post('/retriggerpivclink',[ApiController::class, 'retriggerApi']);
Route::get('/sendEmailTest', [ApiController::class, 'sendEmailTest'])->name('sendEmailTest');

// Route::post('/reactivate',[ApiController::class, 'reactivate'])->name('reactivate');
// Route::post('/reactivate',[ApiController::class, 'reactivateData'])->name('reactivateData');

Route::post('/linkreactivation',[ApiController::class, 'reactivateApi'])->name('reactivateApi');
Route::post('/single-reactivate',[ApiController::class, 'singleReactivate'])->name('single-reactivate');
Route::get('/genPdf/{proposal}',[ApiController::class, 'genPdf'])->name('genPdf');
Route::get('/DownloadPdfArchive/{proposal}/{version}',[ApiController::class, 'DownloadPdfArchive'])->name('DownloadPdfArchive');

Route::post('/callbackurl',[ApiController::class, 'callbackurl'])->name('callbackurl');

//Route::get('/pivcLinkStatus/{proposal}/{pivcStatus}/{pivcSubStatus}/{reason}',[ApiController::class, 'pivcLinkStatus'])->name('pivcLinkStatus');
Route::get('/pdfDocUpload/{proposal}',[ApiController::class, 'pdfDocUpload'])->name('pdfDocUpload');

Route::get('/sendSMSPramerica/{to}/{message}',[ApiController::class, 'sendSMSPramerica'])->name('sendSMSPramerica');

Route::post('/fedostatus',[ApiController::class, 'fedostatus']);
Route::post('/fedoVitalsApi',[ApiController::class, 'fedoVitalsApi']);

Route::post('/updateQuestions',[ApiController::class, 'update_Questions']);

Route::post('/shashaktPivcstatus',[ApiController::class, 'shashakt_pivcstatus']);
Route::post('/update_Page',[ApiController::class, 'update_Page']);

Route::get('/updateExcel',[ApiController::class, 'updateExcel']);

Route::get('/updatefacescore',[ApiController::class, 'updatefacescore']);

Route::post('/getCallbackurllog',[ApiController::class, 'getCallbackurllog']);

Route::get('/docCheckstatus',[ApiController::class, 'docCheckstatus']);

Route::post('/testaddVideo',[ApiController::class, 'testaddVideo']);
Route::post('/image_base64check',[ApiController::class, 'image_base64check']);

Route::post('/pushuploadtoD',[ApiController::class, 'pushuploadtoD']);

Route::post('/testspeechToText',[ApiController::class, 'testspeechToText']);

Route::post('/singleimagereturn',[ApiController::class, 'singleimagereturn']);

Route::post('/compute-hash', [ComputeHashFromJSON::class, 'generateHashFromRequest']);

Route::post('/checkKannada',[ApiController::class, 'checkKannada']);

Route::get('/copyApplicationImages',[ApiController::class, 'copyApplicationImages']);


Route::get('/downloadfile/{proposal}',[ApiController::class, 'downloadfile']);

//Route::get('/downloadPDF/{proposal}',[ApiController::class, 'downloadPDF']);

Route::get('/test-public-path', function () {
    return public_path();
});

Route::get('/run-script/{image64}', function () {
    include public_path('scripts/sample.php');
});
//Route::get('/video_check_api/{proposal}',[ApiController::class, 'video_check_api']);

Route::post('/video_check_api',[ApiController::class, 'video_check_api']);