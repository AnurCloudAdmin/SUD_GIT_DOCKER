<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <title>Video Pre-Issuance Verification - Transcript </title>
      <meta name="author" content="Mangesh Warise"/>
      <style type="text/css"> * {margin:0; padding:0; text-indent:0; }
         .s1 { color: #2B2A29; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt; }
         .s2 { color: #2B2A29; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 9pt; }
         p { color: #2B2A29; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 9pt; margin:0pt; }
         .s3 { color: #2B2A29; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 9pt; }
         .h1 { color: #00A0E3; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: underline; font-size: 9pt; }
         .s4 { color: #2B2A29; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 9pt; }
         table, tbody {vertical-align: top; overflow: visible; }
         .title-set{float:left;text-align:center;margin:5px;}
      </style>
   </head>
   <body style="padding:20px">
     <div style="clear: both;
     float: left;
     width: 100%;">
        <div style="float:left">
            <img src="{{ asset('public/images/promotedby.jpg') }}" width="190px"/>
        </div>
        <div style="float:right">
            <img src="{{ asset('public/images/logo.png') }}" width="120px" />
        </div>
     </div>
     <br/><br/><br/>
     <table width="100%" style="margin-top:20px">
      <tr>
        <td>  
      <p style=" padding-left: 10pt;text-indent: 0pt;line-height: 12pt;text-align: left;background: #948e8e;float: left;width: 100%;padding: 7px;"><span style=" color: #FEFEFE; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 10pt;">Video Pre-Issuance Verification - Transcript</span></p>
      <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;"/>
</td>
</tr>
</table>

@php
         $personal = json_decode($data->personal,true);
         $dob = date('d/m/Y',strtotime($personal['dob']));
         $created_at = date('d/m/Y H:i:s',strtotime($data->created_at,true));
         $completed_on = date('d/m/Y H:i:s',strtotime($data->completed_on));
         $completed_on_date = date('d/m/Y',strtotime($data->completed_on));
         $lang = 'English';
         if($data->lang=='hin'){
            $lang = 'Hindi';
         }
         if($data->lang=='tel'){
            $lang = 'Telugu';
         }
         if($data->lang=='guj'){
            $lang = 'Gujarati';
         }
		 if($data->lang=='tam'){
            $lang = 'Tamil';
         }
		 if($data->lang=='mar'){
            $lang = 'Marathi';
         }
		 if($data->lang=='mal'){
            $lang = 'Malayalam';
         }
		 if($data->lang=='ben'){
            $lang = 'Bengali';
         }
         if($data->lang=='pun'){
            $lang = 'Punjabi';
         }
         if($data->lang=='orr'){
            $lang = 'Orria';
         }

         $personal_disagree_status = 'No';

         $personal = json_decode($data->personal,true);
         $personal_disagree = json_decode($data->personal_disagree,true);
         $plan_disagree = json_decode($data->plan_disagree,true); 
         $device = json_decode($data->device,true); 
         $network = json_decode($data->network,true);

         $responseJson_info = json_decode($data->response,true); 
         $nomineeList=array();
         if($personal['nominee']!=''){
               $nomineeList = json_decode($personal['nominee'],true);
         } 

         if(!empty($personal_disagree) && strtolower($personal['name']) != strtolower($personal_disagree['in_name'])){
            $personal_disagree_status = 'Yes';
         }
         if(!empty($personal_disagree) &&  date('d/m/Y',strtotime($personal['dob'])) != date('d/m/Y',strtotime($personal_disagree['in_dob'])) ) {
            $personal_disagree_status = 'Yes';
         }
         if(!empty($personal_disagree) && strtolower($personal['gender']) != strtolower($personal_disagree['in_gender'])) {
            $personal_disagree_status = 'Yes';
         }
         if(!empty($personal_disagree) && strtolower($personal['email']) != strtolower($personal_disagree['in_email'])){
            $personal_disagree_status = 'Yes';
         }
         if(!empty($personal_disagree) && strtolower($personal['mobile']) != strtolower($personal_disagree['in_mob'])) {
            $personal_disagree_status = 'Yes';
         }
         if(!empty($personal_disagree) &&  $personal['anual_income'] != $personal_disagree['in_annul']) {
            $personal_disagree_status = 'Yes';
         }
         if(!empty($personal_disagree) && strtolower($personal['address']) != strtolower($personal_disagree['in_add'])) {
            $personal_disagree_status = 'Yes';
         }
         foreach($nomineeList as $key=>$nominee)  {
            if(!empty($personal_disagree) && strtolower($nominee['value']) != strtolower($personal_disagree['in_nom'][$key]['value'])) {
               $personal_disagree_status = 'Yes';
            }
         }

         $plan_disagree_status = 'No';
         if(!empty($plan_disagree) && strtolower($proposal_info['PRODUCT_CODE']) != strtolower($plan_disagree['in_planname'])) {
            $plan_disagree_status = 'Yes';
         }
         if(!empty($plan_disagree) && strtolower($proposal_info['PREMIUM']) != strtolower($plan_disagree['in_premiumamount'])){
            $plan_disagree_status = 'Yes';
         }
         if(!empty($plan_disagree) && strtolower($proposal_info['MODE_OF_PREMIUM']) != strtolower($plan_disagree['in_paymentFre'])){
            $plan_disagree_status = 'Yes';
         }
         if(!empty($plan_disagree) && strtolower($proposal_info['PREM_TERM']) != strtolower($plan_disagree['in_payingTerm'])){
            $plan_disagree_status = 'Yes';
         }
         if(!empty($plan_disagree) && strtolower($proposal_info['POLICY_TERM']) != strtolower($plan_disagree['in_policyTerm'])) {
            $plan_disagree_status = 'Yes';
         }
         if(!empty($plan_disagree) && strtolower($proposal_info['SUM_ASSURE']) != strtolower($plan_disagree['in_Assuredsum'])){
            $plan_disagree_status = 'Yes';
         }
         @endphp
 
      
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt;width: 100%;margin-top:30px;margin-bottom: 20px;" cellspacing="0">
        <tr style="height:15pt">
           <td style="width:200px;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
              <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;padding: 12px;">PIVC status</p>
           </td> 
           <td style="border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            <p class="s2" style="padding-top: 8pt;padding-left: 3pt;text-indent: 0pt;text-align: left;font-weight:bold;color:red;">
            @if($data->complete_status==1)
				@if($data['disclaimer_disagree']=='Yes' && $data['medical_disagree']=='Yes' && $personal_disagree_status== 'No' && $plan_disagree_status== 'No' && $data['video_disagree']=='Yes')
					Completed 
				@else
					Completed with Discrepancy
				@endif
			@else
				Not Completed
			@endif</p>
         </td> 
        </tr> 
    </table>
   
      <table style="border-collapse:collapse;margin-left:6.33105pt;width:100%" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:69pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">Application No.</p>
            </td>
            <td style="width:78pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Proposer Name</p>
            </td>
            <td style="width:39pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 1pt;padding-right: 4pt;text-indent: 0pt;text-align: center;">Gender</p>
            </td>
            <td style="width:57pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">DOB</p>
            </td>
            <td style="width:59pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Contact No.</p>
            </td>
            <td style="width:67pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Date of Trigger</p>
            </td>
            <td style="width:77pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">PIVC completed</p>
            </td>
            <td style="width:88pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 9pt;text-indent: 0pt;text-align: center;">Language Chosen</p>
            </td>
         </tr>
        
         <tr style="height:29pt">
            <td style="width:69pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 8pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$data->app_no.'-'.$data->version}}</p>
            </td>
            <td style="width:78pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 7pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">{{$personal['name']}}</p>
            </td>
            <td style="width:39pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 8pt;padding-right: 2pt;text-indent: 0pt;text-align: center;">{{$personal['gender']}}</p>
            </td>
            <td style="width:57pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 7pt;padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$dob}}</p>
            </td>
            <td style="width:59pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            <p class="s2" style="padding-top: 7pt;padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$personal['mobile']}} </p>
            
            </td>
            <td style="width:67pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            <p class="s2" style="padding-top: 7pt;padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">
            {{$created_at}}</p> 
            </td>
            <td style="width:77pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            <p class="s2" style="padding-top: 7pt;padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$completed_on}}</p>  
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 8pt;padding-left: 9pt;text-indent: 0pt;text-align: center;">{{$lang}}</p>
            </td>
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p> 
 
      <p style="padding-top: 4pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Personal Details Info :</p> 
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt;width:100%" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 1pt;text-indent: 0pt;text-align: center;">Sr.No.</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Particulars</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">Details as per App form</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Changes/comments (if any)</p>
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">Confirmation</p>
            </td>
         </tr>
         @php
         $index=1;
        
         @endphp
         <tr style="height:14pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Name of LA</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$personal['name']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
             
               @if(!empty($personal_disagree) && strtolower($personal['name']) != strtolower($personal_disagree['in_name'])) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> {{$personal_disagree['in_name']}}</p>
               @endif 
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) && strtolower($personal['name']) != strtolower($personal_disagree['in_name'])) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Date of Birth</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
               {{date('d/m/Y',strtotime($personal['dob']))}}
            </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
              
            @if( !empty($personal_disagree) && date('d/m/Y',strtotime($personal['dob'])) != date('d/m/Y',strtotime($personal_disagree['in_dob'])) ) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{date('d/m/Y',strtotime($personal_disagree['in_dob'])) }}  </p>
               @endif 

            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) &&  date('d/m/Y',strtotime($personal['dob'])) != date('d/m/Y',strtotime($personal_disagree['in_dob'])) ) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
       
         <tr style="height:16pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Gender</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
                  <p style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;font-weight:normal">
                  @if($personal['gender'] == 'M')
                     Male
                  @else
                     Female
                  @endif
                     
                  </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               
            @if(!empty($personal_disagree) && strtolower($personal['gender']) != strtolower($personal_disagree['in_gender'])) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                  @if($personal_disagree['in_gender'] == 'M')
                     Male
                  @else
                     Female
                  @endif 
               </p>
            @endif 

            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) && strtolower($personal['gender']) != strtolower($personal_disagree['in_gender'])) 
                  Changed
               @else
                  Agreed
               @endif
            </p>
            </td>
         </tr>

         <tr style="height:16pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Email ID</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
                  <p style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
                  <a href="mailto:{{$personal['email']}}" class="s3">{{$personal['email']}}</a>
                  </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               
            @if(!empty($personal_disagree) && strtolower($personal['email']) != strtolower($personal_disagree['in_email'])) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                  {{$personal_disagree['in_email']}}
               </p>
            @endif 

            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) && strtolower($personal['email']) != strtolower($personal_disagree['in_email'])) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Mobile No.</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> {{$personal['mobile']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            @if(!empty($personal_disagree) && strtolower($personal['mobile']) != strtolower($personal_disagree['in_mob'])) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                  {{$personal_disagree['in_mob']}}
               </p>
            @endif 
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) && strtolower($personal['mobile']) != strtolower($personal_disagree['in_mob'])) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         <tr style="height:16pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Annual Income</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$personal['anual_income']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            @if(!empty($personal_disagree) && $personal['anual_income'] != $personal_disagree['in_annul']) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                  {{$personal_disagree['in_annul']}}
               </p>
            @endif 
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) &&  $personal['anual_income'] != $personal_disagree['in_annul']) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         <tr style="height:17pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Address</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$personal['address']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            @if(!empty($personal_disagree) && strtolower($personal['address']) != strtolower($personal_disagree['in_add'])) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                  {{$personal_disagree['in_add']}}
               </p>
            @endif 
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(!empty($personal_disagree) && strtolower($personal['address']) != strtolower($personal_disagree['in_add'])) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         @php
         $nomineeList=array();
         if($personal['nominee']!=''){
            $nomineeList = json_decode($personal['nominee'],true);
		 } 
         @endphp
         @if(!empty($nomineeList))
			 
		 <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;"></p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;"><b>Nominee List</b></p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               
            </td>
         </tr>
		 
        @foreach($nomineeList as $key=>$nominee)   
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$index++}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">{{$nominee['label']}}</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$nominee['value']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               @if(!empty($personal_disagree) && strtolower($nominee['value']) != strtolower($personal_disagree['in_nom'][$key]['value'])) 
                  <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                     {{$personal_disagree['in_nom'][$key]['value']}}
                  </p>
               @endif
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($personal_disagree) && strtolower($nominee['value']) != strtolower($personal_disagree['in_nom'][$key]['value'])) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         @endforeach  
         @endif
         
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Product Details Confirmation :</p> 
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt;width:100%" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 1pt;text-indent: 0pt;text-align: center;">Sr.No.</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Particulars</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">Details as per App form</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Changes/comments (if any)</p>
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">Confirmation</p>
            </td>
         </tr>
         <tr style="height:14pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: center;">1</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Plan Name</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
            {{$proposal_info['PRODUCT_CODE']}}
            </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
             
            @if(!empty($plan_disagree) && strtolower($proposal_info['PRODUCT_CODE']) != strtolower($plan_disagree['in_planname'])) 
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                  {{$plan_disagree['in_planname']}}
               </p>
            @endif 

            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(!empty($plan_disagree) && strtolower($proposal_info['PRODUCT_CODE']) != strtolower($plan_disagree['in_planname'])) 
                  Changed
               @else
                  Agreed
               @endif
            </p>
            </td>
         </tr>
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;text-indent: 0pt;text-align: center;">2</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Plan type</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$data['plan_type']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               @if(!empty($plan_disagree) && strtolower($data['plan_type']) != strtolower($plan_disagree['in_plantype'])) 
                  <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                     {{$plan_disagree['in_plantype']}}
                  </p>
               @endif 
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($plan_disagree) && strtolower($data['plan_type']) != strtolower($plan_disagree['in_plantype'])) 
                  Changed
               @else
                  Agreed
               @endif 
            </p>
            </td>
         </tr>
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;text-indent: 0pt;text-align: center;">3</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Premium Amount</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
               {{$proposal_info['PREMIUM']}}
            </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
                  @if(!empty($plan_disagree) && strtolower($proposal_info['PREMIUM']) != strtolower($plan_disagree['in_premiumamount'])) 
                     <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                        {{$plan_disagree['in_premiumamount']}}
                     </p>
                  @endif
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;"> @if(!empty($plan_disagree) && strtolower($proposal_info['PREMIUM']) != strtolower($plan_disagree['in_premiumamount']))
                  Changed
               @else
                  Agreed
               @endif </p>
            </td>
         </tr>
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 4pt;text-indent: 0pt;text-align: center;">4</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Payment Frequency</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
               {{$proposal_info['MODE_OF_PREMIUM']}}
            </p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            @if(!empty($plan_disagree) && strtolower($proposal_info['MODE_OF_PREMIUM']) != strtolower($plan_disagree['in_paymentFre'])) 
                     <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                        {{$plan_disagree['in_paymentFre']}}
                     </p>
                  @endif
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($plan_disagree) && strtolower($proposal_info['MODE_OF_PREMIUM']) != strtolower($plan_disagree['in_paymentFre']))
                  Changed
               @else
                  Agreed
               @endif
            </p>
            </td>
         </tr>
         <tr style="height:14pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 3pt;text-indent: 0pt;text-align: center;">5</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Premium Paying Term</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 3pt;text-indent: 0pt;text-align: left;"> {{$proposal_info['PREM_TERM']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            @if(!empty($plan_disagree) && strtolower($proposal_info['PREM_TERM']) != strtolower($plan_disagree['in_payingTerm'])) 
                     <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                        {{$plan_disagree['in_payingTerm']}}
                     </p>
                  @endif
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($plan_disagree) && strtolower($proposal_info['PREM_TERM']) != strtolower($plan_disagree['in_payingTerm'])) 
                  Changed
               @else
                  Agreed
               @endif
            </p>
            </td>
         </tr>
         <tr style="height:14pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 4pt;text-indent: 0pt;text-align: center;">6</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Policy Term</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$proposal_info['POLICY_TERM']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            @if(!empty($plan_disagree) && strtolower($proposal_info['POLICY_TERM']) != strtolower($plan_disagree['in_policyTerm'])) 
                     <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                        {{$plan_disagree['in_policyTerm']}}
                     </p>
                  @endif
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($plan_disagree) && strtolower($proposal_info['POLICY_TERM']) != strtolower($plan_disagree['in_policyTerm'])) 
                  Changed
               @else
                  Agreed
               @endif
            </p>
            </td>
         </tr>
         <tr style="height:18pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: center;">7</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 3pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Sum Assured</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$proposal_info['SUM_ASSURE']}}</p>
            </td>
            <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               

            @if(!empty($plan_disagree) && strtolower($proposal_info['SUM_ASSURE']) != strtolower($plan_disagree['in_Assuredsum'])) 
                     <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> 
                        {{$plan_disagree['in_Assuredsum']}}
                     </p>
                  @endif

            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 3pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(!empty($plan_disagree) && strtolower($proposal_info['SUM_ASSURE']) != strtolower($plan_disagree['in_Assuredsum'])) 
                  Changed
               @else
                  Agreed
               @endif
            </p>
            </td>
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Medical Question Confirmation: </p>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt;margin-bottom: 100px;" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:330pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">Particulars</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Changes/comments (if any)</p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 11pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">Confirmation</p>
            </td>
         </tr>
         <tr style="height:112pt">
            <td style="width:330pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 4pt;padding-right: 6pt;text-indent: 0pt;line-height: 1.2;text-align: justify;">We would like you to confirm that you have read and answered all the medical questions or Good health declaration in the proposal form correctly and disclosed all the details of medical/treatment history(if any).</p>
               <p class="s2" style="padding-top: 4pt;padding-left: 4pt;padding-right: 9pt;text-indent: 0pt;line-height: 1.2;text-align: left;">I, {{$personal['name']}}, not currently suffering or not undergoing/underwent any treatment for any health condition or not undergone or not planned for any surgery as on date of submission of proposal.</p>
               <p class="s2" style="padding-top: 2pt;padding-left: 4pt;padding-right: 9pt;text-indent: 0pt;line-height: 1.2;text-align: left;">I have also not experienced any symptoms like fever, cough, shortness of breath in the last 30 days or not advised to test Corona Virus test (SARS-CoV2 / COVID19).</p>
               <p class="s2" style="padding-top: 7pt;padding-left: 3pt;text-indent: 0pt;line-height: 1.2;text-align: left;">Non-disclosure of any adverse medical history of Life assured may lead to rejection of claim in future.</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if($data['medical_disagree']=='Yes')
               Agreed
               @else
               Disagreed
               @endif</p>
            </td>
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/><br/><br/></p>
      <p style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Disclaimer:</p>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:330pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">Particulars</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Changes/comments (if any)</p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 11pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">Confirmation</p>
            </td>
         </tr>
         <tr style="height:71pt">
            <td style="width:330pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;padding-right: 6pt;text-indent: 0pt;line-height: 1.2;text-align: justify;">We believe that all the policy features have been explained to you correctly. The amount paid by you is only towards the premium of the policy and you have not been promised with any of kind of bonus, loan, mobile tower installation or refund against any other policy. Please do not believe in any such false promises and highlight any such concern to us immediately. So can we confirm no such promise has been made to you. Please note IndiaFirst may not consider any such complaint in the matter on a later date.</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if($data['disclaimer_disagree']=='Yes')
               Agreed
               @else
               Disagreed
               @endif
            </p>
            </td>
         </tr> 
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/><br/><br/></p>
      <p style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Video:</p>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:330pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">Particulars</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Changes/comments (if any)</p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 11pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">Confirmation</p>
            </td>
         </tr>
         <tr style="height:71pt">
            <td style="width:330pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;padding-right: 6pt;text-indent: 0pt;line-height: 1.2;text-align: justify;">I {{$personal['name']}}, hereby confirm that details of my proposal have been verified by me. I consent to processing IndiaFirst Life insurance policy further. I also confirm that the Application Form & Benefit Illustration has been provided by me.</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if($data['video_disagree']=='Yes')
               Agreed
               @else
               Disagreed
               @endif
            </p>
            </td>
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Device and Network Information:</p>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt;width:100%" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:66pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-right: 1pt;text-indent: 0pt;text-align: center;">PIVC comp. on</p>
            </td>
            <td style="width:34pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 2pt;text-indent: 0pt;text-align: left;">Device</p>
            </td>
            <td style="width:49pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">OS</p>
            </td>
            <td style="width:57pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">OS version</p>
            </td>
            <td style="width:37pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-right: 2pt;text-indent: 0pt;text-align: right;">Browser</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 1pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">Browser Version</p>
            </td>
            <td style="width:65pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-right: 3pt;text-indent: 0pt;text-align: center;">Effective type</p>
            </td>
            <td style="width:70pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 23pt;text-indent: 0pt;text-align: left;">RTT</p>
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 11pt;padding-right: 3pt;text-indent: 0pt;text-align: center;">Downlink</p>
            </td>
         </tr> 
         <tr style="height:16pt">
            <td style="width:66pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;text-indent: 0pt;text-align: center;">{{$completed_on_date}}</p>
            </td>
            <td style="width:34pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">{{$device['device']}}</p>
            </td>
            <td style="width:49pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$device['os']}}</p>
            </td>
            <td style="width:57pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29"> 
            <p class="s2" style="padding-top: 1pt;padding-left: 3pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$device['os_version']}}</p>
            </td>
            <td style="width:37pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">{{$device['browser']}}</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">{{$device['browser_version']}}</p>
            </td>
            <td style="width:65pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-right: 2pt;text-indent: 0pt;text-align: center;">{{$network['type']}}</p>
            </td>
            <td style="width:70pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 24pt;text-indent: 0pt;text-align: left;">{{$network['rtt']}}</p>
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 11pt;padding-right: 3pt;text-indent: 0pt;text-align: center;">{{$network['downlink']}}</p>
            </td>
         </tr>
      </table>
      @php
 
