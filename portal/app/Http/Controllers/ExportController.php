<?php

namespace App\Http\Controllers;

use App\Links;
use Illuminate\Http\Request;
use App\Exports\LinksReportExport;
use App\Exports\TrailLinksReportExport;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\ExportMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ExportController extends Controller
{
  public function export()
  {
    /*$filename = "test.xlsx";
    $attachment = Excel::raw(new LinksReportExport('2020-07-11'), BaseExcel::XLSX);
    $subject = "PNB Daily Export";*/

    $date = date('Y-m-d-H')."-00-00";
    $onehour = date('Y-m-d H', strtotime('now -96 hours')).":00:00";
    $sdate = date('Y-m-d H').":00:00";
    /**/
    /*$date = date('Y-m-d H', strtotime('now -1 hour')).":00:00";
    $enddate = date('Y-m-d H', strtotime('now')).":00:00";
    $agreedCount = 0;
    $disagreedCount = 0;
    $links = Links::where('created_at', '>=',  $date)->where('created_at', '<=',  $enddate)->get();
    //$links = Links::whereDate('created_at', $date)->get();
    //$totalLinks = Links::query()->whereDate('created_at', $date);
    $totalLinks = $links->count();
    $notCompleted = Links::where('created_at', '>=' , $date)->where('created_at', '<=',  $enddate)->where('completed_status', 0)->get();
    $notCompletedLinks = $notCompleted->count();
    foreach ($links as $link) {
      if($link->completed_status){
      $ag_per = "";
      $ag_pol = "";
      $ag_rid = "";
      $ag_tc1 = "";

      if(!is_null($link->user_agreement)) {
        $agreement = json_decode(json_encode($link->user_agreement),true);
        $agr_array = array_column($agreement, 'screen');
        if(array_search('personal_details', $agr_array)):
          $ag_per = 'agreed';
        endif;
        if(array_search('policy_details', $agr_array)):
          $ag_pol = $agreement[array_search('policy_details', $agr_array)]['choice'];
        endif;
        if(array_search('rider_details', $agr_array)):
          $ag_rid = $agreement[array_search('rider_details', $agr_array)]['choice'];
        endif;
        if(array_search('terms_and_conditions1', $agr_array)):
          $ag_tc1 = $agreement[array_search('terms_and_conditions1', $agr_array)]['choice'];
        endif;
      }

      if($ag_per == "agreed" && $ag_pol == "agreed" && $ag_rid == "agreed" && $ag_tc1 == "agreed" ){
        $agreedCount++;
      }else{
        $disagreedCount++;
      }
    }
    }

    $metadata = [["name" => "reportDate", "value" => $date], ["name" => 'totalNoOfCases', "value" => $totalLinks], ["name" => 'agreedFor', "value" => $agreedCount], ["name" => 'disagreedFor', "value" => $disagreedCount], ["name" => 'disagreedForPPT', "value" => "-"], ["name" => 'disagreedForTerm', "value" => "-"], ["name" => 'disagreedForProduct', "value" => "-"], ["name" => 'disagreedForSA', "value" => "-"], ["name" => 'disagreedForOthers', "value" => "-"],["name" => 'disagreedForPremium', "value" => "-"], ["name" => 'notCompleted', "value" => $notCompletedLinks]];

    echo json_encode($metadata);*/

    /**/


    Excel::store(new LinksReportExport($onehour, $sdate), $date.'-export.xlsx');
    /*$token = ExportController::generateToken();
    if(isset($token->accessToken)){
      $accToken = $token->accessToken;
      ExportController::sendEmail($accToken);
    }*/
  } 
  

  public function reportsByDate($type, $from, $to = "")
  {
    $date = Carbon::createFromFormat('m-d-Y', $from);
    $from = $date->format('Y-m-d');
    if($to == ""){
      $to = date('Y-m-d');
    } else {
      $todd = Carbon::createFromFormat('m-d-Y', $to);
      $to = $todd->format('Y-m-d');
    }
    $fromDate = date('Y-m-d', strtotime($from))." 00:00:00";
    $toDate = date('Y-m-d', strtotime($to))." 23:59:59";

    return Excel::download(new LinksReportExport($fromDate, $toDate,$type), date('Y-m-d H-i-s')."-report.xlsx");
  }


  public function trailreportsByDate($type, $from, $to = "")
  {
    $date = Carbon::createFromFormat('m-d-Y', $from);
    $from = $date->format('Y-m-d');
    if($to == ""){
      $to = date('Y-m-d');
    } else {
      $todd = Carbon::createFromFormat('m-d-Y', $to);
      $to = $todd->format('Y-m-d');
    }
    $fromDate = date('Y-m-d', strtotime($from))." 00:00:00";
    $toDate = date('Y-m-d', strtotime($to))." 23:59:59";
    

    return Excel::download(new TrailLinksReportExport($type,$fromDate, $toDate), date('Y-m-d H-i-s')."-report.xlsx");
  }


}
