<?php

namespace App\Http\Controllers;

use URL;
use Auth;
use File;
use Hash;
use Storage;
use App\Models\Link;
use App\Models\UploadLink;
use App\Models\User; 
use App\Models\Linksarchive;
use Illuminate\Http\Request;
use App\Exports\LinksReportExport;
use App\Exports\TrailLinksReportExport;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\HomeController;

class CronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   

      // EXPORT EXCEL
      public function documentPushCron()
      {
        $links = Link::where('docpush', 0)->orderBy('id', 'desc')->limit(10)->get();
        if(!empty($links)){
          foreach($links as $link){
            $apicrl = new ApiController();
            $pdf_res =  $apicrl->pdfDocUpload($link->proposal_no);
            $pdf_res = json_decode($pdf_res, true);
            if (!empty($pdf_res) && $pdf_res['status'] &&  $pdf_res['data']['status'] != 'FAILED') {
              $docpush = Link::where('proposal_no', $link->proposal_no)->first();
              $docpush->docpush = 1;
              $docpush->save();
            }

          }
        }
      }

      public function bulkUploadData()
      {
        $links = UploadLink::where('create_status', 0)->orderBy('id', 'desc')->limit(10)->get();
        if(!empty($links)){
          foreach($links as $link){
             $apiCtrl = new ApiController();
             $proposal_no = $link->app_no;
            $lists = Link::where('proposal_no', $proposal_no)->first();  
          if (empty($lists)) {
            
            $jsonData =  $link->request;

             $expectedName  = config('partner.name');
              $expectedCode  = config('partner.code');
              $partnerKey    = config('partner.secret');

              $image_base64=null;

              $homeCtrl = new HomeController();
              $jsonDataDec = json_decode($jsonData,true);
              $Life_assured_dob  = $jsonDataDec['Life_assured_dob'];
                $image_base64 = $homeCtrl->getimagebase64($proposal_no, $Life_assured_dob, $expectedName, $expectedCode);
              $jsonDataDec['image_base64'] = $image_base64;
              $jsonData = json_encode($jsonDataDec);

            $request = Request::create('/dummy-url', 'POST', [], [], [], [
                  'CONTENT_TYPE' => 'application/json',
              ], $jsonData);
              $decryptreq = $apiCtrl->testApi($request);
              if($decryptreq){
               //dd($decryptreq);
               $createreq = [
                'TransactionId'=>'12345',
                'ReqPayload'=>$decryptreq,
                'Source'=>'SUD'
               ];

              

              $jsoncreateData = json_encode($createreq);
              $request = Request::create('/dummy-url', 'POST', [], [], [], [
                  'CONTENT_TYPE' => 'application/json',
              ], $jsoncreateData);
              $createreq = $apiCtrl->createPIVCLink($request);
              
              $uploadPush = UploadLink::where('app_no', $proposal_no)->first();
              $uploadPush->create_status = 1;
              $uploadPush->save();

              //print_r($createreq);die;
              }

          }
 
          }
        }
      }
    



}
