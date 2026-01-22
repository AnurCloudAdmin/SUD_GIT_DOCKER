<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php $params = json_decode($data['params']); ?>
    <?php 
       $applicationNumber = isset($params->Application_Number) ? $params->Application_Number : 'default';
       //$public_path = "D:";

       if(isset($version) && $version!=''){ 
        $public_path = public_path();
    //    $public_path = "D:";

    $image_url = $public_path.'/upload/' . $applicationNumber . '-' . $version . '/img/link_upload.jpeg';
    $selfie_url = $public_path.'/upload/' . $applicationNumber . '-' . $version . '/img/video_consent.jpeg';
    $images = $public_path.'/upload/' . $applicationNumber . '-' . $version . '/img';

       $imageExists = File::exists($image_url);

    }else{
 
      
         $public_path = public_path();
         $image_url = $public_path.'/upload/' . $applicationNumber . '/img/link_upload.jpeg';
         $selfie_url = $public_path.'/upload/' . $applicationNumber . '/img/video_consent.jpeg';
         $images = $public_path.'/upload/' . $applicationNumber . '/img';
  
         $imageExists = File::exists($image_url);
  
          }
    ?>
   
    <title>SUD Life {{$params->Application_Number}}</title>
    <style type="text/css">
      /*  * {
            font-family: Verdana, Arial, sans-serif;
        }
        body {
                font-size: 10pt;
        }
        h2, h3 {
            font-size: 12pt;
        }
        .keep-together {
         page-break-inside: avoid;
        }
        table {
            font-size: 9pt;
            width: 100%;
            border-collapse: collapse;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray;
        }

        .lnht {
            line-height: 0.5;
        }

        .page-break {
            page-break-before: always;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .face-status {
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        .face-score {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Adjusting for mPDF /
        img {
            max-width: 100%;
            height: auto;
        }
        .wwrap {
            word-wrap: break-word;
        }*/
        * {
        font-family: Verdana, Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-size: 10pt;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }

    h2, h3 {
        font-size: 12pt;
        margin: 10px 0;
    }

    p {
        margin: 5px 0;
    }

    /* Table Styling */
    table {
        font-size: 9pt;
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    th, td {
        padding: 5px 10px;
        text-align: left;
        vertical-align: top;
        border: 1px solid #ddd;
    }

    thead th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tfoot td {
        font-weight: bold;
        font-size: 9pt;
    }

    /* Specific Table Styles */
    .gray {
        background-color: lightgray;
    }

    .lnht {
        line-height: 0.5;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    /* Page Break */
    .page-break {
        page-break-before: always;
    }

    /* Image Styling */
    img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    .wwrap {
        word-wrap: break-word;
    }

    /* Alignment Adjustments */
    td.align-top {
        vertical-align: top;
    }

    td.align-center {
        text-align: center;
    }

    td.align-right {
        text-align: right;
    }

    /* Header Logo and Title */
    .header-table td img {
        max-height: 50px;
    }

    .header-table td h2 {
        margin: 0;
        font-size: 14pt;
        font-weight: bold;
    }

    /* ID and Selfie Images */
    .id-selfie img {
        max-width: 100px;
        max-height: 100px;
        border: 1px solid #ccc;
        margin: 5px 0;
    }

    /* Speech Response */
    .speech-response {
        line-height: 1.8;
        margin: 10px 0;
        font-size: 9pt;
    }

    /* Highlighting BBCode Conversion */
    .bbcode-bold {
        font-weight: bold;
    }

    .bbcode-red {
        color: red;
    }
    </style>
</head>
<body>
    
<div class="keep-together">
    <table>
        <tr>
            <td align="top"><img src="{{ public_path('images/logo.png') }}" alt="Logo"/></td>
            <td align="left">
                <h2>SUD Life PIVC Video Verification Report</h2>
            </td>
        </tr>
    </table>
   
    <table>
        <tr>
            <td>
            @if($data['sys_lang']=='eng') 
            @php $syslang = 'English'; @endphp
            @endif   
            @if($data['sys_lang']=='hin') 
            @php $syslang = 'Hindi'; @endphp
            @endif 
            @if($data['sys_lang']=='ben') 
            @php $syslang = 'Bengali'; @endphp
            @endif 
            @if($data['sys_lang']=='mar') 
            @php $syslang = 'Marathi'; @endphp
            @endif 
            @if($data['sys_lang']=='ori') 
            @php $syslang = 'Oriya'; @endphp
            @endif 
            @if($data['sys_lang']=='tel') 
            @php $syslang = 'Telugu'; @endphp
            @endif 
            @if($data['sys_lang']=='tam') 
            @php $syslang = 'Tamil'; @endphp
            @endif 
            @if($data['sys_lang']=='kan') 
            @php $syslang = 'Kannada'; @endphp
            @endif 
            @if($data['sys_lang']=='mal') 
            @php $syslang = 'Malayalam'; @endphp
            @endif 
            @if($data['sys_lang']=='guj') 
            @php $syslang = 'Gujarati'; @endphp
            @endif 
            @if($data['sys_lang']=='ass') 
            @php $syslang = 'Assamese'; @endphp
            @endif
                <h3 style="text-decoration: underline;">Proposal Details</h3><br>
                <p><b>Application Number</b>: {{$params->Application_Number}}</p>
                <p><b>Proposer Name</b>: {{$params->Proposer_name}}</p>
                <p><b>Plan Name</b>: {{$params->plan_details->Plan_Name}}</p>
                <p><b>Language</b>: {{$syslang}}</p>
            </td>
            <td>
                <h3 style="text-decoration: underline;">Device Details</h3>
                <?php $location = json_decode($data['location']); ?>
                <p><b>Geo Tag </b>: Lat- {{ $location->lat ?? 'N/A' }}, Long- {{ $location->long ?? 'N/A' }}</p>
                <p><b>Network Details</b>: {{ json_decode($data['network'])->type ?? 'N/A' }}</p>
                <p><b>Device</b>: {{ json_decode($data['device'])->device ?? 'N/A' }}</p>
                <p><b>Operating System (OS)</b>: {{ json_decode($data['device'])->os ?? 'N/A' }}</p>
                <p><b>Browser</b>: {{ json_decode($data['device'])->browser ?? 'N/A' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="text-decoration: underline;">Contact Details</h3>
                <p><b>Mobile</b>: {{$params->Mobile_number}} | <b>Email</b>: {{ $params->Email_id }}</p>
            </td>
            <td>
                <p><b>Completed On</b>: {{$data['completed_on']}}</p><br/>
                <?php
                $rawResponse = $data['speech_res'];

                //dd($rawResponse);

                // Find the first `{` which is the start of the JSON
                $jsonStart = strpos($rawResponse, '{');
                
                // Extract the JSON string
                $jsonString = substr($rawResponse, $jsonStart);
                
                // Decode it safely
                $speechResponse = json_decode($jsonString, true);
                
               // dd($speechResponse); 
                   $score = $speechResponse['score'];
                 ?>
                @if(
                    isset($data['face_response']) &&
                    isset($data['face_score']) &&
                    $data['face_score'] >= 30 &&
                    $score >= 65 &&
                    $data['policy_disagree'] == '0' &&
                    $data['personal_disagree'] == '0'
                )
                    <p><b>Final Status</b>: <b>Success</b> </p>
                @else
                    <p><b>Final Status</b>: <b>Failure</b> </p>
                @endif
            </td>
        </tr>
        <br/><br/>
        <tr>
            <td>
            @if($imageExists)
                    @if($data['face_score'] == 0)
                        @php
                            $org_upload = $public_path . '/upload/' . $applicationNumber . '/img/page0.jpeg';
                        @endphp
                        <p><b>ID Card Image</b></p>
                        <p>
                            <a href="{{ $org_upload }}" target="_blank">
                                <img src="{{ $org_upload }}" alt="Original ID Card Image" style="max-width: 100px;">
                            </a>
                        </p>
                    @else
                        <p><b>Face Image</b></p>
                        <p>
                            <a href="{{ $image_url }}" target="_blank">
                                <img src="{{ $image_url }}" alt="Face Image" style="max-width: 100px;">
                            </a>
                        </p>
                    @endif
                @else
                    <p>Photo Not Uploaded.</p>
                @endif
            </td>
            <td><b>Selfie Image</b></p>
            <p><a href="{{$selfie_url}}" target="_blank"><img src="{{$selfie_url}}" alt="Selfie Image" style="max-width: 100px;"></a></p> 
            </td>
        </tr>
        <tr></tr>
    </table>
    </div>
    <div class="page-break"></div>

    <table width="100%" border="1" cellspacing="0" cellpadding="10">
    <tr>
        <td colspan="2">
            <div class="page-break"></div>
            <table width="100%" style="background-color: #dde0e6; border-collapse: collapse;">
                
                <!-- Personal Section -->
                <tr class="item">
                    <td colspan="2" style="text-align: center; font-weight: bold; padding: 15px;">
                        @if($data['personal_disagree']=='0')
                            <h3><b>Personal Agree</b></h3>
                        @else
                            <h3><b>Personal Disagree</b></h3>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: center;">
                        @if($data['personal_disagree']=='0')
                            <img src="{{ $images }}/personal_details.jpeg" width="300px" height="480px" 
                                 style="margin: 10px; display: inline-block;">
                        @else
                            <img src="{{ $images }}/personal_details_disagree.jpeg" width="300px" height="480px" 
                                 style="margin: 10px; display: inline-block;">
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" style="text-align: left; font-weight: bold; padding: 15px;">
                        <b>Status:</b>    
                        @if($data['personal_disagree']=='0')
                            Agree
                        @else 
                            Disagree <br>  
                            @if(!empty($personaldisagreeResult) && isset($personaldisagreeResult)) 
                            
                                @foreach($personaldisagreeResult as $keyset => $personaldisagreeResultS)
                                
                                {{$keyset}} <b>:</b> {{$personaldisagreeResultS}}<br>
                                @endforeach 
                            @endif
                        @endif
                    </td>
                    
                </tr>
                <div class="page-break"></div>
                <!-- Policy Section -->
                <tr class="item">
                    <td colspan="2" style="text-align: center; font-weight: bold; padding: 15px;">
                        @if($data['policy_disagree']=='0')
                            <h3><b>Policy Agree</b></h3>
                        @else
                            <h3><b>Policy Disagree</b></h3>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: center;">
                        @if($data['policy_disagree']=='0')
                            <img src="{{ $images }}/policy_details.jpeg" width="300px" height="480px" 
                                 style="margin: 10px; display: inline-block;">
                        @else
                            <img src="{{ $images }}/policy_details_disagree.jpeg" width="300px" height="480px" 
                                 style="margin: 10px; display: inline-block;">
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: left; font-weight: bold; padding: 15px;">
                        <b>Status:</b>    
                        @if($data['policy_disagree']=='0')
                            Agree
                        @else
                            Disagree <br>    
                            @if(!empty($policydisagreeResult) && isset($policydisagreeResult))
                                @foreach($policydisagreeResult as $keyset => $policydisagreeResultS) 
                                    {{$keyset}} <b>:</b> {{$policydisagreeResultS}}<br>
                                @endforeach 
                            @endif 
                        @endif
                    </td>
                    
                </tr>
                <div class="page-break"></div>
                <!-- Video Consent (Always Last) -->
                 <?php
                    $rawResponse = $data['speech_res'];

                    // Find the first `{` which is the start of the JSON
                    $jsonStart = strpos($rawResponse, '{');
                    
                    // Extract the JSON string
                    $jsonString = substr($rawResponse, $jsonStart);
                    
                    // Decode it safely
                                   //$speechResponse = json_decode($jsonString, true);
                   $score = $speechResponse['score'];
                 ?>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <img src="{{ $images }}/video_consent.jpeg" width="300px" height="480px" 
                             style="margin: 10px; display: inline-block;">
                             <b>Status:</b>
                            @if(isset($data['face_response']) && isset($data['face_score']) && $data['face_score'] >= 30 && $score >=65 && $data['policy_disagree']=='0' && $data['personal_disagree']=='0')
                                <b>Success</b><br>
                                <b>face_score:</b> <b>{{ number_format($data['face_score'], 1) }}%</b>
                            @elseif(isset($data['face_response']) && isset($data['face_score']) && $data['face_score'] < 30 && $data['face_score'] > 1)
                                <b>Failure</b><br>
                                <b>face_score:</b> <b>{{ number_format($data['face_score'], 1) }}%</b>
                                @elseif(isset($data['face_response']) && isset($data['face_score']) && $data['face_score'] =='-1')
                                <b>Failure</b><br>
                                <b>face_score:</b> <b>-{{ number_format($data['face_score'], 1) }}%</b>    
                            @else
                                <b>Failure</b><br>
                                <b>face_score:</b> <b>{{$data['face_score']}}</b>
                            @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


    <div class="page-break"></div>
    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th>Speech Response</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td style="line-height: 2.5;">
                 
     
                   
                <p><b>Speech Response:</b></p> 
                <?php
                 $fontChosse = 'font-family:TimesNewRoman';
                 if($data->sys_lang!='eng')
                     $fontChosse = 'font-family:FreeSerif'; 
                 if($data->sys_lang=='kan' )
                  $fontChosse = 'font-family:kannada';
                  if($data->sys_lang=='tel' )
                  $fontChosse = 'font-family:telugu'; 
                  if($data->sys_lang=='ben' )
                  $fontChosse = 'font-family:bengali'; 
                  if($data->sys_lang=='ori' )
                  $fontChosse = 'font-family:oriya'; 
                  if($data->sys_lang=='tam' )
                  $fontChosse = 'font-family:tamil'; 
                  if($data->sys_lang=='guj' )
                  $fontChosse = 'font-family:gujarati'; 
                  if($data->sys_lang=='ass' )
                  $fontChosse = 'font-family:assam'; 
                  if($data->sys_lang=='mal' )
                  $fontChosse = 'font-family:malayalam'; 
                  
                
            // Decode the JSON stored in speech_response
            //$speechResponse = json_decode($data['speech_res'], true);
           //dd($speechResponse);
           // Check if it contains HTTP headers
           if (strpos($data['speech_res'], "\r\n\r\n") !== false) {
            // Split headers and body
            list(, $body) = explode("\r\n\r\n",$data['speech_res'], 2);
            } else {
                // It's plain JSON
                $body = $export->speech_res;
            }

        // Decode the JSON stored in speech_response
        $speechResponse = json_decode($body, true); //dd($speechResponse);

            if (($speechResponse === null || empty($speechResponse) )  ) {
                echo "<p>No speech response available.</p>";
            } else {
                // Extract values from the response
                $code = '';
                $match ='';
                $ref = '';
                $score ='';
                $stt ='';
                if(isset($speechResponse['code'])){
                    $code = $speechResponse['code'];
                }else{
                    $code = 'No code available';
                }

                if(isset($speechResponse['score'])){
                    $score = $speechResponse['score'];
                }else{
                    $score = 'No score available';
                }

                if(isset($speechResponse['match'])){
                    $match = $speechResponse['match'];
                }else{
                    $match = 'No match data available';
                }

                if(isset($speechResponse['ref'])){
                    $ref = $speechResponse['ref'];
                }else{
                    $ref = 'No reference text available';
                }

                if(isset($speechResponse['stt'])){
                    $stt = $speechResponse['stt'];
                }else{
                    $stt = 'No speech text available';
                }
               
             /*   $code = isset($speechResponse['code']) ? $speechResponse['code'] : 'No code available';
                $match = isset($speechResponse['match']) ? $speechResponse['match'] : 'No match data available';
                $ref = isset($speechResponse['ref']) ? $speechResponse['ref'] : 'No reference text available';
                $score = isset($speechResponse['score']) ? $speechResponse['score'] : 'No score available';
                $stt = isset($speechResponse['stt']) ? $speechResponse['stt'] : 'No speech text available';*/

                // Convert BBCode in 'ref' and 'stt' fields to HTML using the autoloaded function
               /* $formattedRef = bbcodeToHtml($ref);
                $formattedStt = bbcodeToHtml($stt);*/

                // Handle specific cases like code 500 and match false
                //dd($code, $match);
                if ($code == 500 && $match === false) { 
                    echo "<p><b>Status:</b> 500 - No match</p>";
                } elseif ($code == 200 && isset($speechResponse['ref'])) {
                    $ref = $speechResponse['ref'];
                    $ref  = str_replace('[b]','<b>',$ref);
                    $ref  = str_replace('[/b]','</b>',$ref);
                    $ref  = str_replace('[color=red]','<span style="color:red">',$ref);
                    $ref  = str_replace('[/color]','</span>',$ref);
                    $stt = $speechResponse['stt'];
                    $stt  = str_replace('[b]','<b>',$stt);
                    $stt  = str_replace('[/b]','</b>',$stt);
                    $stt  = str_replace('[color=red]','<span style="color:red">',$stt);
                    $stt  = str_replace('[/color]','</span>',$stt);
                   // $match = $speech_response['match'] ;
                    echo "<p><b>Code:</b> 200 - Match found</p>";
                    echo "<p><b>Score:</b> $score</p>";
                    echo "<p><b>Formatted Reference:</b></p>";
                    echo "<p style='$fontChosse'>$ref</p>"; // Render HTML
                    echo "<p><b>Speech Text:</b></p>";
                    echo "<p style='$fontChosse'>$stt</p>"; // Render HTML 
                    
                } else {
                    $ref = $speechResponse['ref'];
                    $ref  = str_replace('[b]','<b>',$ref);
                    $ref  = str_replace('[/b]','</b>',$ref);
                    $ref  = str_replace('[color=red]','<span style="color:red">',$ref);
                    $ref  = str_replace('[/color]','</span>',$ref);
                    $stt = $speechResponse['stt'];
                    $stt  = str_replace('[b]','<b>',$stt);
                    $stt  = str_replace('[/b]','</b>',$stt);
                    $stt  = str_replace('[color=red]','<span style="color:red">',$stt);
                    $stt  = str_replace('[/color]','</span>',$stt);
                    // Default case for other responses
                    echo "<p><b>Code:</b> $code</p>";
                    echo "<p><b>Match:</b> $match</p>";
                    echo "<p><b>Formatted Reference:</b></p>";
                    echo "<p style='$fontChosse'> $ref </p>"; // Render HTML
                    echo "<p><b>Speech Text:</b></p>";
                    echo "<p style='$fontChosse'>$stt</p>"; // Render HTML
                }
            }
            ?>
            </td>
        </tr>
        </tbody>
        <thead style="background-color: lightgray;">
      <tr>
        <!-- <th>#</th> -->
        <th>CONSENT VIDEO</th>
        <!-- <th>Quantity</th> -->
      </tr>
    </thead>
    <tbody>
      <tr>
        <!-- <th scope="row">4</th> -->

        <?php 
        $video_url = $data['video'];//dd($video_url);
        //$public_path = "D:";
        //$url = $public_path.'/upload/' . $applicationNumber . '/vid/consent.webm';
        
        //$path = public_path('upload/' . $applicationNumber . '/vid/consent.webm');
        //$video_url = file_exists($path) ? asset('upload/' . $applicationNumber . '/vid/consent.webm') : null;
        
         if(isset($version) && $version!=''){ 
            $video_url = str_replace($applicationNumber,$applicationNumber.'-'.$version,$video_url);
         }else{
            $video_url = str_replace($applicationNumber.'-1',$applicationNumber,$video_url);
         }

         ?>

        <td style="line-height: 0.5;"> 
        <p><b>Video Link :</b> <a target="_blank" href="{{ url('api/downloadfile/' . base64_encode($applicationNumber)) }}" class="clrtxt">Click here</a></p>
      </td>
      </tr>
    </tbody>
    </table>
</body>
</html>