$address_info = $address_disp; 

      @endphp
      <p style="padding-top: 8pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Geo Tagging:</p>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <table style="border-collapse:collapse;margin-left:6.33105pt;width:100%" cellspacing="0">
         <tr style="height:15pt">
            <td style="width:64pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">IP Address</p>
            </td>
            <td style="width:73pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 12pt;padding-right: 7pt;text-indent: 0pt;text-align: center;">Latitude</p>
            </td>
            <td style="width:81pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-right: 18pt;text-indent: 0pt;text-align: right;">Longitude</p>
            </td>
            <td style="width:206pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s1" style="padding-left: 22pt;padding-right: 20pt;text-indent: 0pt;text-align: center;">Geo Location</p>
            </td> 
         </tr>
         <tr style="height:16pt">
            <td style="width:64pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 4pt;text-indent: 0pt;text-align: left;">{{$data['ipaddress']}}</p>
            </td>
            <td style="width:73pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 12pt;padding-right: 7pt;text-indent: 0pt;text-align: center;">{{$data['latitude']}}</p>
            </td>
            <td style="width:81pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-right: 17pt;text-indent: 0pt;text-align: right;">{{$data['longitude']}}</p>
            </td>
            <td style="width:206pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 22pt;padding-right: 20pt;text-indent: 0pt;text-align: center;">{{$address_info}}</p>
            </td> 
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Video Consent: <br/><span class="h1">Video Link:  <a href="{{$data->video}}" target="_blank">Click Here</a></span></p> 
      <p style="text-indent: 0pt;text-align: left;"><br/></p> 
      <p style="padding-top: 4pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Photo status:</p> <br/>
      <p style="text-indent: 0pt;text-align: left;"><br/></p> 
      @if(file_exists(public_path('upload').'/'.$data->app_no.'/img/'.'Personal Details - Show.jpeg'))  
            <div class="title-set">
            <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->app_no}}/img/Personal Details - Show.jpeg" width="130px"/><br/>
            <span style="font-size:12px;">Personal Details Screen</span><br/>
            <span style="font-size:12px;">({{$responseJson_info[0]['update_on']}})</span>  
            </div>
      @endif 
      @if(file_exists(public_path('upload').'/'.$data->app_no.'/img/'.'Disclaimer.jpeg'))  
           <div class="title-set">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->app_no}}/img/Disclaimer.jpeg" width="130px"/><br/>
           <span style="font-size:12px;">Disclaimer Screen</span><br/>
           <span style="font-size:12px;">({{$responseJson_info[1]['update_on']}})</span> 
           </div>
     @endif 
     @if(file_exists(public_path('upload').'/'.$data->app_no.'/img/'.'Plan Details.jpeg'))  
           <div class="title-set">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->app_no}}/img/Plan Details.jpeg" width="130px"/><br/>
           <span style="font-size:12px;">Plan Details Screen</span><br/>
           <span style="font-size:12px;">({{$responseJson_info[2]['update_on']}})</span> 
           </div>
     @endif 
     @if(file_exists(public_path('upload').'/'.$data->app_no.'/img/'.'Medical.jpeg'))  
           <div class="title-set">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->app_no}}/img/Medical.jpeg" width="130px"/><br/>
           <span style="font-size:12px;">Medical Screen</span><br/>
           <span style="font-size:12px;">({{$responseJson_info[3]['update_on']}})</span>  
           </div>
     @endif 
     @if(file_exists(public_path('upload').'/'.$data->app_no.'/img/'.'video_consent.jpeg'))  
           <div class="title-set">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->app_no}}/img/video_consent.jpeg" width="130px"/><br/>
           <span style="font-size:12px;">Video Screen</span><br/>
           <span style="font-size:12px;">({{$responseJson_info[4]['update_on']}})</span> 
           </div>
     @endif 
      <table style="border-collapse:collapse;margin-left:6.33105pt;width: 722px;margin-top:340px;margin-bottom: 20px;" cellspacing="0">
        <tr style="height:15pt">
           <td style="width:69pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29;padding:5px">
          
            <p style="padding-top: 4pt;padding-left: 7pt;text-indent: 0pt;line-height: 1.2;text-align: left;"><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt;">IndiaFirst   Life   Insurance   Company   Ltd., </span>
                <br/>
                <span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8pt;">12th and 13th Floor, North [C] Wing, Tower 4, Nesco IT Park, Nesco Center, Western Express Highway, Goregaon (East), Mumbai – 400063, IRDA Reg. No. 143. CIN: U66010MH2008PLC183679.</span></p>
           </td> 
           <td style="width:69pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29;padding:5px">
           
            <p style="padding-top: 5pt;padding-left: 9pt;text-indent: 0pt;text-align: left;"><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt;">Tel: </span><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8pt;">+91 22 6165 8700 </span><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt;">Fax: </span><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8pt;">+91 22 6857 0600 </span><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt;">Toll Free: </span><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8pt;">1800-209-8700</span></p>
            <p style="border-bottom: 2px solid #ddd;margin-top:10px;margin-left: 11px;margin-right: 7px;"></p>
            <p style="padding-left: 11pt;text-indent: 0pt;text-align: left;"><a href="mailto:customer.first@indiafirstlife.com" style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt;" target="_blank">E-mail: </a><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8pt;">customer.first@indiafirstlife.com </span><a href="http://www.indiafirstlife.com/" style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8pt;" target="_blank">Website: </a><a href="http://www.indiafirstlife.com/" style="
                text-decoration: none;
            " target="_blank"><span style=" color: #434242; font-family:Tahoma, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8pt;">www.indiafirstlife.com</span></a></p>
            
            
            <p style="text-indent: 0pt;text-align: left;"><br/></p>
         </td> 
        </tr>
    </table>

      
     
      <p style="text-indent: 0pt;text-align: left;"/>
        
   </body>
</html>