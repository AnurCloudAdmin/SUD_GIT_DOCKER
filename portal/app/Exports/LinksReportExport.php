<?php
namespace App\Exports;
// use App\Transaction;
// use App\Proposal;
use App\Models\Link;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use File;

class LinksReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{ 
  use Exportable;

  public function __construct($min,$max,$statuscheck)
  { 
     //echo $statuscheck; die;
    
    $this->min = $min;
    $this->max = $max; 
    $this->statuscheck = $statuscheck; 
    
  }

  public function query()
  {

    //dd($this->statuscheck);
    set_time_limit(0);
    $query = Link::query()->whereDate('created_at', '>=', $this->min)->whereDate('created_at', '<=', $this->max);
    
    if($this->statuscheck=='0'){
      //return Link::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max); 

      return Link::query()
      ->selectRaw("*, 
          CONVERT(VARCHAR, DATEADD(SECOND, 
              DATEDIFF(SECOND, curr_openstatus, completed_on), 0), 108) AS duration")
      ->whereDate('created_at', '>=', $this->min)
      ->whereDate('created_at', '<=', $this->max);
    } 
    if($this->statuscheck==1){
      return Link::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max)->where('complete_status',0)->where('is_open',1);
    }
    if($this->statuscheck==2){
      // return Link::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max)->where('complete_status',1)->where('personal_disagree',0)->where('policy_disagree',0);//->WhereRaw(" (face_score is null OR face_score >=40 )");

       return Link::query()
    ->selectRaw("*, 
        CONVERT(VARCHAR, DATEADD(SECOND, 
            DATEDIFF(SECOND, curr_openstatus, completed_on), 0), 108) AS duration")
    ->whereDate('created_at', '>=', $this->min)
    ->whereDate('created_at', '<=', $this->max)
    ->where('complete_status', 1)
    ->where('personal_disagree', 0)
    ->where('policy_disagree', 0);
    //->whereNotNull('curr_openstatus')
    //->whereNotNull('completed_on');


   
    //->get();

    

    }
    if($this->statuscheck==3){
       //return Link::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max)->where('complete_status',1) ->WhereRaw(" (personal_disagree = 1 or  policy_disagree = 1)"); 
      //dd('dfd');
      
      return Link::query()
    ->selectRaw("*, 
        CONVERT(VARCHAR, DATEADD(SECOND, 
            DATEDIFF(SECOND, curr_openstatus, completed_on), 0), 108) AS duration")
    ->whereDate('created_at', '>=', $this->min)
    ->whereDate('created_at', '<=', $this->max)
    ->where('complete_status', 1)
    ->where(function ($query) {
        $query->where('personal_disagree', 1)
              ->orWhere('policy_disagree', 1);
    });
    //->whereNotNull('curr_openstatus')
    //->whereNotNull('completed_on');

    }
    if($this->statuscheck=='4'){
      return Link::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max)->where('is_open',0); 
    }
    return $query;
  }
  public function map($export): array
  {
    // echo "<pre>";

     //dd($export); //die;
    $speechscore=0;
    $facescore=0;
    if(isset($export->speech_res)){

           // Check if it contains HTTP headers
            if (strpos($export->speech_res, "\r\n\r\n") !== false) {
                // Split headers and body
                list(, $body) = explode("\r\n\r\n",$export->speech_res, 2);
            } else {
                // It's plain JSON
                $body = $export->speech_res;
            }

            // Decode the JSON stored in speech_response
            $speechResponse = json_decode($body, true); //dd($speechResponse);

      //$speechResponse = json_decode($export->speech_res, true);//print_r($speechResponse);
      if(isset($speechResponse['score'])){
        $speechscore = $speechResponse['score'];
      }
      else{
        $speechscore = 0;
      }
    }

    $feedbck=null;
    $feedbckscr=0;
    if(isset($export->feedback)){
      $feedbackresp = json_decode($export->feedback, true);
      if(isset($feedbackresp['feedback'])){
        $feedbck = $feedbackresp['feedback'];
      }
      else{
        $feedbck = null;
      }

      if(isset($feedbackresp['category'])){
        $feedbckscr = $feedbackresp['category'];
      }
      else{
        $feedbckscr = 0;
      }
      
    }

    $personalConsent =  $policyConsent = null;

    if($export->complete_status==1){
         $personalConsent = ($export->personal_disagree == 0) ? "Agree" : "Disagree";
         $policyConsent   = ($export->policy_disagree == 0) ? "Agree" : "Disagree";
    }else{
         if($export->is_open==1){
             if(!empty($export->personal_disagree_response) || !empty($export->policy_disagree_response)){
             $personalConsent = ($export->personal_disagree == 0) ? "Agree" : "Disagree";
             $policyConsent   = ($export->policy_disagree == 0) ? "Agree" : "Disagree";}
             else{
              $personalConsent =  $policyConsent = null;
             }
         }
         
    }
   
    

    $face_score  =  'No';
    if($export->face_score !=null && $export->face_score >=40){
       $face_score  =  'Yes';
    }else if (is_null($export->face_score)) {
       $face_score  =  'Yes';
    }

    $sys_lang = '';
    $languages = [
       'eng' => 'English',
       'hin' => 'Hindi',
       'tam' => 'Tamil',
       'ben' => 'Bengali',
       'tel' => 'Telugu',
       'kan' => 'Kannada',
       'mar' => 'Marathi',
       'guj' => 'Gujarati',
       'ass' => 'Assamese',
       'mal' => 'Malayalam',
       'pun' => 'Punjabi',
       'ori' => 'Oriya',
    ];


    if($export->sys_lang != null){
      $sys_lang = $languages[$export->sys_lang] ?? 'Unknown Language';
    }else{
      $sys_lang = 'English';
    }
    
   $params = json_decode($export->params,true);


   if( $export->status == "1") { $export->status = "Active"; } else { $export->status = "In active"; }
   if( $export->is_open == "1") { $export->is_open = "Is open"; } else { $export->is_open = "Not open"; }

   
   $questions =json_decode($export->questions);
   $question1 = isset($questions[0]->Q1) ? $questions[0]->Q1 : "";
   $question2 = isset($questions[1]->Q2) ? $questions[1]->Q2 : "";

   $medical_disagree='--';
   if($export->complete_status == 1){
     if( $question1=='AGREE' && $question2=='AGREE' ) 
     $medical_disagree='Agreed';
     else
     $medical_disagree= 'Disagree';

   }
   
   $finstatus='';

       $applicationNumber = $params['Application_Number'];
       $public_path = public_path();
       $image_url = $public_path.'/upload/' . $applicationNumber . '/img/link_upload.jpeg';

   if( $export->complete_status == "1") { 

    //dd($export->personal_disagree);

    if( $export->personal_disagree==0 && $export->policy_disagree==0) { 

      $export->complete_status = "Completed";

      }else {
        $export->complete_status = "Completed with Discrepancy";
      }

      $finstatus = (($export->face_score < 30) ||
      ($speechscore < 65) ||
      $personalConsent === "Disagree" ||
      $policyConsent === "Disagree")?"Failure":"Success";

      $imageExists = File::exists($image_url);

      $facescore=0;

      $facescore = $imageExists ? $export->face_score : '-2';

    }else{
      $export->complete_status = "Not Completed";

      $finstatus = "Pending";
      
    }
       



   //if(isset($params)&&$params!=null){   dd($params);}
    return [
 
      $export->id,
      $export->proposal_no,
      // $export->url, 
      $export->short_link,
      
      isset($params['Proposer_name'])? $params['Proposer_name']: '',
      isset($params["Life_assured_dob"])? $params['Life_assured_dob']: '',
      //$params["personal_gender"],
      isset($params["Occupation"])? $params['Occupation']: '',
      isset($params["email"])? $params['email']: '',
      isset($params["Mobile_number"])? $params['Mobile_number']: '',
      $sys_lang,
      isset($params["Address"])? $params['Address']: '',
      isset($params['plan_details']["Plan_Name"])? $params['plan_details']['Plan_Name']: '',
      isset($params['plan_details']["Sum_Assured"])? $params['plan_details']['Sum_Assured']: '',
      //isset($params['plan_details']["Rider_Sum_Assured"])? $params['plan_details']['Rider_Sum_Assured']: '',
      isset($params['plan_details']["Premium_Amount"])? $params['plan_details']['Premium_Amount']: '',
      isset($params['plan_details']["Premium_Payment_Term"])? $params['plan_details']['Premium_Payment_Term']: '',
      isset($params['plan_details']["Frequency_Of_Premium_Payment"])? $params['plan_details']['Frequency_Of_Premium_Payment']: '',
      // $export->images,
      // $export->video,
      $export->status,
      $personalConsent,
      $policyConsent,
      //$medical_disagree,
      $export->is_open,
      $export->is_open_at,
      $export->curr_openstatus,
      $export->device,
      $export->network,
      $export->location,
      $export->complete_status,
      $export->completed_on,
      $export->created_at,
      $export->updated_at,
      $export->duration,
      $speechscore,
      $facescore,
      $finstatus,
      $feedbck,
      $feedbckscr,
      $export->link_attempt_count
    ];
   //}
  }

  public function headings(): array
  {
    return [

      "Id", 
      "Application No", 
      // "URL", 
      "Short Link", 
      "Personal Name",
      "Personal DOB",
      //"Personal Gender",
      "Personal Occupation",
      "Personal Email",
      "Personal Mobile",
      "Language",
      "Personal Address",
      "Policy Prod Name & Plan",
      "Policy Sum Assured",
      //"Policy Rider Name",
      //"Policy Rider Sum Assured",
      "Policy Premium Amount",
      "Policy Payment Type",
      "Policy Frequency",
      // "Images", 
      // "Video", 
      "Status", 
      "Personal Disagree", 
      "Policy Disagree", 
      //"Medical Disagree",
      "Is Open", 
      "Is Open At", 
      "Last Open At",
      "Device", 
      "Network", 
      "Location", 
      "Complete Status", 
      "Completed On", 
      "Created At", 
      "Updated At", 
      "Time Taken",
      "Speech Score",
      "Face Score",
      "Status",
      "Feedback",
      "Feedback Score",
      "Total Attempts"
    ];
  }
}
