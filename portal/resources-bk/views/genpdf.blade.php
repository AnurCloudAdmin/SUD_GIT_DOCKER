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
         .photo-face{float:left;text-align:center;margin:5px;}
      </style>
   </head>
   <body style="padding:20px">
     <div style="clear: both;
     float: left;
     width: 100%;"> 
        <div style="float:left">
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
         $params = json_decode($data->params,true);
         $dob = date('d/m/Y',strtotime($params['personal_dob']));
         $created_at = date('d/m/Y H:i:s',strtotime($data->created_at,true));
         $completed_on = date('d/m/Y H:i:s',strtotime($data->completed_on));
         $completed_on_date = date('d/m/Y',strtotime($data->completed_on));
         $lang = 'English'; 

         if($params['policy_rider_name']!='' && $params['policy_rider_name']!=null){
         $params['policy_rider_name']  = $params['policy_rider_name'][0];
         $params['policy_rider_sum_assured']  = $params['policy_rider_sum_assured'][0];
         }
         
         $device = json_decode($data->device,true);
         $network = json_decode($data->network,true);
         $location = json_decode($data->location,true);
         $personal_disagree_response = (isset($data->personal_disagree_response) && $data->personal_disagree_response!= null) ? json_decode($data->personal_disagree_response,true) : array(); 
         $policy_disagree_response = (isset($data->policy_disagree_response) && $data->policy_disagree_response!= null) ? json_decode($data->policy_disagree_response,true) : array();
          
         $medical_checked_response = (isset($data->medical_checked_response) && $data->medical_checked_response!= null) ? json_decode($data->medical_checked_response,true) : array();
         
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
				@if($data['personal_disagree']=='Agree' && $data['policy_disagree']=='Agree')
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
               <p class="s2" style="padding-top: 8pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$data->proposal_no}}</p>
            </td>
            <td style="width:78pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 7pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">{{$params['personal_name']}}</p>
            </td>
            <td style="width:39pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 8pt;padding-right: 2pt;text-indent: 0pt;text-align: center;">{{$params['personal_gender']}}</p>
            </td>
            <td style="width:57pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 7pt;padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$dob}}</p>
            </td>
            <td style="width:59pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
            <p class="s2" style="padding-top: 7pt;padding-left: 4pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">{{$params['personal_mobile']}} </p>
            
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
 
      <p style="padding-top: 4pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Personal Details Info ({{$data['personal_disagree']}}):</p> 
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
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Name </p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$params['personal_name']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Policy Owner Name",$personal_disagree_response)) 
                  Disagree
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
               {{date('d/m/Y',strtotime($params['personal_dob']))}}
            </p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Date of Birth",$personal_disagree_response)) 
                  Disagree
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
                  {{$params['personal_gender']}}
                     
                  </p>
            </td>
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Gender",$personal_disagree_response)) 
                  Disagree
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
                  <a href="mailto:{{$params['personal_email']}}" class="s3">{{$params['personal_email']}}</a>
                  </p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Email ID",$personal_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;"> {{$params['personal_mobile']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Mobile Number",$personal_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Occupation</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$params['personal_occupation']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Occupation",$personal_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$params['personal_address']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               @if(in_array("Address",$personal_disagree_response)) 
                  Disagree
               @else
                  Agreed
               @endif  
            </p>
            </td>
         </tr> 
         
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Product Details Confirmation ({{$data['policy_disagree']}}):</p> 
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
               @if(isset($params['policy_plan'])) 
               {{$params['policy_prod_name']}}-{{$params['policy_plan']}}
               @else
               {{$params['policy_prod_name']}}
               @endif
            </p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Product Name",$policy_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-top: 2pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Sum Assured</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$params['policy_sum_assured']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Sum Assured",$policy_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-top: 2pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Rider </p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
               {{$params['policy_rider_name']}}
            </p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Rider Name",$policy_disagree_response)) 
                  Disagree
               @else
                  Agreed
               @endif  
            </p>
            </td>
         </tr>
         <tr style="height:15pt">
            <td style="width:30pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 4pt;text-indent: 0pt;text-align: center;">4</p>
            </td>
            <td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Rider Sum Assued</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">
               {{$params['policy_rider_sum_assured']}}
            </p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Rider Sum Assured",$policy_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Premium Amount</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 3pt;text-indent: 0pt;text-align: left;"> {{$params['policy_preimum_amount']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Premium Amount",$policy_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-left: 7pt;text-indent: 0pt;text-align: left;">Policy Payment Type</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$params['policy_payment_type']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Payment Type",$policy_disagree_response)) 
                  Disagree
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
               <p class="s2" style="padding-top: 3pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Payment Frequency</p>
            </td>
            <td style="width:150pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$params['policy_Frequency']}}</p>
            </td> 
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
            
               @if(in_array("Payment Frequency",$policy_disagree_response)) 
                  Disagree
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
      <table style="border-collapse:collapse;margin-left:6.33105pt;margin-bottom: 30px;" cellspacing="0">
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
               
               <p class="s2" style="padding-top: 2pt;padding-left: 4pt;padding-right: 6pt;text-indent: 0pt;line-height: 1.2;text-align: justify;">We would like you to confirm that you have read and answered all the medical questions in the Proposal correctly and disclosed all details of medical/treatment history (if any) [Non-disclosure of any adverse medical history may lead to rejection of claim in future].
               @if(in_array('Q1',$medical_checked_response))
               (Agree)
               @else
               (Disagree)
               @endif
            </p>
                
               <p class="s2" style="padding-top: 4pt;padding-left: 4pt;padding-right: 9pt;text-indent: 0pt;line-height: 1.2;text-align: left;">It is not a fixed deposit product
               @if(in_array('Q2',$medical_checked_response))
               (Agree)
               @else
               (Disagree)
               @endif
            </p>
                
               <p class="s2" style="padding-top: 2pt;padding-left: 4pt;padding-right: 9pt;text-indent: 0pt;line-height: 1.2;text-align: left;">Understand product features and benefits as per the benefit illustration and stated during the above journey.
               @if(in_array('Q3',$medical_checked_response))
               (Agree)
               @else
               (Disagree)
               @endif
            </p> 
              
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
               
               @if(count($medical_checked_response)!=3) 
                  Disagree
               @else
                  Agreed
               @endif  
               </p>
            </td>
         </tr>
      </table>  
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
               <p class="s2" style="padding-top: 2pt;padding-left: 3pt;padding-right: 6pt;text-indent: 0pt;line-height: 1.2;text-align: justify;">I have understood the features, terms and conditions of this plan and give consent for further processing of my insurance application.</p>
            </td>
            <!-- <td style="width:123pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
            </td> -->
            <td style="width:80pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p style="text-indent: 0pt;text-align: left;"><br/></p>
               <p class="s2" style="padding-left: 10pt;padding-right: 11pt;text-indent: 0pt;text-align: center;"> 
               Agreed 
            </p>
            </td>
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;margin-top:190px">Device and Network Information:</p>
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
            <td style="width:73pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 12pt;padding-right: 7pt;text-indent: 0pt;text-align: center;">{{$location['lng']}}</p>
            </td>
            <td style="width:81pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">   
               <p class="s2" style="padding-top: 1pt;padding-right: 17pt;text-indent: 0pt;text-align: right;">{{$location['lat']}}</p>
            </td>
            <td style="width:206pt;border-top-style:solid;border-top-width:1pt;border-top-color:#2B2A29;border-left-style:solid;border-left-width:1pt;border-left-color:#2B2A29;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#2B2A29;border-right-style:solid;border-right-width:1pt;border-right-color:#2B2A29">
               <p class="s2" style="padding-top: 1pt;padding-left: 22pt;padding-right: 20pt;text-indent: 0pt;text-align: center;">{{$address_info}}</p>
            </td> 
         </tr>
      </table>
      <p style="text-indent: 0pt;text-align: left;"><br/></p>
      <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Video Consent: <br/><span class="h1">Video Link:  <a href="https://dev1.anurcloud.com/pramerica/portal/public/upload/{{$data->proposal_no}}/vid/consent.webm" target="_blank">Click Here</a></span></p> 
      <p style="text-indent: 0pt;text-align: left;"><br/></p> 
      <p style="padding-top: 4pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Photo status:</p>  
      <p style="text-indent: 0pt;text-align: left;"><br/></p> 
  
      @if(file_exists(public_path('upload').'/'.$data->proposal_no.'/img/'.'Personal Information.jpeg'))  
            <div class="title-set">
            <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->proposal_no}}/img/Personal Information.jpeg" width="130px"/><br/>
            <span style="font-size:12px;">Personal Details Screen</span><br/> 
            </div>
      @endif 
      @if(file_exists(public_path('upload').'/'.$data->proposal_no.'/img/'.'Policy Information.jpeg'))  
           <div class="title-set">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->proposal_no}}/img/Policy Information.jpeg" width="130px"/><br/>
           <span style="font-size:12px;">Policy Information Screen</span><br/> 
           </div>
     @endif  
     @if(file_exists(public_path('upload').'/'.$data->proposal_no.'/img/'.'video_consent.jpeg'))  
           <div class="title-set">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->proposal_no}}/img/video_consent.jpeg" width="130px"/><br/>
           <span style="font-size:12px;">Video Screen</span><br/> 
           </div>
     @endif  

     @if($data->face_response !='')   
     

     <div style="border:1px solid #ddd;padding:7px;width:100%;height:120px;clear:both;">
     <div class="photo-face">
           <img style="padding:5px;border:1px solid #ddd;"   height="100px" src="{{public_path('upload')}}/{{$data->proposal_no}}/img/link_upload.jpeg" width="100px;"/><br/> 
           </div>
           <div class="photo-face">
           <img style="padding:5px;border:1px solid #ddd;" src="{{public_path('upload')}}/{{$data->proposal_no}}/img/video_consent.jpeg" width="100px;" height="100px"/><br/> 
           </div> 

     <p style="margin-left:20px;padding-left: 15pt;text-indent: 0pt;text-align: left;">Face Score <br/>
         
         </p>  
         <span style="padding-left: 5pt;font-size:12px;font-weignt:normal">Face Match Score *:  <b>{{$data->face_score}}<b></span><br/>
         <span style="padding-left: 5pt;font-size:12px;font-weignt:normal" >Face Match Status:  
            <b>
               @if($data->face_score >=40)
                  Face Matched
               @else
                  Face Not Match
               @endif
               </b>
               <br/> 
         </span>
         
         <p style="margin-top:40px;">* Face Score must be 40 and above for a Match</p>

      </div>
      @endif
   </body>
</html>