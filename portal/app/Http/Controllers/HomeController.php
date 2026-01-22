<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\User;
use App\Models\UploadLink;
use App\Models\Linksarchive;
use App\Exports\LinksReportExport;
use App\Exports\TrailLinksReportExport;
use App\Exports\LogsReportExport;
use App\Http\Controllers\Api\ApiController; 
use App\Exports\HealthLinksReportExport;
use File;
use URL;
use Storage;
use Auth;
use Hash;
use Excel;
use App\Models\FailedProductLogs;
use App\Exports\FailedProductLinksReportExport;
use App\Exports\LogsreportdownloadReportExport;
use App\Models\Logs;
use App\Imports\YourImport;
use App\Exports\RetriggerResponseExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
//use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
      
      if (Auth::user() && ( Auth::user()->role=='User' )){
          //abort(401, 'This action is unauthorized.');
          return redirect('retrigger');
      }

      
        //$lists=Links::all('is_open', 'completed_status', 'created_at');
    $from = date('Y-m-d', strtotime('-30 days'));
    $to = date('Y-m-d', strtotime('now'));
    $sdate = date('Y-m-d', strtotime($from))." 00:00:00";
    $edate = date('Y-m-d', strtotime($to))." 23:59:59";
    $prmonth = [];
    $allAgree = 0;
    $allDisAgree = 0;
    $ag_per = "";
    $ag_pol = "";
    $ag_rid = "";
    $ag_tc1 = "";

    $prmonth['count'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->count();
    $prmonth['completed'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->where('complete_status', 1)->WhereRaw(" (personal_disagree = 0 AND  policy_disagree = 0 )")->count();
    $prmonth['completed_desc'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->where('complete_status', 1)->WhereRaw(" (personal_disagree = 1 OR  policy_disagree = 1 )")->count();
    $prmonth['notopen'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->where('is_open', 0)->count();

    $prmonth['incomplete'] = $prmonth['count']-( $prmonth['completed']+ $prmonth['completed_desc'] + $prmonth['notopen']);
    
    //$prmonth['agreed'] = Links::where(json_extract('user_agreement->choice','=','agreed'))->where('complete_status', 1)->toSql(); 
    return view('index', compact('prmonth'));
    }
    public function changepassword(Request $request)
    {
      
      $new_pass = Hash::make($request->new_pass);
      $userId = Auth::user()->id;
      $user = User::where('id',$userId)->first();
      $user->password = $new_pass;
      $user->save();
      if (Auth::user() && ( Auth::user()->role=='User' )){
       return redirect('retrigger')->with('success', 'Password Changed Successfully.');
      }else{
        return redirect('home')->with('success', 'Password Changed Successfully.');
      }
    }
    
    
  public function homeData(Request $request)
  {
    if (Auth::user() && ( Auth::user()->role=='User' )){
      abort(401, 'This action is unauthorized.');
  }
    $from = $request->from;
    $to = $request->to;
    $sdate = date('Y-m-d', strtotime($from))." 00:00:00";
    $edate = date('Y-m-d', strtotime($to))." 23:59:59";
    $prmonth = [];
    $allAgree = 0;
    $allDisAgree = 0;
    $ag_per = "";
    $ag_pol = "";
    $ag_rid = "";
    $ag_tc1 = "";

    $prmonth['count'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->count();
    $prmonth['completed'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->where('complete_status', 1) ->WhereRaw(" (personal_disagree = 0 or  policy_disagree = 0 )")->count();

    $prmonth['completed_desc'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->where('complete_status', 1)->WhereRaw(" (personal_disagree = 1 OR  policy_disagree = 1 )")->count();
    $prmonth['notopen'] = Link::where('created_at', '>=',  $sdate)->where('created_at', '<=',  $edate)->where('is_open', 0)->count(); 
    $prmonth['incomplete'] = $prmonth['count']-( $prmonth['completed']+ $prmonth['completed_desc'] + $prmonth['notopen']);
 
    
    return $prmonth;
  } 

  public function list(Request $request)
    {
      if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
    }

      $resetfilter     =   ($request->input('resetfilter')!='') ? $request->input('resetfilter') : 0;
      $pageNumber     =   ($request->input('pageNumber')!='') ? $request->input('pageNumber') : 1;
      $perPage        =   ($request->input('perpage')!='') ? $request->input('perpage') : 50;
      $orderName      =   ($request->input('orderName')!='') ? $request->input('orderName') : 'id'; 
      $orderBy        =   ($request->input('orderBy')!='') ? $request->input('orderBy') : 'desc';
      $from           =   ($request->input('from')!='') ?  date('Y-m-d',strtotime($request->input('from'))) : date('Y-m-d',strtotime('-30 days'));
      $to           =   ($request->input('to')!='') ? date('Y-m-d',strtotime($request->input('to'))) : date('Y-m-d');
      $filter        =   ($request->input('filter')!='') ? $request->input('filter') : '';
      $statuscheck        =   ($request->input('statuscheck')!='') ? $request->input('statuscheck') : '';
      
      if($resetfilter==0){
          $pageNumber     =   1;
          $perPage        =   50;
          $orderName      =   'id';
          $orderBy        =   'desc';
      }
      $_SkipValue     =   $perPage * ($pageNumber-1);
      $lists['pageNumber']  =   $pageNumber;
      $lists['perPage1']    =   $perPage;
      $lists['orderName']   =   $orderName;
      $lists['orderBy']     =   $orderBy;
      $lists['resetfilter'] =   $resetfilter;
      $lists['from'] =   $from;
      $lists['to'] =   $to;
      $lists['filter'] =   $filter;
      $lists['statuscheck'] =   $statuscheck;
      $paginstionRequest      =   "?perPage=$perPage&orderName=$orderName&orderBy=$orderBy&resetfilter=1&from=$from&&to=$to&filter=$filter&statuscheck=$statuscheck&";
        
      $_WhereCondition    =   array();
      if($from != '' && $to != ''){
          $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
          $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59');
      }
      if($statuscheck!=''){
        if($statuscheck==1){
          $_WhereCondition[]  =   array("complete_status",'!=',1);
        }
        if($statuscheck==2){
          $_WhereCondition[]  =   array("complete_status",'=',1);
          $_WhereCondition[]  =   array("personal_disagree",'=',0);
          $_WhereCondition[]  =   array("policy_disagree",'=',0);
        
        }
        if($statuscheck==3){
          $_WhereCondition[]  =   array("complete_status",'=',1); 
         // $_WhereCondition[]  =   array("personal_disagree",'<>','Agree');
        }
      }
      if($filter != '' ){
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("proposal_no",'=',$filter); 
      }
      //$lists=Link::all(); 
      if($statuscheck!=3){
      $lists['data'] = Link::where($_WhereCondition)
      ->orderBy($orderName, $orderBy)
      ->skip($_SkipValue)->take($perPage)->get();
      }else{
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
        $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59'); 
        $lists['data'] = Link::where('complete_status',1)
        ->where($_WhereCondition)
        ->WhereRaw(" (personal_disagree = 1 or  policy_disagree = 1  )")
        //->orWhere('policy_disagree','=','Disagree')
      ->orderBy($orderName, $orderBy)
      ->skip($_SkipValue)->take($perPage)->get();
      //echo  $lists['data'];die;
      }

      $linktrue = 'Y';
      //$Linksarchive=Linksarchive::all(); 
      //$lists = $lists->merge($Linksarchive); 
      if($statuscheck!=3){
        $count          =   Link::where($_WhereCondition)->count();
      }else{
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
        $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59'); 
        $count = Link::where('complete_status',1)
        ->where($_WhereCondition)
        ->WhereRaw(" (personal_disagree = 'Disagree' or  policy_disagree = 'Disagree'  or questions like '%DISAGREE%'  )")->count();
      }
      $href           =   URL::to('/list'.$paginstionRequest); 
      $pagination      =   $this->pagination($pageNumber,$perPage,$count,$href);
      
      return view('home',compact('lists','linktrue','pagination'));
    }

    public function list_old(Request $request)
    {
      if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
    }

      $resetfilter     =   ($request->input('resetfilter')!='') ? $request->input('resetfilter') : 0;
      $pageNumber     =   ($request->input('pageNumber')!='') ? $request->input('pageNumber') : 1;
      $perPage        =   ($request->input('perpage')!='') ? $request->input('perpage') : 50;
      $orderName      =   ($request->input('orderName')!='') ? $request->input('orderName') : 'id'; 
      $orderBy        =   ($request->input('orderBy')!='') ? $request->input('orderBy') : 'desc';
      $from           =   ($request->input('from')!='') ?  date('Y-m-d',strtotime($request->input('from'))) : date('Y-m-d',strtotime('-30 days'));
      $to           =   ($request->input('to')!='') ? date('Y-m-d',strtotime($request->input('to'))) : date('Y-m-d');
      $filter        =   ($request->input('filter')!='') ? $request->input('filter') : '';
      $statuscheck        =   ($request->input('statuscheck')!='') ? $request->input('statuscheck') : '';
      
      if($resetfilter==0){
          $pageNumber     =   1;
          $perPage        =   50;
          $orderName      =   'id';
          $orderBy        =   'desc';
      }
      $_SkipValue     =   $perPage * ($pageNumber-1);
      $lists['pageNumber']  =   $pageNumber;
      $lists['perPage1']    =   $perPage;
      $lists['orderName']   =   $orderName;
      $lists['orderBy']     =   $orderBy;
      $lists['resetfilter'] =   $resetfilter;
      $lists['from'] =   $from;
      $lists['to'] =   $to;
      $lists['filter'] =   $filter;
      $lists['statuscheck'] =   $statuscheck;
      $paginstionRequest      =   "?perPage=$perPage&orderName=$orderName&orderBy=$orderBy&resetfilter=1&from=$from&&to=$to&filter=$filter&statuscheck=$statuscheck&";
        
      $_WhereCondition    =   array();
      if($from != '' && $to != ''){
          $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
          $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59');
      }
      if($statuscheck!=''){
        if($statuscheck==1){
          $_WhereCondition[]  =   array("complete_status",'!=',1);
        }
        if($statuscheck==2){
          $_WhereCondition[]  =   array("complete_status",'=',1);
          $_WhereCondition[]  =   array("personal_disagree",'=',0);
          $_WhereCondition[]  =   array("policy_disagree",'=',0);
        
        }
        if($statuscheck==3){
          $_WhereCondition[]  =   array("complete_status",'=',1); 
        }
      }
      if($filter != '' ){
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("proposal_no",'=',$filter); 
      }
      //$lists=Link::all(); 
      if($statuscheck!=3){
      $lists['data'] = Link::where($_WhereCondition)
      ->orderBy($orderName, $orderBy)
      ->skip($_SkipValue)->take($perPage)->get();
      }else{
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
        $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59'); 
        $lists['data'] = Link::where('complete_status',1)
        ->where($_WhereCondition)
        ->WhereRaw(" (personal_disagree = 1 or  policy_disagree = 1)")
        //->orWhere('policy_disagree','=','Disagree')
      ->orderBy($orderName, $orderBy)
      ->skip($_SkipValue)->take($perPage)->get();
      //echo  $lists['data'];die;
      }

      $linktrue = 'Y';
      //$Linksarchive=Linksarchive::all(); 
      //$lists = $lists->merge($Linksarchive); 
      if($statuscheck!=3){
        $count          =   Link::where($_WhereCondition)->count();
      }else{
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
        $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59'); 
        $count = Link::where('complete_status',1)
        ->where($_WhereCondition)
        ->WhereRaw(" (personal_disagree = 'Disagree' or  policy_disagree = 'Disagree'  or questions like '%DISAGREE%'  )")->count();
      }
      $href           =   URL::to('/list'.$paginstionRequest); 
      $pagination      =   $this->pagination($pageNumber,$perPage,$count,$href);
      
      return view('home',compact('lists','linktrue','pagination'));
    }

    public function pagination($pageNumber,$perPage,$count, $href) {
      $output = '';
      if(!isset($pageNumber)) $pageNumber = 1;
      if($perPage != 0)
      $pages  = ceil($count/$perPage); 
      if($pages>1) {
          if(($pageNumber-3)>0) {
              $output = $output . '<li class="page-item"><a class="page-link" href="' . $href . 'pageNumber=1" >1</a></li>';
          }
          if(($pageNumber-3)>1) {
              $output = $output . '<li class="page-item"><span class="page-link">...</span></li>';
          }
          
          for($i=($pageNumber-2); $i<=($pageNumber+2); $i++)	{
              if($i<1) continue;
              if($i>$pages) break;
              if($pageNumber == $i)
                  $output = $output . ' <li class="page-item active"><span id='.$i.' class="page-link current">'.$i.'</span></li>';
              else				
                  $output = $output . ' <li class="page-item"><a href="' . $href . "pageNumber=".$i . '" class="page-link"">'.$i.'</a></li>';
          }
          if(($pages-($pageNumber+2))>1) {
              $output = $output . '<li class="page-item"><span class="page-link">...</span></li>';
          }
          if(($pages-($pageNumber+2))>0) {
              if($pageNumber == $pages)
                  $output = $output . ' <li class="page-item"><span id=' . ($pages) .' class="current">' . ($pages) .'</span></li>';
              else				
                  $output = $output . ' <li class="page-item"><a class="page-link"  href="' . $href .  "pageNumber=" .($pages) .'"  >' . ($pages) .'</a></li>';
          }
          
      }
      return $output;
    }

    public function resend()
	{
    if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
    }
		$app_no='';
		return view('resend',compact('app_no'));		
	}
  

  public function postresend(Request $request)
  {
    $success = '';
    $error = '';
    $successcount = 0;
    
    $apiCtrl = new ApiController();
    $policy_no  = (isset($request->policy_no)) ? json_decode($request->policy_no,true)  : '';
    if(!empty($policy_no)){
      foreach($policy_no as $policy_noSingle){
       $trans =  Link::where('proposal_no',$policy_noSingle)->first();
       //dd($trans);
      // {"proposal_no":"Z2300503507","personal_name":"Shivam Sharma","personal_dob":"18\/06\/2000","personal_gender":"Male","personal_occupation":"Salaried","personal_email":"shivam.sharma1@pramericalife.in","personal_mobile":"9759311075","personal_address":". Aurangabad Taharpur  Bulandshahr Siana","policy_prod_name":"RockSolid Future","policy_plan":"Income Builder Option","policy_sum_assured":"3,30,000","policy_rider_name":null,"policy_rider_sum_assured":null,"policy_preimum_amount":"30,000","policy_payment_type":"Online","policy_Frequency":"Annual","policy_term":"7","premium_payment_term":"5","product_code":"T52A"}
       if(!empty($trans)){
        $token =  $apiCtrl->getOauthTokenGeneric();
        $token = json_decode($token,true); 
         if(isset($token['data']['accessToken'])){
           $resendcheck = HomeController::resendLink($policy_noSingle,$trans->short_link,$token['data']['accessToken']);
           $resendcheck = json_decode($resendcheck);
           if($resendcheck->status==200){
 
            $params = json_decode($trans->params,true);
            $to   = $params['personal_mobile'];
            $var1 = $params['policy_prod_name'].'-'.$params['policy_plan'];
            $var2 = $trans->short_link;
            $message = "Dear customer, you have successfully applied for $var1. To complete your verification please click on $var2. In case of any error, please copy paste the link directly to your web browser. Pramerica Life Insurance Limited.";
            $apiCtrl->sendSMSPramerica($to,$message);
            $trans->resendcount = $trans->resendcount+1;
            $trans->save();
            $successcount++;
           }
          // dd($resendcheck);
         }
       }
       
       
      }
    }else{
      $error ="Invalid Proposal Number You Entered";
    }
    if($successcount>0){
      $success ="Resend Successfully";
    } 
    
      $app_no='';
      
  return view('resend',compact('app_no','success','error'));	
  
  }

  
  public function resendLink($proposal,$link,$token)
  {   
    if (Auth::user() && ( Auth::user()->role=='User' )){
      abort(401, 'This action is unauthorized.');
  }
      $curl = curl_init(); 
 

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://speedbiz2login.pramericalife.in/pivc/resendLink',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
       "appNo": "'.$proposal.'",
       "link": "'.$link.'"
      }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$token.'',
          'Content-Type: application/json'
        ),
      ));
      
      $response = curl_exec($curl);
      //dd($response);
      curl_close($curl);
      return $response; 

  }

    public function retrigger(Request $request)
    {
     
		return view('retrigger');	
    
    }

    public function retriggerall(Request $request)
    {
     
		return view('retriggerall');	
    
    }

     public function bulkupload(Request $request)
    {
     
		return view('bulkupload');	
    
    }

  
    // public function traillinks(Request $request)
    // {
    //     $app_no='';
	// 	return view('traillinks',compact('app_no'));	
    // }


    public function trailLinks(Request $request)
    {
      if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
    }
      $resetfilter     =   ($request->input('resetfilter')!='') ? $request->input('resetfilter') : 0;
      $pageNumber     =   ($request->input('pageNumber')!='') ? $request->input('pageNumber') : 1;
      $perPage        =   ($request->input('perpage')!='') ? $request->input('perpage') : 50;
      $orderName      =   ($request->input('orderName')!='') ? $request->input('orderName') : 'id';
      $orderBy        =   ($request->input('orderBy')!='') ? $request->input('orderBy') : 'asc';
      $from           =   ($request->input('from')!='') ?  date('Y-m-d',strtotime($request->input('from'))) : date('Y-m-d',strtotime('-30 days'));
      $to           =   ($request->input('to')!='') ? date('Y-m-d',strtotime($request->input('to'))) : date('Y-m-d');
      $filter        =   ($request->input('filter')!='') ? $request->input('filter') : '';
      if($resetfilter==0){
          $pageNumber     =   1;
          $perPage        =   50;
          $orderName      =   'id';
          $orderBy        =   'desc';
      }
      $_SkipValue     =   $perPage * ($pageNumber-1);
      $lists['pageNumber']  =   $pageNumber;
      $lists['perPage1']    =   $perPage;
      $lists['orderName']   =   $orderName;
      $lists['orderBy']     =   $orderBy;
      $lists['resetfilter'] =   $resetfilter;
      $lists['from'] =   $from;
      $lists['to'] =   $to;
      $lists['filter'] =   $filter;

      $paginstionRequest      =   "?perPage=$perPage&orderName=$orderName&orderBy=$orderBy&from=$from&&to=$to&filter=$filter&";
        
      $_WhereCondition    =   array();
      if($from != '' && $to != ''){
          $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
          $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59');
      }
      if($filter != '' ){
        $_WhereCondition    =   array();
        $_WhereCondition[]  =   array("proposal_no",'=',$filter); 
      } 
      $lists['data'] = Linksarchive::where($_WhereCondition)
      ->orderBy($orderName, $orderBy)
      ->skip($_SkipValue)->take($perPage)->get();

      $linktrue = 'Y'; 
      $count          =   Linksarchive::where($_WhereCondition)->count();
      $href           =   URL::to('/list'.$paginstionRequest); 
      $pagination      =   $this->pagination($pageNumber,$perPage,$count,$href);

    //   print_r( $lists['data']);  die;
      
     // return view('home',compact('lists','linktrue','pagination'));
      return view('trail',compact('lists','linktrue','pagination'));

    }

    // EXPORT EXCEL
    public function linksreportsdownloadexcel(Request $request)
    {
      if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
    }
      // dd($request->all());
      $min =  $request->input('from');
      $max =  $request->input('to');
      $statuscheck =  $request->input('statuscheck');
     // $app_no = (($request->input('proposal_no'))) ? $request->input('proposal_no') : '';
      $complete_status ='';
      if($request->input('complete_status') !='' ){
        $complete_status =  $request->input('complete_status');
      } 
      $moth_Return =	date('mY',strtotime($min));
 
      return (new LinksReportExport($min,$max,$statuscheck))->download('Sudlife_AllLinks-'.$moth_Return.'.xlsx');
    }

      // EXPORT EXCEL
      public function traillinksreportsdownloadexcel(Request $request)
      {
        if (Auth::user() && ( Auth::user()->role=='User' )){
          abort(401, 'This action is unauthorized.');
      }
        // dd($request->all());
        $min =  $request->input('from');
        $max =  $request->input('to');
       // $app_no = (($request->input('proposal_no'))) ? $request->input('proposal_no') : '';
        $complete_status ='';
        if($request->input('complete_status') !='' ){
          $complete_status =  $request->input('complete_status');
        } 
        $moth_Return =	date('mY',strtotime($min));
   
        return (new TrailLinksReportExport($min,$max))->download('Sudlife_AllTrailLinks-'.$moth_Return.'.xlsx');
      }


      public function healthvitallist(Request $request)
      {
        if (Auth::user() && ( Auth::user()->role=='User' )){
          abort(401, 'This action is unauthorized.');
      }
  
        $resetfilter     =   ($request->input('resetfilter')!='') ? $request->input('resetfilter') : 0;
        $pageNumber     =   ($request->input('pageNumber')!='') ? $request->input('pageNumber') : 1;
        $perPage        =   ($request->input('perpage')!='') ? $request->input('perpage') : 50;
        $orderName      =   ($request->input('orderName')!='') ? $request->input('orderName') : 'id'; 
        $orderBy        =   ($request->input('orderBy')!='') ? $request->input('orderBy') : 'desc';
        $from           =   ($request->input('from')!='') ?  date('Y-m-d',strtotime($request->input('from'))) : date('Y-m-d',strtotime('-30 days'));
        $to           =   ($request->input('to')!='') ? date('Y-m-d',strtotime($request->input('to'))) : date('Y-m-d');
        $filter        =   ($request->input('filter')!='') ? $request->input('filter') : '';
        $statuscheck        =   ($request->input('statuscheck')!='') ? $request->input('statuscheck') : '';
        
        if($resetfilter==0){
            $pageNumber     =   1;
            $perPage        =   50;
            $orderName      =   'id';
            $orderBy        =   'desc';
        }
        $_SkipValue     =   $perPage * ($pageNumber-1);
        $lists['pageNumber']  =   $pageNumber;
        $lists['perPage1']    =   $perPage;
        $lists['orderName']   =   $orderName;
        $lists['orderBy']     =   $orderBy;
        $lists['resetfilter'] =   $resetfilter;
        $lists['from'] =   $from;
        $lists['to'] =   $to;
        $lists['filter'] =   $filter;
        $lists['statuscheck'] =   $statuscheck;
        $paginstionRequest      =   "?perPage=$perPage&orderName=$orderName&orderBy=$orderBy&resetfilter=1&from=$from&&to=$to&filter=$filter&statuscheck=$statuscheck&";
          
        $_WhereCondition    =   array();
        if($from != '' && $to != ''){
            $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
            $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59');
        }
        if($statuscheck!=''){
          if($statuscheck==1){
            $_WhereCondition[]  =   array("complete_status",'!=',1);
          }
          if($statuscheck==2){
            $_WhereCondition[]  =   array("complete_status",'=',1);
            $_WhereCondition[]  =   array("personal_disagree",'=','Agree');
            $_WhereCondition[]  =   array("policy_disagree",'=','Agree');
            
          }
          if($statuscheck==3){
            $_WhereCondition[]  =   array("complete_status",'=',1); 
           // $_WhereCondition[]  =   array("personal_disagree",'<>','Agree');
          }
        }
        if($filter != '' ){
          $_WhereCondition    =   array();
          $_WhereCondition[]  =   array("proposal_no",'=',$filter); 
        }
        //$lists=Link::all(); 
        if($statuscheck!=3){
        $lists['data'] = Link::where($_WhereCondition)
        ->orderBy($orderName, $orderBy)
        ->skip($_SkipValue)->take($perPage)->get();
        }else{
          $_WhereCondition    =   array();
          $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
          $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59'); 
          $lists['data'] = Link::where('complete_status',1)
          ->where($_WhereCondition)
          ->WhereRaw(" (personal_disagree = 'Disagree' or  policy_disagree = 'Disagree' or questions like '%DISAGREE%' )")
          //->orWhere('policy_disagree','=','Disagree')
        ->orderBy($orderName, $orderBy)
        ->skip($_SkipValue)->take($perPage)->get();
        //echo  $lists['data'];die;
        }
  
      
        foreach ($lists['data'] as $list) {
         if (!empty($list->fedo_vitals)) {
            $list->healthvitals = 'Yes';
          } else {
             $list->healthvitals = 'No';
             }
       }
  
  
        $linktrue = 'Y';
        //$Linksarchive=Linksarchive::all(); 
        //$lists = $lists->merge($Linksarchive); 
        if($statuscheck!=3){
          $count          =   Link::where($_WhereCondition)->count();
        }else{
          $_WhereCondition    =   array();
          $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
          $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59'); 
          $count = Link::where('complete_status',1)
          ->where($_WhereCondition)
          ->WhereRaw(" (personal_disagree = 'Disagree' or  policy_disagree = 'Disagree'  or questions like '%DISAGREE%' )")->count();
        }
        $href           =   URL::to('/list'.$paginstionRequest); 
        $pagination      =   $this->pagination($pageNumber,$perPage,$count,$href);
       
        
        return view('healthvitallist',compact('lists','linktrue','pagination'));
      }
  
  
      public function healthlinksreportsdownloadexcel(Request $request)
      {
        if (Auth::user() && ( Auth::user()->role=='User' )){
          abort(401, 'This action is unauthorized.');
      }
        // dd($request->all());
        $min =  $request->input('from');
        $max =  $request->input('to');
        $statuscheck =  $request->input('statuscheck');
       // $app_no = (($request->input('proposal_no'))) ? $request->input('proposal_no') : '';
        $complete_status ='';
        if($request->input('complete_status') !='' ){
          $complete_status =  $request->input('complete_status');
        } 
        $moth_Return =	date('mY',strtotime($min));
   
        return (new HealthLinksReportExport($min,$max,$statuscheck))->download('Pramerica_HV_AllLinks-'.$moth_Return.'.xlsx');
      }
    
      public function failedProductLinks(Request $request)
      {
        if (Auth::user() && ( Auth::user()->role=='User' )){
          abort(401, 'This action is unauthorized.');
      }
  
        $resetfilter     =   ($request->input('resetfilter')!='') ? $request->input('resetfilter') : 0;
        $pageNumber     =   ($request->input('pageNumber')!='') ? $request->input('pageNumber') : 1;
        $perPage        =   ($request->input('perpage')!='') ? $request->input('perpage') : 50;
        $orderName      =   ($request->input('orderName')!='') ? $request->input('orderName') : 'id'; 
        $orderBy        =   ($request->input('orderBy')!='') ? $request->input('orderBy') : 'desc';
        $from           =   ($request->input('from')!='') ?  date('Y-m-d',strtotime($request->input('from'))) : date('Y-m-d',strtotime('-30 days'));
        $to           =   ($request->input('to')!='') ? date('Y-m-d',strtotime($request->input('to'))) : date('Y-m-d');
        $filter        =   ($request->input('filter')!='') ? $request->input('filter') : '';
        $statuscheck        =   ($request->input('statuscheck')!='') ? $request->input('statuscheck') : '';
        
        if($resetfilter==0){
            $pageNumber     =   1;
            $perPage        =   50;
            $orderName      =   'id';
            $orderBy        =   'desc';
        }
        $_SkipValue     =   $perPage * ($pageNumber-1);
        $lists['pageNumber']  =   $pageNumber;
        $lists['perPage1']    =   $perPage;
        $lists['orderName']   =   $orderName;
        $lists['orderBy']     =   $orderBy;
        $lists['resetfilter'] =   $resetfilter;
        $lists['from'] =   $from;
        $lists['to'] =   $to;
        $lists['filter'] =   $filter;
        $lists['statuscheck'] =   $statuscheck;
        $paginstionRequest      =   "?perPage=$perPage&orderName=$orderName&orderBy=$orderBy&resetfilter=1&from=$from&&to=$to&filter=$filter&statuscheck=$statuscheck&";
          
        $_WhereCondition    =   array();
        if($from != '' && $to != ''){
            $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
            $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59');
        }
        //$lists=Link::all(); 
        $lists['data'] = FailedProductLogs::where($_WhereCondition)
        ->orderBy($orderName, $orderBy)
        ->skip($_SkipValue)->take($perPage)->get();
       
  
        $linktrue = 'Y';
        $count          =   FailedProductLogs::where($_WhereCondition)->count();
        
        $href           =   URL::to('/failedProductLinks'.$paginstionRequest); 
        $pagination      =   $this->pagination($pageNumber,$perPage,$count,$href);
        
        return view('failedProductLinks',compact('lists','linktrue','pagination'));
      }

      public function logslink(Request $request)
    {
      if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
      }

      $resetfilter     =   ($request->input('resetfilter')!='') ? $request->input('resetfilter') : 0;
      $pageNumber     =   ($request->input('pageNumber')!='') ? $request->input('pageNumber') : 1;
      $perPage        =   ($request->input('perpage')!='') ? $request->input('perpage') : 50;
      $orderName      =   ($request->input('orderName')!='') ? $request->input('orderName') : 'id'; 
      $orderBy        =   ($request->input('orderBy')!='') ? $request->input('orderBy') : 'desc';
      $from           =   ($request->input('from')!='') ?  date('Y-m-d',strtotime($request->input('from'))) : date('Y-m-d',strtotime('-30 days'));
      $to           =   ($request->input('to')!='') ? date('Y-m-d',strtotime($request->input('to'))) : date('Y-m-d');
      $filter        =   ($request->input('filter')!='') ? $request->input('filter') : '';
      $statuscheck        =   ($request->input('statuscheck')!='') ? $request->input('statuscheck') : '';
      
      if($resetfilter==0){
          $pageNumber     =   1;
          $perPage        =   50;
          $orderName      =   'id';
          $orderBy        =   'desc';
      }
      $_SkipValue     =   $perPage * ($pageNumber-1);
      $lists['pageNumber']  =   $pageNumber;
      $lists['perPage1']    =   $perPage;
      $lists['orderName']   =   $orderName;
      $lists['orderBy']     =   $orderBy;
      $lists['resetfilter'] =   $resetfilter;
      $lists['from'] =   $from;
      $lists['to'] =   $to;
      $lists['filter'] =   $filter;
      $lists['statuscheck'] =   $statuscheck;
      $paginstionRequest      =   "?perPage=$perPage&orderName=$orderName&orderBy=$orderBy&resetfilter=1&from=$from&&to=$to&filter=$filter&statuscheck=$statuscheck&";
        
      $_WhereCondition    =   array();
      if($from != '' && $to != ''){
          $_WhereCondition[]  =   array("created_at",'>=',$from.' 00:00:00');
          $_WhereCondition[]  =   array("created_at",'<=',$to.' 23:59:59');
      }
      //$lists=Link::all(); 
      // $lists['data'] = Logs::where($_WhereCondition)
      // ->orderBy($orderName, $orderBy)
      // ->skip($_SkipValue)->take($perPage)->get();
      $subQuery = Logs::selectRaw('MAX(id) as max_id')
                        ->where($_WhereCondition)
                        ->groupBy('app_no');
  
      $lists['data'] = Logs::whereIn('id', $subQuery)
                        ->orderBy($orderName, $orderBy)
                        ->skip($_SkipValue)
                        ->take($perPage)
                        ->get(); 
  
      $linktrue = 'Y';
      $count          =     Logs::whereIn('id', $subQuery)
      ->orderBy($orderName, $orderBy) 
      ->count();
      
      $href           =   URL::to('/logslink'.$paginstionRequest); 
      $pagination      =   $this->pagination($pageNumber,$perPage,$count,$href);
      
      return view('logslink',compact('lists','linktrue','pagination'));
    }
      public function failedproductlinksreportdownloadexcel(Request $request)
      {
        if (Auth::user() && ( Auth::user()->role=='User' )){
          abort(401, 'This action is unauthorized.');
        }
        // dd($request->all());
        $min =  $request->input('from');
        $max =  $request->input('to');
        $app_no = (($request->input('proposal_no'))) ? $request->input('proposal_no') : '';
        $moth_Return =	date('mY',strtotime($min));
   
        return (new FailedProductLinksReportExport($min,$max))->download('Pramerica_FailedProductLinks-'.$moth_Return.'.xlsx');
      }
      
      public function logs()
      {
          if (Auth::user() && (Auth::user()->role == 'User')) {
          abort(401, 'This action is unauthorized.');
        }
        return view('logs');
      }


      public function logsPost(Request $request)
      { 
        if (Auth::user() && (Auth::user()->role == 'User')) {
        abort(401, 'This action is unauthorized.');
        }

        $application_no = $request->input('application_no');
        $logs = Logs::where('app_no', $application_no)->orderBy('id', 'desc')->get();
        return view('logs', compact('logs', 'application_no'));
      }
  public function logsDetails(Request $request)
  { 
    if (Auth::user() && (Auth::user()->role == 'User')) {
    abort(401, 'This action is unauthorized.');
    }
    // $link_attempt_count = $request->input('link_attempt_count');
    // $links = Link::where('link_attempt_count', $link_attempt_count)->orderBy('id', 'desc')->get();
    // return view('logs', compact('logs', 'link_attempt_count'));
    $application_no = $request->input('application_no');
    $logs = Logs::where('app_no', $application_no)->orderBy('id', 'desc')->get();
    $links = Link::where('proposal_no',$application_no)->first();
    $no_of_attempts = '';
    if(!empty($links)){
      $no_of_attempts = $links->link_attempt_count;
    }
    return view('logs', compact('logs', 'application_no','no_of_attempts'));
  }
  public function logsreportdownloadexcel(Request $request)
  {
    if (Auth::user() && ( Auth::user()->role=='User' )){
      abort(401, 'This action is unauthorized.');
    }
    // dd($request->all());
    $min =  $request->input('from');
    $max =  $request->input('to');
    $moth_Return =	date('mY',strtotime($min));
    return (new LogsreportdownloadReportExport($min,$max))->download('Pramerica_Logs'.$moth_Return.'.xlsx');
  }

  public function logsreportdownload( $application_no)
  {

    if (Auth::user() && ( Auth::user()->role=='User' )){
      abort(401, 'This action is unauthorized.');
    }
    return (new LogsReportExport($application_no))->download('Pramerica_Logs-'.$application_no.'.xlsx');
  }

  public function uploadExcel(Request $request)
    {
              $request->validate([
              'file' => 'required|mimes:xlsx,xls,csv'
          ]);

          $file = $request->file('file');

          $import = new YourImport;
          Excel::import($import, $file);

          $rows = $import->getData();
          $output = [];

          // Optional: Add a header row
          $output[] = ['Application No', 'Short Link'];

          foreach ($rows as $row) {
              $appno = $row[0] ?? null;

              if ($appno) {
                  $resp = $this->retriggerbyappno($appno);
                  $respdecode = json_decode($resp, true);
                  //$respSummary = json_encode($respdecode); // or extract only needed fields
              } else {
                  $respSummary = 'Invalid Application No';
              }

              //print_r($respdecode['link']);

              $output[] = [$appno, $respdecode['link']];
          }

          return Excel::download(new RetriggerResponseExport($output), 'retrigger_responses.xlsx');
    }

    public function retriggerbyappno( $proposal_no)
    {

          if( $proposal_no){ 

          //echo $proposal_no;die;
          // print_r($request->proposal_no); die;
          //dd($details);
          $lists = Link::where('proposal_no', $proposal_no)->get();
          // print_r($lists); die;
          if ($lists->isNotEmpty()) {
            // echo "test"; die;
            //$listarchive=new Linksarchive; 
            $listarchive = $lists[0]->replicate(); 

            $created_at = $lists[0]['created_at'];
            $updated_at = $lists[0]['updated_at'];

            $listarchive->version = Linksarchive::where('proposal_no', $proposal_no)->count() + 1;

            $listarchive->created_at = $created_at;
            $listarchive->updated_at = $updated_at;

            //dd($listarchive);
            $listarchive->setTable('links_archive');
            $listarchive->save();
            $path = public_path('upload/' . $proposal_no . '-' . $listarchive->version);
            $spath = public_path('upload/' . $proposal_no);
            if (!File::isDirectory($path)) {
              File::makeDirectory($path, 0777, true, true);
            }
            File::copyDirectory($spath, $path);

            $lists = $lists[0];
            $lists->complete_status = 0;
            $lists->device = NULL;
            $lists->personal_disagree = NULL;
            $lists->policy_disagree = NULL;
            $lists->completed_on = NULL;
            $lists->is_open = 0;
            $lists->is_open_at = NULL;


            // $lists->disagree_response = NULL;
            $lists->save();

            $trans = Link::where('proposal_no', $proposal_no)->first();

             $appno = $trans->proposal_no;
            //$params = json_decode($trans->params, true);

            //$to   = $params['personal_mobile'];
            //$var1 = $params['policy_prod_name'] . '-' . $params['policy_plan'];
            $var2 = $trans->short_link;//dd($var2);
            //$message = "Dear customer, you have successfully applied for $var1 . To complete your verification please click on $var2 . In case of any error, please copy paste the link directly to your web browser. Pramerica Life Insurance Limited.";
            //$response = $this->sendSMSPramerica($to, $message);
            //  dd($response);
            // return $arr = ["status" => "true", "msg" => "reactivated"];

            //return response()->json(['status' => true, 'link' => $var2], 200);
            $jsonResult = json_encode(['link' => $var2, 'Application_Number' => $appno], 200);
            return $jsonResult;
            //return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
          } else {
            //return $arr = ["status" => "false"];
            //return response()->json(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
            $jsonResult = json_encode(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
             return $jsonResult;
            //return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
          }
        }
        else{
          $jsonResult = json_encode(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
           return $jsonResult;
            //return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
          //return response()->json(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
        }

    }

    public function bulkuploadExcel(Request $request)
    {
        //dd('ddf');


       $apiCtrl = new ApiController();
       //$request = new Request();
              $request->validate([
              'file' => 'required|mimes:xlsx,xls'
          ]);
 
           $file = $request->file('file');

          $spreadsheet = IOFactory::load($file);
          $sheet = $spreadsheet->getActiveSheet();
          $rows = $sheet->toArray(null, true, true, true);

          $header = array_shift($rows); // remove header row
          $jsonData = [];

          foreach ($rows as $row) { 

          $proposal_no = $row['A'];  
            
          $lists = Link::where('proposal_no', $proposal_no)->first();  
          if (empty($lists)) {//dd('1'); //createlink starts
            //dd($row['A']);
              // Build nested nominee data manually from fixed column positions

              $Nominee_details = [];
              $plan_details = [];
              $Rider_details = [];

              if (!empty($row['E']) || !empty($row['F'])) {
                  $Nominee_details[] = [
                      'Nominee_name' => $row['E'] ?? null,
                      'Nominee_dob'  => $row['F'] ?? null,
                  ];
              }

              if (!empty($row['G']) || !empty($row['H'])) {
                  $Nominee_details[] = [
                      'Nominee_name' => $row['G'] ?? null,
                      'Nominee_dob'  => $row['H'] ?? null,
                  ];
              }

              if (!empty($row['S']) || !empty($row['T'])) {
                  $Rider_details[] = [
                      'Rider_name' => $row['S'] ?? null,
                      'Rider_Sum_Assured'  => $row['T'] ?? null,
                  ];
              }

              if (!empty($row['U']) || !empty($row['V'])) {
                  $Rider_details[] = [
                      'Rider_name' => $row['U'] ?? null,
                      'Rider_Sum_Assured'  => $row['V'] ?? null,
                  ];
              }

             // $image_base64 = HomeController::getimagebase64($proposal_no, $row['D'], $expectedName, $expectedCode);

              
             //dd($image_base64);
              // Main record structure
              $data = [
                  'Application_Number'   => $row['A'] ?? null,
                  'Proposer_name' => $row['B'] ?? null,
                  'Life_assured_name' => $row['C'] ?? null,
                  'Life_assured_dob'         => $row['D'] ?? null,
                  'Nominee_details'    => $Nominee_details,
                  'Mobile_number'      => $row['I'] ?? null,
                  'Email_id'       => $row['J'] ?? null,
                  'Address'     => $row['K'] ?? null,
                  'Occupation'   => $row['L'] ?? null,
                  'plan_details' =>[
                  'Plan_Name'   => $row['M'] ?? null,
                  'Sum_Assured' => $row['N'] ?? null,
                  'Policy_Term'        => $row['O'] ?? null,
                  'Frequency_Of_Premium_Payment'        => $row['P'] ?? null,
                  'Premium_Amount'     => $row['Q'] ?? null,
                  'Premium_Payment_Term'=> $row['R'] ?? null,
                  'Rider_details'    => $Rider_details,
                  'Medical_Flag'=> $row['T'] ?? null,
                  // 'image_base64'      => $image_base64,
                  ]
              ];

              $jsonData = json_encode($data); //dd($jsonData);
              $upload_link = new UploadLink();
              $upload_link->app_no = $row['A'] ?? null;
              $upload_link->request = $jsonData;
              $upload_link->create_status = 0;
              $upload_link->save();
              
              //print_r($jsonData);die;
              /*$request = Request::create('/dummy-url', 'POST', [], [], [], [
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
              //print_r($createreq);die;
              }*/
              
              }
              // else{           //Retrigger starts
              //   $listapp = $lists->toArray();
              //   //print_r($listapp);die;
              //   $appno = $listapp[0]['proposal_no'];//dd($appno);
              //         if ($appno) {
              //           $resp = $this->retriggerbyappno($appno);
              //           $respdecode = json_decode($resp, true);
              //           //$respSummary = json_encode($respdecode); // or extract only needed fields
              //       } else {
              //           $respSummary = 'Invalid Application No';
              //       }

              //       //print_r($respdecode['link']);

              //       $output[] = [$appno, $respdecode['link']];

              //       return Excel::download(new RetriggerResponseExport($output), 'retrigger_responses.xlsx');
              // }
          }

          $success="uploaded Successfully";
              return view('bulkupload',compact('success'));	
          //return response()->json($jsonData);
        

    }

    public function getimagebase64_old($proposal_no, $dob, $name, $code)
    {
      $apiCtrl = new ApiController();

                 $getDoclist = $apiCtrl->getdocumentlistApi($proposal_no, $dob, $name, $code);
                  
                 if($getDoclist){

                     $decodeDoclist = json_decode($getDoclist, true); //dd($decodeDoclist);

                     if($decodeDoclist['Status']=="Success"){

                       $targetName = "Identity_Proof_LA.tif";

                      foreach ($decodeDoclist['DocumentList']['Documents'] as $doc) {
                          $parts = explode('|', $doc);
                          $docname = $parts[0];

                          //dd($name, $targetName);

                          if ($name === $targetName) { 
                              // Extract document index
                              $documentIndexPart = explode(':', $parts[1]); // DocumentIndex:32711254
                              $documentIndex = $documentIndexPart[1] ?? null; //dd($documentIndexPart);

                              $getDocdownload = $apiCtrl->getdownloaddocumentApi($proposal_no, $dob,  $name, $code, $docname, $documentIndex);

                                //dd($getDocdownload);

                              if($getDocdownload){

                                    $decodeDocdownload = json_decode($getDocdownload, true);

                                    //$getHashval = $decodeDoclist['hash'];
                                    if($decodeDocdownload['Status']=="Success"){
                                        $image_base64 = $decodeDocdownload['DocDownload']['DocumentBase64'];
                                    }
                                    else{
                                      $apiCtrl->logs($proposal_no,'bulklinkgen', '', json_encode($decodeDocdownload['Status']), "success");
                                      $image_base64 = null;
                                    }
                                  }
                         }else{
                          $image_base64 = null;
                         }
                      }
                       
                     }else if($decodeDoclist['Status']=="Failure"){//dd('sds');

                      $apiCtrl->logs($proposal_no,'bulklinkgen', '', json_encode($decodeDoclist['Status']), "success");

                       $image_base64 = null;
                     }


                  }else{

                    //ApiController::logs($proposal_no,'bulklinkgen', '', $getDoclist, "success");

                    $image_base64 = null;
                  }
      return $image_base64;
    }

        public function getimagebase64($proposal_no, $dob, $name, $code)
    {
        $apiCtrl = new ApiController();
        $image_base64 = null;
    
        $getDoclist = $apiCtrl->getdocumentlistApi($proposal_no, $dob, $name, $code);
    
        if (!$getDoclist) {
            // If API response is empty or failed
            return null;
        }
    
        $decodeDoclist = json_decode($getDoclist, true);
    
        if ($decodeDoclist['Status'] === "Success") {
    
          $targetBaseName = "Identity_Proof_LA"; // just base name without extension

          foreach ($decodeDoclist['DocumentList']['Documents'] as $doc) {
              $parts = explode('|', $doc);
              $docname = $parts[0];
          
              // compare only base filename (remove extension)
              $docBaseName = pathinfo($docname, PATHINFO_FILENAME);
          
              if ($docBaseName === $targetBaseName) {
                  // Extract Document Index
                  $documentIndexPart = explode(':', $parts[1] ?? '');
                  $documentIndex = $documentIndexPart[1] ?? null;
          
                  // Extract Image Index
                  $imageIndex = null;
                  if (isset($parts[2])) {
                      preg_match('/ImageIndex:(\d+)/', $parts[2], $matches);
                      $imageIndex = $matches[1] ?? null;
                  }
    
                  $dob="";
                  //dd($proposal_no, $dob, $name, $code, $docname, $imageIndex);
                    if ($documentIndex && $imageIndex) {
                        $getDocdownload = $apiCtrl->getdownloaddocumentApi($proposal_no, $dob, $name, $code, $docname, $imageIndex);
    
                        if ($getDocdownload) {
                            $decodeDocdownload = json_decode($getDocdownload, true);

                           //dd($decodeDocdownload);
    
                            if ($decodeDocdownload['Status'] === "Success") {
                                return $decodeDocdownload['DocDownload']['DocumentBase64'];
                            } elseif ($decodeDocdownload['Status'] === "Failure") {///dd('dd');
                                $apiCtrl->logs($proposal_no, 'bulklinkgen', '', json_encode($decodeDocdownload['Status']), "failure");
                                ///return false;
                            }
                        }
                    }
                }
            }
    
        } elseif ($decodeDoclist['Status'] === "Failure") {
          $apiCtrl->logs($proposal_no, 'bulklinkgen', '', json_encode($decodeDoclist['Status']), "success");
        }
    
        return null;
    }

    public function bulkdownloadexcel(Request $request)
    {

     // dd('rftyrt');

      if (Auth::user() && ( Auth::user()->role=='User' )){
        abort(401, 'This action is unauthorized.');
    }
      // dd($request->all());
      $min =  $request->input('from');
      $max =  $request->input('to');
      $statuscheck =  $request->input('statuscheck');
     // $app_no = (($request->input('proposal_no'))) ? $request->input('proposal_no') : '';
      $complete_status ='';
      if($request->input('complete_status') !='' ){
        $complete_status =  $request->input('complete_status');
      } 
      $moth_Return =	date('mY',strtotime($min));
 
      return (new LinksReportExport($min,$max,$statuscheck))->download('Sudlife_AllLinks-'.$moth_Return.'.xlsx');
    }
}
