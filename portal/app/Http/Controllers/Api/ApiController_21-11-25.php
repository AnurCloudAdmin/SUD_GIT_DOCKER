<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Link;
use App\Models\Linksarchive;
use App\Models\Product;
use App\Models\Logs;
use App\Models\Fedologs;
use App\Models\FailedProductLogs;
use File;
use PDF;
use Mpdf\Mpdf;
use Mail;
use Excel;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use App\Exports\CompleteList;
use App\Exports\NotComExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\CURLFile;
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\EncryptionCTRController;
use App\Http\Controllers\Api\ComputeHashFromJSON;
use App\Imports\YourImport;
use finfo;
use ZPDF;
use Barryvdh\Snappy\Facades\SnappyPdf as BPDF;
use App\Http\Controllers\HomeController; 

use App\Imports\ImageNumbersImport;
//use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Facades\File;

// use Jenssegers\Agent\Facades\Agent;

class ApiController extends Controller
{

  public $main_url;
  public $enc;
  public $secret_key;

  public $generatedLinks = [];

  public function __construct()
  {
    $this->secret_key = '1234567890123456';
    $this->enc = new EncryptionController;

    $this->secret_upload_key = '1234567890123456';
    $this->encUpload = new EncryptionUploadController;

    //https://eadvapgt.sudlife.in/vc/sudlife/portal/api/addImage

    //$this->main_url = 'https://vbawcwi.sudlife.in/vc/welcome';   //'https://VBAWCWi.sudlife.in/vc/welcome';  
         //https://eadvapgt.sudlife.in/vc/sudlife/  https://vbawcwiuat.sudlife.in/sudlife/welcome
    //$this->file_url =  'https://vbawcwin.sudlife.in';                  //'https://VBAWCWi.sudlife.in/vc/portal';      
     //https://eadvapgt.sudlife.in/vc/sudlife/portal https://vbawcwiuat.sudlife.in/sudlife/portal

    $this->main_url = 'https://dev.anurcloud.com/sudlife';          //https://eadvapgt.sudlife.in/vc/sudlife/portal/api/addImage
    $this->file_url = 'https://dev1.anurcloud.com/sudlife/portal';

    $this->uaturl = 'https://vbawcwiuat.sudlife.in/vc/portal/public/';

    //$this->produrl = 'https://vbawcwi.sudlife.in/vc/portal/public/';
  }
 
  public function test(){
    
    

      $curl = curl_init();
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sud.life/api/get/url',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "sourcename": "AnurClud",
        "longUrl": "https://www.sudlife.in",
        "expirydays": "90"
      }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      echo $response;
      
        
    
    die;
  }

  public function testdecryptApi(Request $request)
  {
    $jsonData = $request->all();
    $jsonResult =  $jsonData['encrypted'];
    return $dd =  APIController::decrypt($jsonResult);
    //return $dd =  APIController::encrypt($jsonResult);
  }

  public function testApi(Request $request)
  {
    //dd(public_path());
//     //$i =0;
    
//     $path = public_path('upload/test/sud_life.mp3');
//     $file = new \CURLFile($path);
//     // echo $path;die;

// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'http://192.168.1.244:5002',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS => array('application_number'=>555555,'video_blob'=>$file,'lang'=>'english','name'=>'kumar'),  //new CURLFILE($path)
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// //echo $i+1;
// print_r($response);
// die;

    $key = '1234567890123456'; // 16-byte key 1234567890123456
    $jsonData = json_encode($request->all());

    //dd($request->all());
    
    $encrypted = EncryptionController::encrypt($jsonData, $key);
    return $encrypted;
   // $decrypted = EncryptionController::decrypt($encrypted, $key);
    //echo "<br/><br/><br/>";
    //echo $decrypted;
    
  }

  public function testuploadApi(Request $request)
  {
//     //$i =0;
    
//     $path = public_path('upload/test/sud_life.mp3');
//     $file = new \CURLFile($path);
//     // echo $path;die;

// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'http://192.168.1.244:5002',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS => array('application_number'=>555555,'video_blob'=>$file,'lang'=>'english','name'=>'kumar'),  //new CURLFILE($path)
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// //echo $i+1;
// print_r($response);
// die;

    $key = '1234567890123456'; // 16-byte key 1234567890123456
    $jsonData = json_encode($request->all());

    //dd($request->all());

    $partnerDetails = $request->input('PartnerDetails');

    $partnerName  = $partnerDetails['Partner'] ?? null;
    $partnerCode  = $partnerDetails['PartnerCode'] ?? null;
    
    $encrypted = EncryptionUploadController::encrypt($jsonData, $partnerName, $partnerCode);
    return $encrypted;
    $decrypted = EncryptionUploadController::decrypt($jsonData, $partnerName, $partnerCode);
    echo "<br/><br/><br/>";
    echo $decrypted;
    
  }
  

  public function encrypt($data)
  {
    $nonceValue = $this->secret_key;
    $encrypted = $this->enc->encrypt($data, $nonceValue);
    return $encrypted;
  }

  public function decrypt($data)
  {
    $nonceValue = $this->secret_key;
    $decrypted = $this->enc->decrypt($data, $nonceValue);
    return $decrypted;
  }

  public function logs($proposal_no, $mod, $req, $res, $status)
  {
    $log = new Logs;
    $log->app_no = $proposal_no;
    $log->module = $mod;
    $log->request = $req;
    $log->response = $res;
    $log->status = $status;
    $log->save();
    //dd($log);
    return;
  }
  public function fedologs($proposal_no, $mod, $req, $res, $status)
  {
    $log = new Logs;
    $log->app_no = $proposal_no;
    $log->module = $mod;
    $log->request = $req;
    $log->response = $res;
    $log->status = $status;
    $log->save();
    //dd($log);
    return;
  }

  public function failedProductLogs($proposal_no, $product_name, $plan_name)
  {
    $log = new FailedProductLogs;
    $log->app_no = $proposal_no;
    $log->product_name = $product_name;
    $log->plan_name = $plan_name;
    $log->save();
    //dd($log);
    return;
  }

  public function DownloadPdf($proposal)
  {
    //dd($proposal);

    $name = $proposal . '.pdf';
    $path = public_path('upload/' . $proposal . '/') . $name;
    $links = Link::where('proposal_no', $proposal)->get();
    $data = $links[0];
    $fedo_details  = Fedologs::where('app_no', $proposal)->latest()->first();
    // dd($fedo_details);
    if (!empty($fedo_details)) {
      $fedo = json_decode($fedo_details['request'], true);
    } else {
      $fedo = '';
    }
    $pdf = '';

    $location = json_decode($data['location'], true);//dd($location);
    $longitude = $location['lat'];
    $latitude = $location['long'];
    $address_disp = '';
    $city = '';
    // if ($latitude != '' || $longitude != '') {




    //   $curl = curl_init();

    //   curl_setopt_array($curl, array(
    //     CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyBPnS82bRgH3-yYqK_-ikTWzKqS5P5n63g",
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'GET',
    //   ));

    //   $response = curl_exec($curl);

    //   curl_close($curl);
    //   $response = json_decode($response);
    //   if (isset($response->results[0])) {
    //     $address = $response->results[0]->formatted_address;
    //     $address_disp = $address;
    //   }
    // }

    $html = view('genpdf', compact('data', 'address_disp', 'city'))->render();

    //dd($html);
    // Initialize mPDF
    $mpdf = new mpdf();

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Define file path
    //$path = storage_path($path);

    // Save PDF file
    File::put($path, $mpdf->Output('', 'S')); // 'S' returns PDF as a string

    // Retrieve the file
    return response()->file($path);


    //$pdf = PDF::loadView('genpdf', compact('fedo', 'data', 'address_disp', 'city'));

    //return $pdf->stream($name);
  }


  public function DownloadPdfArchive($proposal, $version)
  {

    $downloadPdf = ApiController::getPDFdownload($proposal);

    $filepath ='';

    if($downloadPdf){
              if(isset($downloadPdf)){
          
                $decoderesponse = json_decode($downloadPdf);

                //dd($decoderesponse);

                if(isset($decoderesponse->path)){
 
                    $fullPath = $decoderesponse->path;

                   // dd($fullPath);

                   $filepath = str_replace('D:\\vc\\portal\\public\\', '', $fullPath);

                   $relativePath = str_replace('\\', '/', $filepath);

                   $filepath = $this->uaturl.$relativePath; //dd($filepath);

                   ApiController::logs($proposal,'GetPDFPathApi', '',json_encode($decoderesponse->path), "success");

                   $filename = $proposal;
                   return redirect()->away($filepath);

                }
                else if(isset($decoderesponse->error) || $decoderesponse==null){ //dd('dfdf');

    $name = $proposal . '.pdf';
    $path = public_path('upload/' . $proposal . '/') . $name;
    $links = Linksarchive::where('proposal_no', $proposal)->where('version', $version)->get();
    $data = $links[0];
    $pdf = '';

    $location = json_decode($data['location'], true);
    $longitude = $location['lat'];
    $latitude = @$location['long'];
    $address_disp = '';
    $city = '';
    // if ($latitude != '' || $longitude != '') {
    //   //echo "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyBPnS82bRgH3-yYqK_-ikTWzKqS5P5n63g";die;

    //   $curl = curl_init();

    //   curl_setopt_array($curl, array(
    //     CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyBPnS82bRgH3-yYqK_-ikTWzKqS5P5n63g",
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'GET',
    //   ));

    //   $response = curl_exec($curl);

    //   curl_close($curl);
    //   $response = json_decode($response);
    //   if (isset($response->results[0])) {
    //     $address = $response->results[0]->formatted_address;
    //     $address_disp = $address;
    //   }
    // }

    $html = view('genpdf', compact('data', 'address_disp', 'city','version'))->render();

    //dd($html);
    // Initialize mPDF
    $mpdf = new mpdf();

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Define file path
    //$path = storage_path($path);

    // Save PDF file
    File::put($path, $mpdf->Output('', 'S')); // 'S' returns PDF as a string

    // Retrieve the file
    return response()->file($path);

    // $pdf = PDF::loadView('genpdf', compact('data', 'address_disp', 'city'));

    // return $pdf->stream($name);
                }
              }
            }
  }

  public function msg($status, $msg)
  {
    $array = array('Status' => $status, 'Message' => $msg);
    $enc_data = json_encode($array, JSON_FORCE_OBJECT);
    //return $enc_data;
    return APIController::encrypt($enc_data);
  }

  public function linkgen($url)
  {
    $encode = EncryptionCTRController::encrypt($url,$this->secret_key);
    $url = $this->main_url . '/index.html?' . $encode;
    // $url='https://office.anoorcloud.in/choice/index.html?'.$encode;
    return $url;
  }

  public function linkgenApi($urlnum)
  {

   
    $encode = EncryptionCTRController::encrypt(trim($urlnum),$this->secret_key);
    $longurl = $this->main_url . '/index.html?' . $encode;
    // $url='https://office.anoorcloud.in/choice/index.html?'.$encode;

    

    $shorturl = ApiController::shortenTiny(trim($longurl)); //dd($shorturl, trim($urlnum));

    Link::where('proposal_no', trim($urlnum))->update(['url' => $longurl, 'short_link' => $shorturl]);

    //return $url;
  }


  public function shorten($url)
  {
    // Commented as it says MONTHLY_RATE_LIMIT_EXCEEDED
    //$data=file_get_contents('https://api-ssl.bitly.com/v3/shorten?access_token=d053e9018cdc9ef3e6930405fc4d15b8f279224d&longUrl='.$url);
    //$obj=json_decode($data);
    //return $obj->data->url;

    $data = array('api_key' => '6a748b-84f8bc-461d9a-dc658e-cc365e', 'url' => $url);
    $ch = curl_init('https://anoor.link/portal/api/shorten');
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($ch);
    $obj = json_decode($result);
    return $obj->url;
  }



  public function shortenTiny($url)
  {

    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //   CURLOPT_URL => 'http://tinyurl.com/api-create.php?url=' . $url,
    //   CURLOPT_RETURNTRANSFER => true,
    //   CURLOPT_ENCODING => '',
    //   CURLOPT_MAXREDIRS => 10,
    //   CURLOPT_TIMEOUT => 0,
    //   CURLOPT_FOLLOWLOCATION => true,
    //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //   CURLOPT_CUSTOMREQUEST => 'GET',
    // ));

    // $response = curl_exec($curl);
    // curl_close($curl);
    // return $response;
    
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://sud.life/api/get/url',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "sourcename": "AnurClud",
  "longUrl": "'.$url.'",
  "expirydays": "90"
}',
CURLOPT_SSL_VERIFYPEER =>false,
CURLOPT_SSL_VERIFYHOST =>false,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);



curl_close($curl);
$res_decode = json_decode($response,true);//dd($res_decode);
if(isset($res_decode['ShortlUrl']) && $res_decode['ShortlUrl']!=''){
  return  $res_decode['ShortlUrl'];
}else{
  return '';
}

  }

  public function easyname($word)
  {
    $word = str_replace('- ', '', $word);
    $word = str_replace(' ', '_', $word);
    return $word;
  }

  public function SFTPUpload()
  {
    // https://office.anoorcloud.in/choice/portal/api/SFTPUpload
    // Storage::disk('sftp')->delete('test/logo.png');
    // exit;

    //  $fileContents=public_path('images/logo.png');
    //  $sftp_content='test/logo.png';
    //  Storage::disk('sftp')->put($sftp_content,$fileContents);
    //  echo "Hello"; exit;
    $data = Transaction::where('complete_status', '1')->where('sftp_status', 0)->get();

    foreach ($data as $res) {
      $proposal_no = $res->proposal_no;
      $dt = date('d_m_Y', strtotime($res->completed_on));
      $video = public_path('upload/' . $proposal_no . '/vid/');
      $pdf = public_path('upload/' . $proposal_no . '/pdf/');
      $vid = scandir($video);
      unset($vid[0], $vid[1]);

      foreach ($vid as $row2) {
        $name = $row2;
        $fileContents = $video . $name;
        $sftp_content = $dt . '/' . $proposal_no . '/' . ApiController::easyname($name);
        Storage::disk('sftp')->put($sftp_content, $fileContents);
      }
      $name = $proposal_no . '.pdf';
      $fileContents = $pdf . $name;
      $sftp_content = $dt . '/' . $proposal_no . '/' . $name;
      Storage::disk('sftp')->put($sftp_content, $fileContents);
      Transaction::where('proposal_no', $proposal_no)->update(['sftp_status' => 1]);
      //exit;
    }
  }

  
  public function base64_to_jpeg_old($base64_string, $output_file)
  {
    //echo $base64_string;die;
    $ifp = fopen($output_file, 'wb');
    /*$data = explode( ',', $base64_string );
      if(isset( $data[ 1 ])){
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
      }*/

    if (isset($base64_string)) {
      fwrite($ifp, base64_decode($base64_string));
    }

    fclose($ifp);
    return $output_file;
  }

public function base64_to_jpeg($base64_string, $output_file)
{
    // Remove possible data URL scheme
    if (preg_match('/^data:image\/\w+;base64,/', $base64_string)) {
        $base64_string = preg_replace('/^data:image\/\w+;base64,/', '', $base64_string);
    }

    // Clean base64 string
    $base64_string = str_replace(' ', '+', $base64_string); // Sometimes + is turned into space

    // Decode the base64 string
    $imageData = base64_decode($base64_string);

    // Validate that decoding worked
    if ($imageData === false || strlen($imageData) < 1000) {
        return 'Failed to decode or image too small';
    }

    // Save to file
    $success = file_put_contents($output_file, $imageData);

    if ($success === false) {
        return 'Failed to write image';
    }

    return $output_file;
}



  public function base64_to_jpegFrontend($base64_string, $output_file)
  {
    $ifp = fopen($output_file, 'wb');
    $data = explode(',', $base64_string);
    if (isset($data[1])) {
      fwrite($ifp, base64_decode($data[1]));
    }

    /* if(isset( $base64_string)){
      fwrite( $ifp, base64_decode( $base64_string ) );
    }
   */
    fclose($ifp);
    return $output_file;
  }

  public function validatePIVCLink(Request $request)
  {

    $arr = [];
    $countrycode = '+91 ';
    try {

      $post = $request->all();  
      $url = $post['pivc_url'];//dd($url);
      $device_name = $post['device_name'];
      $nettype = $post['nettype'];
      $netrtt = $post['netrtt'];
      $netdown = $post['netdown'];
      $network = array('device_name' => $device_name, 'type' => $nettype, 'rtt' => $netrtt, 'downlink' => $netdown);
      if(strlen($url) > 120){
        $proposal = decrypt(explode('?', $url)[1]); //dd($proposal);
      }else{
        //echo "hi";die;
        $proposal = EncryptionCTRController::decrypt(explode('?', $url)[1],$this->secret_key);
      }

      
      ApiController::logs($proposal, 'validatePIVCLinkSuccess', json_encode($post), '', "Success");

      $agent = ApiController::agent();
      $ip_address = request()->ip();
      $links = Link::where('proposal_no', $proposal)->first();
      
      Link::where('proposal_no', $proposal)->update(['device' => $agent, 'network' => json_encode($network), 'ip_address' => $ip_address]);

        if ($links['is_open'] == 0) {
        Link::where('proposal_no', $proposal)->update(['is_open' => 1, 'is_open_at' => date('Y-m-d H:i:s')]);
      }

      if (!empty($links) and $links['status'] == 1 and $links['complete_status'] == 0) {
        $arr = array();
        $arr['status'] = true;
        $arr['expired'] = false;
        $arr['completed'] = false;

        $arr['msg'] = 'Given PIVC URL is valid!';

        Link::where('proposal_no', $proposal)->update(['images' => null]);
        //$params = json_decode($links['params']);

        $params = json_decode($links['params'],true); 
        $params['plan_details']['Sum_Assured'] = (isset($params['plan_details']['Sum_Assured']) && $params['plan_details']['Sum_Assured']!='') ? ApiController::IND_money_format($params['plan_details']['Sum_Assured']) : 0;
        $params['plan_details']['Premium_Amount'] = (isset($params['plan_details']['Premium_Amount']) && $params['plan_details']['Premium_Amount']!='') ? ApiController::IND_money_format($params['plan_details']['Premium_Amount']) : 0;

        $Rider_details = [];
        if(isset($params['plan_details']['Rider_details']) && !empty($params['plan_details']['Rider_details'])){
          foreach($params['plan_details']['Rider_details'] as  $key=>$value){
            $params['plan_details']['Rider_details'][$key]['Rider_Sum_Assured'] = ApiController::IND_money_format($params['plan_details']['Rider_details'][$key]['Rider_Sum_Assured']);
          }
        }
        else{
          //$params['plan_details']['Rider_details']=null;
          $params['plan_details']['Rider_details'][0]['Rider_name']="";
          $params['plan_details']['Rider_details'][0]['Rider_Sum_Assured']="";
        }
        if(!isset($params['Nominee_details']) && empty($params['Nominee_details'])){
          //$params['Nominee_details']=null;
          $params['Nominee_details'][0]['Nominee_name']="";
          $params['Nominee_details'][0]['Nominee_dob']="";
        }

        $params['Mobile_number'] = $countrycode.$params['Mobile_number'];
        $arr['params']=$params;
        Link::where('proposal_no', $proposal)->update(['link_attempt_count' => ($links->link_attempt_count + 1)]);
        
       // Link::where('proposal_no', $proposal)->update(['link_attempt_count' => ($links->link_attempt_count + 1),'curr_openstatus'=>Carbon::now()->toDateTimeString()]);
        /*browserupdate */

      $agent = ApiController::agent();

      $agentval = json_decode($agent, true);
      $agentval_os = strtolower($agentval["os"]);
      $browser = "";

      if ($agentval_os == "ios" || $agentval_os == "os x") {
        $device_os = $agentval_os;
        $browser_details = $request->header('User-Agent');
        if (str_contains($browser_details, "Safari")) {
          $device_os = $agentval_os;
          $browser = "Safari";
        }  else {
          $device_os = $agentval_os;
          $browser = "Invalid";
        }
        ApiController::logs($proposal, $browser . ' Browser', $browser_details, '', "Success");
      } elseif ($agentval_os == "androidos" || $agentval_os == "windows") {
        //echo "else and";die;
        $device_os = $agentval_os;
        $browser_details = $request->header('sec-ch-ua');
        if (str_contains($browser_details, "Google Chrome")) {
          $browser = "Chrome";
        }else if (str_contains($browser_details, "Microsoft Edge")) {
          $browser = "Microsoft Edge";
        } else {
          $device_os = $agentval_os;
          $browser = "Invalid"; 
        }
        ApiController::logs($proposal, $browser . ' Browser', $browser_details, '', "Success");
      } else {
        $device_os = $agentval_os;
        $browser = "Invalid";
        ApiController::logs($proposal, $agentval_os . ' OS', $browser, '', "Success");
      }
      
        /*browserupdate*/
        // ApiController::logs($proposal,'validatePIVCLink',json_encode(($post)),$result, "Success");
      }  elseif ($links['complete_status'] == 1) {
        $arr = array();
        $arr['status'] = true;
        $arr['expired'] = false;
        $arr['completed'] = true;
        $arr['msg'] = 'Given PIVC already Completed';
        //$arr['output'] = $params;
       // $arr['completed_at'] = date('d-M-Y H:i', strtotime($links['completed_on));
        $result = json_encode($arr);
      }
      elseif ($links['status'] == 0) {
        $arr = array();
        $arr['status'] = false;
        $arr['expired'] = true;
        $arr['completed'] = false;
        $arr['msg'] = 'Given PIVC URL is Invalid!';
        // Log related codes
        $result = json_encode($arr);
        
        ApiController::logs($proposal,'validatePIVCLink',json_encode(($post)),$result, "Fail");

      }

        $enc_data = json_encode($arr, JSON_FORCE_OBJECT);
        return $enc_data;
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($proposal, 'createPIVCLinkFailed', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }


  public function disagreeScreen(Request $request)
  {
    // echo "test"; exit();
    //$post = APIController::decrypt($request->data);
    //$post = json_decode($post);
    //$post = APIController::decrypt('HWIAbMjuViIfyblHuZISVNbylWP6+kp0aaTa4s3+fwUNp0YQkEob7yC+kwG6q/PGtCa7QH79WyiICaHZBAmagthTkKLu8WLe///wXTManiltk=');dd($post);
    $post = $request->all();//dd($post['application_number']);
    try {


      //$details=$request->all();
      $screen = $post['screen'];
      $disagree_data = $post['disagree_data']; 
      $proposal = $post['application_number'];
      $status = $post['status'];
      $flagstatus = $post['Medical_Flag'];
      //dd($screen,$status);
      ApiController::logs($proposal, $status.'ScreenStart', json_encode($post), "", "Success");

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      $links->medical_checked_response = $flagstatus;
      if ($links != '') {
        $req = array();

        if ($screen == 'personal') {
          $disagree_data_dcode = json_decode($disagree_data, true);
          if($status=='disagree'){  
            $disagree_data_dcode['screen'] = $screen;
            $disagree_data_dcode['status'] = $status;
            $links->personal_disagree = 1;
          $links->personal_disagree_response = json_encode($disagree_data_dcode); //personal_disagree_response
          }
          if($status=='agree'){
            $disagree_data_dcode['screen'] = $screen;
            $disagree_data_dcode['status'] = $status;
            $links->personal_disagree = 0;
            $links->personal_agree_response = json_encode($disagree_data_dcode); //personal_disagree_response
          }
          ApiController::logs($proposal, 'personalScreenStart', "", "", "Success");
        }
        if ($screen == 'policy') {
          $disagree_data_dcode = json_decode($disagree_data, true);
          if($status=='disagree'){
          $disagree_data_dcode['screen'] = $screen;
          $disagree_data_dcode['status'] = $status; 
          $links->policy_disagree = 1; 
          // $amount = '₹5,00,000';

          
          // $cleanAmount = str_replace('₹', '', $amount);   // Remove the rupee symbol

          // echo "Clean amount: " . $cleanAmount;die;
          $disagree_data_dcodeval = $this->removeRupeeSymbol($disagree_data_dcode); //7-5-25

         // dd($disagree_data_dcodeval);
          
          $links->policy_disagree_response = json_encode($disagree_data_dcodeval); 
          $links->agree = 'disagree';      //customer policy disagree statement
          //policy_disagree_response
          }
          if($status=='agree'){
            $links->policy_disagree = 0;
            $disagree_data_dcode['screen'] = $screen;
            $disagree_data_dcode['status'] = $status;  
            $links->policy_agree_response = json_encode($disagree_data_dcode); 
            $links->agree = 'agree';  //customer policy agree statement
            //policy_disagree_response
          }
          ApiController::logs($proposal, 'policyScreenStart', "", "", "Success");
        }

          //dd($links);
        $links->save();
        $arr = array('status' => 'Success', 'msg' => $status.'Status Added.');
        //echo ApiController::msg("Success", 'Disagree Status Added.');
        return json_encode($arr);

      } else {

        //echo MainController::msg('Failed', 'Links not available');
        //$arr = array('status' => 'Failed');
        $arr = array('status' => 'Success', 'msg' => $status.'Status Failed.');
        return json_encode($arr);

      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post['application_number'], 'disagreeScreenFailed', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }


  public function feedback(Request $request)
  {
    // echo "test"; exit();
    //$post = APIController::decrypt($request->data);
    //$post = json_decode($post);
    $post = $request->all();//dd($post->application_number);
    try {


      //$details=$request->all();
      ApiController::logs($post['application_number'], 'feedback started', json_encode($post), "", "Success");

      $category = $post['category'];
      $feedback = $post['feedback']; 

      $proposal = $post['application_number'];

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      if ($links != '') {

          //$links->agree_data_policy = $agree_data; //policy_agree_response
          $links->feedback = json_encode($post); //policy_agree_response


        $links->save();
        $arr = array('status' => 'Success');
        //echo ApiController::msg("Success", 'agree Status Added.');
        return json_encode($arr);

      } else {

        //echo MainController::msg('Failed', 'Links not available');
        $arr = array('status' => 'Failed');
        return json_encode($arr);

      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post['application_number'], 'feedback failed', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }

  public function location(Request $request)
  {
    // echo "test"; exit();
    //$post = APIController::decrypt($request->data);
    //$post = json_decode($post);
    $post = $request->all();//dd($post->application_number);
    try {


      //$details=$request->all();
      ApiController::logs($post['application_number'], 'location capture started', json_encode($post), "", "Success");

      $lat = $post['lat'];
      $long = $post['long']; 
      $syslang  = $post['syslang'];

      $proposal = $post['application_number'];

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      if ($links != '') {

          //$links->agree_data_policy = $agree_data; //policy_agree_response
          if (isset($post['long'])) {     $post['_long'] = $post['long']; 
            unset($post['long']);
            }
          $links->location = json_encode($post); //policy_agree_response
          $links->sys_lang = $syslang;

         

          //dd($links);

        $links->save();

        Link::where('proposal_no', $proposal)->update(['curr_openstatus'=>Carbon::now()->toDateTimeString()]); //9-10-25

        $arr = array('status' => 'Success');
        //echo ApiController::msg("Success", 'agree Status Added.');
        return json_encode($arr);

      } else {

        //echo MainController::msg('Failed', 'Links not available');
        $arr = array('status' => 'Failed');
        return json_encode($arr);

      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post['application_number'], 'location failed', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }

  public function medicalAgree(Request $request)
  {
    // echo "test"; exit();
    //$details=$request->all();   
    $post = APIController::decrypt($request->data);
    $post = json_decode($post);
    try {

      ApiController::logs($post->proposal_no, 'medicalAgreeStart', json_encode($post), " ", "Success");

      $agree_response = (isset($post->agree_response)) ? json_encode(explode(',', $post->agree_response)) : NULL;
      $proposal = $post->proposal_no;

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      if ($links != '') {
        $links->medical_checked_response = $agree_response;
        $links->save();
        $arr = array('status' => 'Success');
        echo ApiController::msg("Success", 'Disagree Status Added.');
        // return json_encode($arr); 
      } else {

        echo MainController::msg('Failed', 'Links not available');
        $arr = array('status' => 'Failed');
        // return json_encode($arr); 
      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post->proposal_no, 'medicalAgreeFailed', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }

  // CHECK STATUS
  public function checkStatus_old(Request $request)
  {
    $reqst = $request->all();
    $jsonString = APIController::decrypt($reqst['ReqPayload']);  

    //$post = explode(",", $jsonString);

    $details = json_decode($jsonString, true);  //print_r($post);die;
    if(!empty($details['Application_Number'])){ 
    $proposal = $details['Application_Number'];
    $links = Link::where('proposal_no', $proposal)->first();

    if ($links->complete_status == 0) {
      $complete_status =  "Fail";
    } else{
      $complete_status =  "Pass";
    }

    //dd($links);

   // $speechres = json_decode($links->speech_res);

     // Check if it contains HTTP headers
            if (strpos($links->speech_res, "\r\n\r\n") !== false) {
                // Split headers and body
                list(, $body) = explode("\r\n\r\n",$links->speech_res, 2);
            } else {
                // It's plain JSON
                $body = $links->speech_res;
            }

            $speechres = json_decode($body, true);

            //dd($speechres['score']);

    if (!empty($links)) {

      if ($links->complete_status == 1) {

        if ($links->personal_disagree == 0 || $links->personal_disagree == 1 && $links->policy_disagree == 0 || $links->policy_disagree == 1) {
        //$callbackresult = $this->callbackurl($proposal);

          $imagePath = public_path('upload/' . $proposal . '/img/video_consent.jpeg');
          $imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);

          $jsondata['Face_Match'] = $links->face_response==null || $links->face_score<30 ? "Failure" : "Success";
          $jsondata['Face_Match_Per'] = $links->face_score==null ? 0 : (number_format($links->face_score, 1) . '%');
          $jsondata['Speech_Text_Match'] = isset($speechres['match'])?$speechres['match']=="true"?"Yes":"No":null;
          $jsondata['Agreement_Status'] = ($links->policy_disagree==0 && $links->personal_disagree==0)?"Yes":"No";
          $jsondata['Location'] = json_decode($links->location, true);
          $jsondata['Personal_Details_Disagreement'] = isset($links->nominee_disagree) ? 'Disagree':null; 
          $jsondata['Plan_Details_Disagreement'] = isset($links->rider_disagree) ? 'Disagree':null;
          //$Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?'Disagree':null;
          //$Health_Medical_Disagreement = isset($link['medical_checked_response'])?$link['medical_checked_response']:null;
          $jsondata['Attempts'] = $links->link_attempt_count;
          $jsondata['Timestamp'] = $links->completed_on;
          $jsondata['Final_Result'] = $complete_status;

          $jsondata['bawc_imgBase64'] =  $imageData; //dd($jsondata);

      if($links->personal_disagree == 0 && $links->policy_disagree == 0){
         $jsonResult = json_encode(['status' => true,  'pivc_status' => "COMPLETE", "Application_Number" => $proposal,"finalresult" => json_encode($jsondata)], 200);
      }
       if($links->personal_disagree == 1 || $links->policy_disagree == 1){
        $jsonResult = json_encode(['status' => true,  'pivc_status' => "COMPLETE With Discrepency", "Application_Number" => $proposal,"finalresult" => json_encode($jsondata)], 200);
       }
      
        return $jsonResult;
        
        } else {
          if($links->personal_disagree == 1 || $links->policy_disagree == 1){
            $jsonResult = json_encode(['status' => true,  'pivc_status' => "Opened - Pending For Completion"], 200);
          }
          else{
             $jsonResult = json_encode(['status' => true,  'pivc_status' => "Opened - Pending For Completion"], 200);
          }
          
          return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
        }
      } else if ($links->complete_status == 0) {
        $jsonResult = json_encode(['status' => true,  'pivc_status' => "Not Opened"], 200);
        return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
      }
    } else {
      $jsonResult = json_encode(['status' => false, 'error_code' => 701,  'message' => "Invalid Application Number."], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    }
  }
  else{
    $jsonResult = json_encode(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
    return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
  }
  }

  public function checkStatus(Request $request)
  {

     $reqst = $request->all();
    $jsonString = APIController::decrypt($reqst['ReqPayload']);  

    //$post = explode(",", $jsonString);

    $details = json_decode($jsonString, true);  //print_r($post);die;
    if(!empty($details['Application_Number'])){ 
        $proposal = $details['Application_Number'];

         $links = Link::where('proposal_no', $proposal)->first();

        if ($links->complete_status == 0) {
          $complete_status =  "Fail";
        } else{
          $complete_status =  "Pass";
        }

    //dd($links);

   // $speechres = json_decode($links->speech_res);

     // Check if it contains HTTP headers
            if (strpos($links->speech_res, "\r\n\r\n") !== false) {
                // Split headers and body
                list(, $body) = explode("\r\n\r\n",$links->speech_res, 2);
            } else {
                // It's plain JSON
                $body = $links->speech_res;
            }

            $speechres = json_decode($body, true);

            //dd($speechres['score']);

          if (!empty($links)) {

            if ($links->complete_status == 1) {

                $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');

                if(!file_exists($imagePath)){

                                $fullPath = APIController::getDocsdownloadApi($proposal);  
                          
                                  //$fullPath = $decoderesponse->path;
                                // Example: D:\vc\portal\public\upload\53923254

                                // Remove only the public base folder
                                $relative = str_replace('D:\\vc\\portal\\public\\', '', $fullPath);

                                // Convert to URL-safe slashes
                                $relative = str_replace('\\', '/', $relative);

                                // Append image
                                $relative = rtrim($relative, '/') . '/img/video_consent.jpeg';

                                // Build final URL
                                $imagePath = rtrim($this->uaturl, '/') . '/' . ltrim($relative, '/');
                }else{
                  $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');
                }

                    $imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);

                      //die;

              $jsondata['Face_Match'] = $links->face_response==null || $links->face_score<30 ? "Failure" : "Success";
              $jsondata['Face_Match_Per'] = $links->face_score==null ? 0 : (number_format($links->face_score, 1) . '%');
              $jsondata['Speech_Text_Match'] = isset($speechres['match'])?$speechres['match']=="true"?"Yes":"No":null;
              $jsondata['Speech_Score'] = isset($speechres['score'])?$speechres['score']:null;
              $jsondata['Agreement_Status'] = ($links->policy_disagree==0 && $links->personal_disagree==0)?"Yes":"No";
              $jsondata['Location'] = json_decode($links->location, true);
              $jsondata['Personal_Details_Disagreement'] = isset($links->nominee_disagree) ? 'Disagree':null; 
              $jsondata['Plan_Details_Disagreement'] = isset($links->rider_disagree) ? 'Disagree':null;
              //$Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?'Disagree':null;
              //$Health_Medical_Disagreement = isset($link['medical_checked_response'])?$link['medical_checked_response']:null;
              //$jsondata['Attempts'] = $links->link_attempt_count;
              $jsondata['Timestamp'] = $links->completed_on;
              $jsondata['Final_Result'] = $complete_status;

              $jsondata['bawc_imgBase64'] =  $imageData; //dd($jsondata);

              if($links->personal_disagree == 0 && $links->policy_disagree == 0 && $links->face_score>=30 && $speechres['score']>=65){
          $jsonResult = json_encode(['status' => true,  'pivc_status' => "Journey Completed - Success", "Attempted" =>$links->link_attempt_count, "Application_Number" => $proposal,"finalresult" => json_encode($jsondata)], 200);
              }else  if($links->personal_disagree == 1 || $links->policy_disagree == 1){
          $jsonResult = json_encode(['status' => true,  'pivc_status' => "Journey Completed - Discrepency", "Attempted" =>$links->link_attempt_count,  "Application_Number" => $proposal,"finalresult" => json_encode($jsondata)], 200);
            }else{
                $jsonResult = json_encode(['status' => true,  'pivc_status' => "Journey Completed - Failure - " .$links->link_attempt_count, "Application_Number" => $proposal,"finalresult" => json_encode($jsondata)], 200);
                  }
                }else if ($links->complete_status == 0) {
                  if($links->is_open == 1){
                    if($links->personal_disagree == 1 || $links->policy_disagree == 1){
                        $jsonResult = json_encode(['status' => true,  'pivc_status' => "Journey Pending - Disagreement recorded"], 200);
                    }else{
                          $jsonResult = json_encode(['status' => true,  'pivc_status' => "Journey Pending"], 200);
                    }
                  }else{
                    $jsonResult = json_encode(['status' => true,  'pivc_status' => "Journey Not Opened"], 200);
                  }
                }
                //return $jsonResult;
                //dd($jsonResult);
            return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
          }else {
              $jsonResult = json_encode(['status' => false, 'error_code' => 701,  'message' => "Invalid Application Number."], 200);
              return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
            }
        }else{
    $jsonResult = json_encode(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
    return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    }
  }

  
  public function addImage(Request $request)
  {

   // $post = APIController::decrypt($request->data);
    //$post = json_decode($post);
    $post = $request;
    //dd($post);
    $arr=[];
    try {
      //$lat = $post->lat;
      //$lng = $post->long;
      //$arr = array('lat' => $lat, 'lng' => $lng);
      $image = $post->reg_img;
      $screen = $post->curscrn;
      $proposal = $post->application_number;
      $screentype = $post->scrntype;
      
      //$sys_lang = (isset($post->sys_lang) && $post->sys_lang!='') ? $post->sys_lang : null;
      $path = public_path('upload/' . $proposal . '/img/');
      $url = asset('public/upload/' . $proposal . '/img/' . $screen . '.jpeg');
      $img = array('screen' => $screen, 'image_url' => $url, 'type' => $screentype);
      $img_ognl = Link::where('proposal_no', '=', $proposal)->select('images')->get();
      if ($img_ognl[0]->images == NULL or $screen == 'Welcome Screen') {
        $images = array();
      } else {
        $images = json_decode($img_ognl[0]->images, true);
      }
      array_push($images, $img);
      $json_images = json_encode($images);
      $linkSingle = Link::where('proposal_no', '=', $proposal)->first();
      //$linkSingle->location= json_encode($arr);
      $linkSingle->images = $json_images;
      // if($sys_lang!=''){
      //   $linkSingle->sys_lang = $sys_lang;
      // }
      $linkSingle->save();
     // Link::where('proposal_no', '=', $proposal)->update(['location' => json_encode($arr), 'images' => $json_images,]);
      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true, true);
      }
      if ($image != '' and $screen != '') {
        ApiController::base64_to_jpegFrontend($image, $path . '/' . $screen . '.jpeg');
        //echo ApiController::msg(TRUE, 'Image Uploaded.');
        $arr = array('status' => TRUE, 'msg' =>'Image Uploaded.');
      } else {
        //echo ApiController::msg(FALSE, 'Data missing');
        $arr = array('status' => FALSE, 'msg' => 'Data missing.');
      }
      //$arr = array('status' => FALSE, 'msg' => 'Data missing.');
      return json_encode($arr);
      ApiController::logs($proposal, $screen, '', '', "Success");
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post->proposal_no, 'addImageFailed', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }


public function addVideo(Request $request)
  {
   // dd($request->all());
    //dd('hfh');
    $file = $request->file('videoChunk'); //dd($file);
    //$file = $request->videoChunk;
    $dataspch='';
    $name = 'consent.webm';
    $proposal = $request->application_number;
    $url = public_path('upload/' . $proposal . '/vid/consent.webm');
    try{ 
     
    ApiController::logs($proposal, 'Liveness video capture started',"","",  "Success");

    $path = public_path('upload/' . $proposal . '/vid/');
    if (!File::isDirectory($path)) {
      File::makeDirectory($path, 0777, true, true);
    }
    $targetFile = $path . $name; 
    // dd($targetFile);
    $chunkIndex = $request->chunkIndex; //dd($targetFile);
    if ($chunkIndex == 0) {
      if (file_exists($targetFile)) {
        unlink($targetFile);
      }
    }
    
    $totalChunks = $request->totalChunks;
    $_speech_res = '';
    // dd($_FILES['videoChunk']['tmp_path']);
    $chunkContent = file_get_contents($_FILES['videoChunk']['tmp_name']); 
    $appendSuccess = file_put_contents($targetFile, $chunkContent, FILE_APPEND);
    chmod($targetFile,0777); 
    ApiController::logs($proposal, 'video chunk total index',$totalChunks,"",  "Success");

    if ($appendSuccess !== false) { 
      $currentFileSize = filesize($targetFile);
   
        if ($currentFileSize < 51200) { //dd('kyuy');   //To check file size is less than 5KB or not
            $_speech_res = "Low";
        } 
    }
    else {
        echo "Failed to append chunk.";
        ApiController::logs($proposal, 'Failed to append video chunk',"","",  "Success");
    } 
   //dd($totalChunks, $chunkIndex, $appendSuccess, $targetFile);
    if($totalChunks == ($chunkIndex + 1)){//dd('dfd');

      
      ApiController::logs($proposal, 'video chunk index',$chunkIndex,"",  "Success");

      $lang= 'english';
      $url = $this->file_url.'/public/upload/' . $proposal . '/vid/consent.webm';
      $url_pass = public_path().'/upload/' . $proposal . '/vid/consent.webm';
      Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);
      $linksave = Link::where('proposal_no', $proposal)->first();
       $lang = $linksave->sys_lang;
      if($lang=='eng'){
        $lang= 'english';
      }elseif($lang=='hin'){
        $lang= 'hindi';
      }
      elseif($lang=='ben'){
        $lang= 'bengali';
      }elseif($lang=='mar'){
        $lang= 'marathi';
      }
      elseif($lang=='ori'){
        $lang= 'oriya';
      }
      elseif($lang=='tel'){
        $lang= 'telugu';
      }
       elseif($lang=='tam'){
        $lang= 'tamil';
      }
      elseif($lang=='mal'){
        $lang= 'malayalam';
      }
       elseif($lang=='kan'){
        $lang= 'kannada';
      }
       elseif($lang=='guj'){
        $lang= 'gujarati';
      }
      elseif($lang=='ass'){
        $lang= 'assamese';
      }


      //speach to text
      sleep(2);
      $link = Link::where('proposal_no', $proposal)->get();
         $params = json_decode($link[0]['params'], TRUE);
         //dd($params);
         //ApiController::logs($params, 'Video Uploaded ',"","",  "Success");
         $name = $params['Life_assured_name']; 
          $sp = ApiController::speechToText($proposal, $url_pass, $lang, $name);
          //dd($sp);
          //$sp ="";
          $_speech_res = "Low";
          //$_speech_res = 'High';
          if($sp!=''){//dd('sds');
            $sp_res = $sp->getData();//dd($sp_res->match);
            //$sp_res = json_decode($sp,true);//dd($sp_res);
            if(isset($sp_res->match) && $sp_res->match==true && $sp_res->score=="low"){
              //$_speech_res = true;
              $_speech_res = "Low";
            }
            elseif(isset($sp_res->match) && $sp_res->match==false && $sp_res->score=="noaudio"){
              //$_speech_res = true;
              $_speech_res = "Low";
            }
            else if(isset($sp_res->match) && $sp_res->match==true && $sp_res->score>=65){
              //$_speech_res = true;
              $_speech_res = "High";
            }else{
              $_speech_res = "Low";
            }

            if($sp_res->spchstatus){
              $dataspch = 'success';  
            }else{
              $dataspch = 'fail';
            }
            //dd($dataspch);
            //$data = $sp->getData();

          }
          $linksave->speech_res = $sp;
          $linksave->save();
          ApiController::logs($proposal, 'customer video uploaded success',"","",  "Success");
          }
          
            
          
//dd(count($appendSuccess),$totalChunks);

          
    //if ($appendSuccess !== false  ) {
      if ($appendSuccess !== false) {
      Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);
      return response()->json(array('status' => TRUE,  'spchstatus' => 'success', 'Message' => 'Video Uploaded.','speech_res'=>$_speech_res));
    } else {//dd('ddf');
      return response()->json( array('status' => TRUE, 'spchstatus' => 'fail', 'Message' => 'Upload Failed'));
    }


  } catch (Exception $e) {//dd('dfdd');
    // Log or handle the error
    Log::error("Exception during cURL request: " . $e->getMessage());
    return response()->json([
        'message' => 'Exception occurred',
        'error' => $e->getMessage(),
        'spchstatus' => 'fail'
    ]);
  }


  }

  public function addVideo_old(Request $request)
  {
    try{
    $file = $request->file('videoChunk');
    //$file = $request->videoChunk;
    $name = 'consent.webm';
    $proposal = $request->application_number;
    $url = public_path('upload/' . $proposal . '/vid/consent.webm');

    ApiController::logs($proposal, 'addvideo start',"","",  "Success");

   ApiController::logs($proposal, 'Video Upload ',$file,"",  "Success");

   $_speech_res = "Low";

    $path = public_path('upload/' . $proposal . '/vid/');
    if (!File::isDirectory($path)) {
      File::makeDirectory($path, 0777, true, true);
    }
    $targetFile = $path . $name; 
    $chunkIndex = $request->chunkIndex; //dd($targetFile);
    if ($chunkIndex == 0) {
      if (file_exists($targetFile)) {
        unlink($targetFile);
      }
    }
    $totalChunks = $request->totalChunks;
    $_speech_res = '';
    // dd($_FILES['videoChunk']['tmp_path']);
    $chunkContent = file_get_contents($_FILES['videoChunk']['tmp_name']); 
    $appendSuccess = file_put_contents($targetFile, $chunkContent, FILE_APPEND);
  
    
    if($totalChunks== ($chunkIndex + 1)){

      if ($appendSuccess !== false) { 
      $currentFileSize = filesize($targetFile);
   
        if ($currentFileSize < 51200) {   //To check file size is less than 5KB or not
            $_speech_res = "Low";
        }
    }
    else {
        //echo "Failed to append chunk.";
        ApiController::logs($proposal, 'Failed to append chunk',"","",  "Success");
    }

      $lang= 'english';
      $url = $this->file_url.'/public/upload/' . $proposal . '/vid/consent.webm';
      $url_pass = public_path().'/upload/' . $proposal . '/vid/consent.webm';
      Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);
      $linksave = Link::where('proposal_no', $proposal)->first();
       $lang = $linksave->sys_lang;
      if($lang=='eng'){
        $lang= 'english';
      }elseif($lang=='hin'){
        $lang= 'hindi';
      }
      // }elseif($lang=='ben'){
      //   $lang= 'bengali';
      // }elseif($lang=='tam'){
      //   $lang= 'tamil';
      // }else{
      //   $lang= 'marathi';
      // }
      //speach to text
      sleep(2);
      $link = Link::where('proposal_no', $proposal)->get();
         $params = json_decode($link[0]['params'], TRUE);
         //dd($params);
         //ApiController::logs($params, 'Video Uploaded ',"","",  "Success");
         $name = $params['Life_assured_name']; 
          $sp = ApiController::speechToText($proposal, $url_pass, $lang, $name);
         // dd($sp['spchstatus']);
          //$sp ="";
          
          //$_speech_res = 'High';
          if($sp!=''){
            $sp_res = json_decode($sp,true);//dd($sp_res);
            if(isset($sp_res["match"]) && $sp_res["match"]==true && $sp_res["score"]=="low"){
              //$_speech_res = true;
              $_speech_res = "Low";
            }
            elseif(isset($sp_res["match"]) && $sp_res["match"]==false && $sp_res["score"]=="noaudio"){
              //$_speech_res = true;
              $_speech_res = "Low";
            }
            else if(isset($sp_res["match"]) && $sp_res["match"]==true && $sp_res["score"]>75){
              //$_speech_res = true;
              $_speech_res = "High";
            }else{
              $_speech_res = "Low";
            }
          }
          $linksave->speech_res = $sp;
          $linksave->save();
          ApiController::logs($proposal, 'Video Uploaded ',"",json_encode($sp),  "Success");
          }
   
           $data = $sp->getData(true);
//dd($data['spchstatus']);
    if ($appendSuccess !== false && $data['spchstatus']=="success") {
      Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);
      return response()->json(array('status' => TRUE, 'spchstatus' => 'success', 'Message' => 'Video Uploaded.','speech_res'=>$_speech_res));
    } else {
      return response()->json( array('status' => TRUE, 'spchstatus' => 'fail',  'Message' => 'Upload Failed'));
    }
  } catch (Exception $e) {
        // Log or handle the error
        Log::error("cURL request failed: " . $e->getMessage());
        return response()->json(['spchstatus' => 'fail', 'message' => $e->getMessage()]);
    }
  }
  
  
  public function medicalQuestions(Request $request)
  {
    $proposal = $request->proposal_no;
    $medical_questions_1 = $request->medical_questions_1;
    $medical_questions_2 = $request->medical_questions_2;
    $medical_questions_3 = $request->medical_questions_3;

    $arr = array('medical_questions_1' => $medical_questions_1, 'medical_questions_2' => $medical_questions_2, 'medical_questions_3' => $medical_questions_3, 'mq1_smoke' => $request->mq1_smoke, 'mq1_quantity' => $request->mq1_quantity, 'mq1_frequency' => $request->mq1_frequency, 'mq2_alcohal' => $request->mq2_alcohal, 'mq2_quantity' => $request->mq2_quantity, 'mq2_frequency' => $request->mq2_frequency);
    //$json_res=json_encode($arr);
    // echo "<pre>"; print_r($arr);die;
    $stus = Link::where('proposal_no', '=', $proposal)->update($arr);
    if ($stus) {
      echo ApiController::msg(TRUE, 'Medical Questions updated successfully.');
    } else {
      echo ApiController::msg(FALSE, 'Medical Questions update Failed');
    }
  }



  public function addIDCard(Request $request)
  {
    //dd('fdff');
    $proposal = $request->proposal_no;
    $link_img = $request->id_card;

    $link_img = str_replace('data:image/jpeg;base64,', '', $link_img);
    $link_img = str_replace(' ', '+', $link_img);
    $link_img_data = base64_decode($link_img);

    if (($link_img_data !== false)) {
      $stus = Link::where('proposal_no', '=', $proposal)->get();
      if ($stus !== false) {
        $link_id = $stus[0]['id'];
        $file_name_details = ApiController::captureIdFile($link_id);
        // echo "<pre>"; print_r($file_name_details);die;
        if (!$file_name_details['status']) {
          die(json_encode(array('status' => FALSE, 'msg' => 'Given file data is invalid!')));
        } else {
          $file_name_data = array(
            'file_name' => $file_name_details['name'],
            'file_loc' => 'adc/capture_images'
          );

          $image_details = ApiController::addIdCardImageFile($file_name_data, $link_img_data);
          if ($image_details['status']) {
            if (PROJECT_SERVER_ENV == 'local') {
              $reg_img_name = $image_details['name'];
            } else {
              $this->localFileDelete($image_details['path']);
              $reg_img_name = $image_details['url'];
            }
            $arr = array("id_card_url" => $reg_img_name);
            $stus = Link::where('proposal_no', '=', $proposal)->update($arr);

            echo json_encode(array('status' => TRUE, 'msg' => 'Successfully added the ID card image!'));
          } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Error occurred while creating the image!'));
          }
        }
      } else {
        echo json_encode(array('status' => FALSE, 'msg' => 'Given Link is not valid!'));
      }
    } else {
      echo json_encode(array('status' => FALSE, 'msg' => 'Given Link or data is not valid!'));
    }
  }



  public function CompleteStatus(Request $request)
  {
   //dd('sds');
    // We change the status as completed_status 1 and generate pdf

    //$post = APIController::decrypt($request->data);
    //$post = json_decode($post);
    $post = $request->all();//dd($post);
    try {
      $proposal =$post['application_number'];
      $vd = Link::where('proposal_no', $proposal)->select('video')->get();//dd($vd);

      $vd = $vd['0'];
      //$video=json_decode($ vd->video);
      if ($vd != "" and $vd != NULL) {

        $path = public_path('upload/' . $proposal . '/img/');
        if (file_exists($path . '/link_upload.jpeg')) {
          $linkImage =   ApiController::image_to_base64($path . 'link_upload.jpeg');

          $captureImage =   ApiController::image_to_base64($path . '/video_consent.jpeg');
          //\\ echo $captureImage;die;
          $type = 'default';
          $links = Link::where('proposal_no', $proposal)->first();

          if (is_null($links->face_score) || $links->face_score === '') {
            $faceScore = ApiController::getFaceScore($proposal, $linkImage, $captureImage, $type);
            //dd($faceScore);die;
            if (!empty($faceScore)) {
              //dd($faceScore);
              if (isset($faceScore['confidence'])) {
                $links = Link::where('proposal_no', $proposal)->first();
                $links->face_score = (int) $faceScore['confidence'];
                $links->face_response = ApiController::face_codes($faceScore['response_code']);
                //$links->face_response = $faceScore['message'];
                $links->save();
              }
            }
          }
          
        }

        $links = Link::where('proposal_no', $proposal)->update(['complete_status' => 1, 'completed_on' => date('Y-m-d H:i:s'), 'status' => 1]);
        // $pdf_url = ApiController::pdf($proposal);

        // Log related codes
        // $result=json_encode();

        $linkspush = Link::where('proposal_no', $proposal)->first();
        //$question = json_decode($linkspush->questions);
        //$question1 = isset($question[0]->Q1) ? $question[0]->Q1 : " ";
        //$question2 = isset($question[1]->Q2) ? $question[1]->Q2 : " ";

        $face_score  =  'No';
        if ($linkspush->face_score != null && $linkspush->face_score >= 40) {
          $face_score  =  'Yes';
        } else if (is_null($linkspush->face_score)) {
          $face_score  =  'Yes';
        }

        if ($linkspush->personal_disagree == 'Agree' && $linkspush->policy_disagree == 'Agree' && $face_score == 'Yes') {
          //ApiController::pivcLinkStatus($proposal, 'Success', 'Video PIVC Pass', 'Video PIVC Pass');
        } else {
          $msg = '';
          if ($linkspush->personal_disagree == 'Disagree' && $linkspush->policy_disagree == 'Disagree') {
            $msg = 'Personal & Policy Details';
          } else if ($linkspush->personal_disagree == 'Disagree') {
            $msg = 'Personal Details';
          } else if ($linkspush->policy_disagree == 'Disagree') {
            $msg = 'Policy Details';
          } else if ($face_score == 'No') {
            $msg = 'Face not match';
          } else {
            $msg = 'Health Questions';
          }

          //ApiController::pivcLinkStatus($proposal, 'PIVC FAIL', 'Video PIVC Fail', $msg);
        }

        // $pdf_res =  ApiController::pdfDocUpload($proposal);
        // $pdf_res = json_decode($pdf_res, true);
        // if (!empty($pdf_res) && $pdf_res['status'] &&  $pdf_res['data']['status'] != 'FAILED') {
        //   $docpush = Link::where('proposal_no', $proposal)->first();
        //   $docpush->docpush = 1;
        //   $docpush->docpush_date = date('Y-m-d H:i:s');          
        //   $docpush->save();
        // }
        // ApiController::genPdf($proposal);
          $docpush = Link::where('proposal_no', $proposal)->first();
          $docpush->docpush = 0;
          $docpush->docpush_date = null;          
          $docpush->save();
        // ApiController::logs($proposal, 'CompleteStatus', '', '', "Success");
        //echo ApiController::msg(TRUE, array("pdf" => "", "msg" => 'PIVC Completed successfully'));
        //$result = json_encode(array(TRUE, 'PIVC Completed successfully'));

        
 
        $callbackresult = $this->callbackurl($proposal);
        //$callbackresult='success';
   //$callbackstr = '"'.json_encode($callbackresult).'"';

   //print_r($callbackstr);
        //die;
        $callredirect = json_decode($docpush['params']);

        $callbckurl = APIController::decrypt($callredirect->callbackurl); //dd($callbckurl);

   $callbackstr = '"'.json_encode($callbackresult).'"';
        $arr = array('status' => True, "msg" => 'PIVC Completed successfully', "callback_url"=>$callbckurl, "callback_reslt"=>$callbackresult);

        //return json_encode($arr);

       // ApiController::retriggerComplete($proposal, $docpush->complete_status);

       $checknow = ApiController::retriggerComplete($proposal, $docpush->complete_status);

        return $arr;

        //dd($callbckurl);
        //$arr = array('status' => True, "msg" => 'PIVC Completed successfully', "callback_url"=>$callbckurl, "callbackresponse" => $callbackresult);
        //$arr = array('status' => True, "msg" => 'PIVC Completed successfully', "callback_url"=>$callbckurl, json_encode($callbackresult));

        //$query = http_build_query($arr);

        // Add query string to callback URL
        //$redirect_url = $callbckurl . '?' . $query;

        // Redirect

        //dd($redirect_url);
        //header("Location: $redirect_url");

        //dd($callbackresult);
        //echo ApiController::msg("Success", 'agree Status Added.');
        //return json_encode($arr);
        //return $result;
        //echo ApiController::msg('Failed','PIVC complete Failed');
      } else {
        // Log related codes
        //$result = json_encode(array(FALSE, 'PIVC complete Failed'));
        $arr = array('status' => False, "msg" => 'PIVC complete Failed');
        return json_encode($arr);

        //echo ApiController::msg(FALSE, 'PIVC complete Failed');
      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post['application_Number'], 'CompleteStatusFailed', '', $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }

  public function updatefacescore()
  {

  $chcknull = Link::where('face_score', NULL)
    ->where('face_response', NULL)
     ->where('complete_status', 1)
    ->select('proposal_no') // <-- specify the columns you want
    //->limit(5)  
    ->get()
    ->toArray();

    print_r($chcknull);die;

     //$proposal =$post['application_number'];

     foreach ($chcknull as $key => $value) {
      //print_r($value['proposal_no']);

      $proposal = $value['proposal_no'];
    
      $vd = Link::where('proposal_no', $proposal)->select('video')->get();//dd($vd['0']);

      $vd = $vd['0'];
      //$video=json_decode($ vd->video);
      if ($vd != "" and $vd != NULL) {

        $path = public_path('upload/' . $proposal . '/img/');

        //dd($path);
        if (file_exists($path . '/link_upload.jpeg')) {

          $filePath1 = $path . '/link_upload.jpeg';
          $linkImage = file_exists($filePath1)
              ? ApiController::image_to_base64($filePath1)
              : '';
          //$linkImage =   ApiController::image_to_base64($path . 'link_upload.jpeg');

          //$captureImage =   isset($path . '/video_consent.jpeg')?ApiController::image_to_base64($path . '/video_consent.jpeg'):'';

          $filePath2 = $path . '/video_consent.jpeg';

          $captureImage = file_exists($filePath2)
              ? ApiController::image_to_base64($filePath2)
              : '';

          //dd($captureImage);
          //\\ echo $captureImage;die;
          $type = 'default';

     $faceScore = ApiController::getFaceScore($proposal, $linkImage, $captureImage, $type);
           //dd($faceScore);die;
           print_r($faceScore);
          if (!empty($faceScore)) {
            //dd($faceScore);
            if (isset($faceScore['confidence'])) {
              $links = Link::where('proposal_no', $proposal)->first();
              $links->face_score = (int) $faceScore['confidence'];
              $links->face_response = ApiController::face_codes($faceScore['response_code']);
              //$links->face_response = $faceScore['message'];
              $links->save();
            }
          }
        }

     }
    }//die;
  }


  public function getFaceScore($proposal, $consentImage, $faceImage, $type = 'default')
  {
    ApiController::logs($proposal, 'getFacescore start',"","",  "Success");

    //$url = 'https://test.anurcloud.com/face_compare';
    $url = 'https://test.anurcloud.com/faceapi_indiafirst';
    //$url = 'http://192.168.1.244:5001';   //VBAWCLi.sudlife.in
    //https://test.anurcloud.com/faceapi_indiafirst
    //$arr= ["policyno" => $proposal, "image1" => $consentImage, "image2" => $faceImage];
    $arr = ["policyno" => $proposal, "image1" => $consentImage, "image2" => $faceImage];
    $data = json_encode($arr);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    //curl_setopt($ch, CURLOPT_HTTPHEADER,array($headers));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $result = curl_exec($ch);
    curl_close($ch);
    if ($type == "default") {
      //ApiController::logs($proposal, 'faceScoreRequestDefault', "", $result);
    } else {
      //ApiController::logs($proposal, 'faceScoreRequestScheduler', "", $result); 
    }

    ApiController::logs($proposal, 'getfacescore end', json_encode($data), $result,  "Success");

    return json_decode($result, true);
  }

  public function image_to_base64($path)
  {
//dd($path);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $fileData = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
    /*
    $arrContextOptions=array(
      "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
      ),
    );
    $data = file_get_contents($path, false, stream_context_create($arrContextOptions));
    $base64 = base64_encode($data);
    */
    return $fileData;
  }


  public function face_codes($code)
  {

    switch ($code) {
      case 'FACE001':
        $response = "Consent Photo Not in Proper Format";
        break;
      case 'FACE002':
        $response = "KYC Not in Proper Format";
        break;
      case 'FACE003':
        $response = "One or Both images are not available";
        break;
      case 'FACE004':
        $response = "Face Not Detected in Consent Photo";
        break;
      case 'FACE005':
        $response = "Face Not Detected in KYC Image";
        break;
      case 'FACE006':
        $response = "Undefined Error";
        break;
      case 'FACE200':
      default:
        $response = "Face Match Complete";
        break;
    }
    return $response;
  }

  public function agent()
  {
    $agent = new Agent();
    $array = array();
    $array['languages'] = $agent->languages();
    if ($agent->isDesktop()) {
      $device = "Desktop";
    } elseif ($agent->isMobile()) {
      $device = "Mobile";
    } elseif ($agent->isTablet()) {
      $device = "Tablet";
    } elseif ($agent->isPhone()) {
      $device = "Phone";
    } else {
      $device = $agent->device();
    }
    $array['device'] = $device;
    $array['os'] = $agent->platform();
    $array['os_version'] = $agent->version($agent->platform());
    $array['browser'] = $agent->browser();
    $array['browser_version'] = $agent->version($agent->browser());
    // $languages = $agent->languages();
    // $device = $agent->device();
    // $platform = $agent->platform();
    // $browser = $agent->browser();
    //$agent->isDesktop();
    //echo $ua;exit;
    return json_encode($array);
  }

  public function pdf($proposal)
  {
    //$proposal=$request->id;
    $name = $proposal . '.pdf';

    $pdf_path_without_Domain = '/public/upload/' . $proposal . '/pdf/';
    $data_dir = '../data/adc/';
    $img_dir_rel = 'adc/';

    $path = $data_dir . $name;
    $links = Link::where('proposal_no', $proposal)->get();
    $links = $links[0];
    $params = json_decode($links->params);
    $device = json_decode($links->device);
    $network = json_decode($links->network);
    $response = json_decode($links->response);
    //echo "<pre>"; print_r($links);die;
    $data = json_decode($links['reg_photo_url']);

    $pdf = PDF::loadView('pdf', compact('links', 'data', 'params', 'device', 'network', 'response'));
    //Link::where('proposal_no',$proposal)->update(['pdf_url'=>$url]);
    $pdf->save($path)->stream($name);

    if (PROJECT_SERVER_ENV == 'aws') {
      // echo $img_path.",".$img_key.",".$img_name;die;
      $aws_file_upload = APIController::dataImageS3Upload($path, $pdf_path_without_Domain . $name, $name);
      if ($aws_file_upload == "Image NOT uploaded successfully") {
        $img_file_data['status'] = FALSE;
      } else // AWS file URL's only accepted
      {
        $img_file_data['url'] = $aws_file_upload;

        ApiController::updateConsentpdfUrl($proposal, $aws_file_upload);
      }
    }
    return $aws_file_upload;
    //return $url
    //return $pdf->stream('download.pdf');
  }

  public function CreatePDF(Request $request, $proposal)
  {
    //$proposal=$request->id;
    $name = $proposal . '.pdf';

    $path = public_path('upload/' . $proposal . '/pdf/') . $name;
    $url = asset('public/upload/' . $proposal . '/pdf/' . $name);
    $links = Proposal::where('proposal_no', $proposal)->get();
    $links = $links[0];
    $trans = Transaction::where('proposal_no', $proposal)->get();
    $trans = $trans[0];
    //print_r($links);exit;
    $pdf = PDF::loadView('pdf', compact('links', 'trans'));
    Transaction::where('proposal_no', $proposal)->update(['pdf_url' => $url]);
    return $pdf->save($path)->stream($name); //
    //return $url
    //return $pdf->stream('download.pdf');
  }

  public function autopdfsave(Request $request)
  {
    //$links=Links::where('complete_status',0)->get();
    $links = Links::where('proposal_no', 'RNLIC100')->get();
    foreach ($links as $value) {
      $proposal = $value->proposal_no;

      if (!File::isDirectory(public_path('upload/' . $proposal . '/img/'))) {
        File::makeDirectory(public_path('upload/' . $proposal . '/img/'), 0777, true, true);
      }
      if (!File::isDirectory(public_path('upload/' . $proposal . '/scr/'))) {
        File::makeDirectory(public_path('upload/' . $proposal . '/scr/'), 0777, true, true);
      }
      $img = scandir(public_path('upload/' . $proposal . '/img/'));
      $scr = scandir(public_path('upload/' . $proposal . '/scr/'));
      if (count($img) == 3 and count($scr) == 3) {
        if ($value->network == '' or $value->network == NULL) {
          $network = json_encode(array('type' => NULL, 'rtt' => NULL, 'downlink' => NULL));
        } else {
          $network = $value->network;
        }
        if ($value->location == '' or $value->location == NULL) {
          $location = json_encode(array('lat' => NULL, 'lng' => NULL));
        } else {
          $location = $value->location;
        }

        $links = Links::where('proposal_no', $proposal)->update(['network' => $network, 'location' => $location, 'complete_status' => 1, 'completed_on' => date('Y-m-d H:i:s'), 'status' => 0]);
        ApiController::pdf($proposal);
      }
    }
    echo 'complete';
  }

  public function NotCompleteList($filename)
  {
    Excel::store(new NotComExport(), $filename);
    return true;
  }

  public function CompleteList($filename)
  {
    Excel::store(new CompleteList(), $filename);
    return true;
  }

  public function AuthCheck(Request $request)
  {
    $post = APIController::decrypt($request->data);
    $post = json_decode($post);

    if (isset($post->date_of_birth)) {
      $proposal = Proposal::where('date_of_birth', $post->date_of_birth)->where('contact_number', $post->contact_number)->where('application_no', $post->application_no)->get('application_no');
    } else {
      $proposal = Proposal::where('contact_number', $post->contact_number)->where('application_no', $post->application_no)->get('application_no');
    }
    if (count($proposal) > 0) {
      foreach ($proposal as $proposal_val) {
        $transaction = Transaction::where(['proposal_no' => $proposal_val->application_no, 'otp' => $post->otp])->get('short_link', 'otp_created_at');

        $data = Transaction::where(['proposal_no' => $proposal_val->application_no, 'otp' => $post->otp])->get('otp_created_at');
        $time_1 = 0;
        $time_2 = 0;
        foreach ($data as $datas) {
          $currenttime = Carbon::now()->toDateTimeString();
          $time_1 = strtotime($currenttime);
          $time_2 = strtotime($datas['otp_created_at']);
        }


        if ($time_1 > $time_2) {
          $result = json_encode(array(FALSE, 'OTP has been expired. Please try using Resend OTP'));
          ApiController::logs("", 'AuthCheck', json_encode(($post)), $result, "Fail");
          echo ApiController::msg(FALSE, 'OTP has been expired. Please try using Resend OTP');
        }

        if (count($transaction) > 0) {

          // Log related codes
          $result = json_encode(array(TRUE, $transaction));
          ApiController::logs($proposal_val->application_no, 'AuthCheck', json_encode(($post)), $result, "Success");

          echo ApiController::msg(TRUE, $transaction);
        } else {

          // Log related codes
          $result = json_encode(array(FALSE, 'Auth Failed, Check OTP'));
          ApiController::logs($proposal_val->application_no, 'AuthCheck', json_encode(($post)), $result, "Fail");
          echo ApiController::msg(FALSE, 'Auth Failed, Check OTP');
        }
      }
    } else {
      // Log related codes
      $result = json_encode(array(FALSE, 'Auth Failed, Check DOB and Contact Number'));
      ApiController::logs($post->application_no, 'AuthCheck', json_encode(($post)), $result, "Fail");
      echo ApiController::msg(FALSE, 'Auth Failed, Check DOB and Contact Number');
    }
  }

  
  public function Questions(Request $request)
  {
    $post = APIController::decrypt($request->data);
    $post = json_decode($post);
    // To check if the app no is valid
    $transactionId = Transaction::where('proposal_no', $post->proposal_no)->get('id');

    if (count($transactionId) > 0) {
      if ($post->tobacco == true) {
        $transaction = Transaction::where('proposal_no', $post->proposal_no)->update(['tobacco' => $post->tobacco, 'tobacco_type' => $post->tobacco_type, 'quantity_per_day' => $post->quantity_per_day]);
        echo ApiController::msg(TRUE, 'Values captured as expected!');
      } else {
        $transaction = Transaction::where('proposal_no', $post->proposal_no)->update(['tobacco' => 'false']);
        echo ApiController::msg(TRUE, 'Values captured as expected!');
      }
    } else {
      echo ApiController::msg(FALSE, 'Invalid Application number');
    }
  }

  public function CovidQuestion(Request $request)
  {
    $post = APIController::decrypt($request->data);
    $post = json_decode($post);
    // To check if the app no is valid
    $transactionId = Transaction::where('proposal_no', $post->proposal_no)->get('id');

    if (count($transactionId) > 0) {
      if ($post->covid == "Yes") {
        if ($post->covid_vaccination_date_2_dose != "") {
          $transaction = Transaction::where('proposal_no', $post->proposal_no)->update(['covid' => $post->covid, 'covid_vaccination_date' => $post->covid_vaccination_date, 'covid_vaccination_date_2_dose' => $post->covid_vaccination_date_2_dose]);
        } else {
          $transaction = Transaction::where('proposal_no', $post->proposal_no)->update(['covid' => $post->covid, 'covid_vaccination_date' => $post->covid_vaccination_date, 'covid_vaccination_date_2_dose' => NULL]);
        }
      } else {
        $transaction = Transaction::where('proposal_no', $post->proposal_no)->update(['covid' => $post->covid, 'covid_vaccination_date' => NULL, 'covid_vaccination_date_2_dose' => NULL]);
      }
      echo ApiController::msg(TRUE, 'Values captured as expected!');
    } else {
      echo ApiController::msg(FALSE, 'Invalid Application number');
    }
  }

  public function getAndSaveLocation(Request $request)
  {
    // To check if the app no is valid
    $transactionId = Link::where('proposal_no', $request->proposal_no)->get('id');

    $location['lat'] = $request->lat;
    $location['long'] = $request->long;
    $location['address'] = $this->get_geo_address_here($request->lat, $request->long);
    $locationJSON = json_encode($location);
    if (count($transactionId) > 0) {
      $transaction = Link::where('proposal_no', $request->proposal_no)->update(['location' => $locationJSON]);
      echo ApiController::msg(TRUE, array('address' => $location['address'], 'msg' => 'Locations details saved!'));
    } else {
      echo ApiController::msg(FALSE, 'Failed to save!');
    }
  }

  public function get_geo_address_here($lat, $long)
  {

    $url = "https://nominatim.openstreetmap.org/reverse?format=geocodejson&lat=" . $lat . "&lon=" . $long . "&zoom=18";
    $curl = curl_init();
    $options = array(
      "http" => array(
        "header" => "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
      )
    );
    $context = stream_context_create($options);
    $geocode = file_get_contents($url, false, $context);

    $address = json_decode($geocode)->features[0]->properties->geocoding->label;

    return $address;
  }

  public function saveDeviceNetwork(Request $request)
  {
    // To check if the app no is valid
    $transactionId = Link::where('proposal_no', $request->proposal_no)->get('id');

    $device_name = $request->device_name;
    $nettype = $request->nettype;
    $netrtt = $request->netrtt;
    $netdown = $request->netdown;
    $network = array('device_name' => $device_name, 'type' => $nettype, 'rtt' => $netrtt, 'downlink' => $netdown);

    $agent = ApiController::agent();

    if (count($transactionId) > 0) {
      Link::where('proposal_no', $request->proposal_no)->update(['device' => $agent, 'network' => json_encode($network)]);

      // Log related codes
      $result = json_encode(array(TRUE, 'Device and Network details saved!'));
      ApiController::logs($request->proposal_no, 'saveDeviceNetwork', json_encode(($request)), $result, "Success");

      echo ApiController::msg(TRUE, 'Device and Network details saved!');
    } else {
      // Log related codes
      $result = json_encode(array(FALSE, 'Failed to save!'));
      ApiController::logs($request->proposal_no, 'saveDeviceNetwork', json_encode(($request)), $result, "Fail");

      echo ApiController::msg(FALSE, 'Failed to save!');
    }
  }

  public function addLifeAssured(Request $request)
  {
    $post = APIController::decrypt($request->data);
    $post = json_decode($post);
    // To check if the app no is valid
    $transactionId = Transaction::where('proposal_no', $post->proposal_no)->get('id');

    if (count($transactionId) > 0) {
      $transaction = Transaction::where('proposal_no', $post->proposal_no)->update(['life_assured_date' => $post->life_assured_date]);
      echo ApiController::msg(TRUE, 'Values captured as expected!');
    } else {
      echo ApiController::msg(FALSE, 'Invalid Application number');
    }
  }


  public function addConsentVideo(Request $request)
  {
    $file_video_data = $request->video_data;
    $proposal_no = $request->proposal_no;
    $device = $request->device;

    $file_name_data = array(
      'file_name' => 'consent',
      'file_loc' => public_path('upload/' . $proposal_no . '/vid/'),
      'device' => $device
    );

    $video_details = ApiController::base64_to_video($file_name_data, $file_video_data, $proposal_no);
    if ($video_details['url']) {
      if (PROJECT_SERVER_ENV == 'local') {
        $reg_img_name = $video_details['url'];
        $sp = "";
      } else {
        $link = Link::where('proposal_no', $proposal_no)->get();
         $params = json_decode($link[0]['params'], TRUE);
         //dd($params);
         $name = $params['Life_assured_name'];
        // $plan_type = "NON-ULIP";
        // $tenure = $params['POLICY_TERM'];
        // echo $video_details['path'];die;
        $sp = ApiController::speechToText($proposal_no, str_replace('//', '/', $video_details['path']), 'English', $name);

        //$this->localFileDelete($video_details['path']);
        $consent_video = $video_details['url'];
      }


      ApiController::updateConsentVideoUrl($proposal_no, $consent_video);
      return ApiController::msg(TRUE, (array('message' => 'Successfully added the consent user video!', 'speechResponse' => json_decode($sp))));
    } else {
      return ApiController::msg(FALSE, 'Error occurred while creating the consent video!');
    }
  }

   public function testspeechToText(Request $request)
  {
//dd($request->proposal_number);

   $proposal = $request->proposal_number;
   $lang = $request->lang;
   $url = $request->url;
    $name = $request->name;
    ApiController::logs($proposal, 'speech to text started',"","",  "Success");
    try {  
    
    $curl = curl_init();
    //$tenure = (int)$tenure;
    curl_setopt_array($curl, array(
      //CURLOPT_URL => 'http://192.168.30.147:5002',
      CURLOPT_URL => 'https://demo.anurcloud.com/sud_life_stt',
      //CURLOPT_URL => 'https://vbawcliuat.sudlife.in/speechtotext_check',      
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      // CURLOPT_SSL_VERIFYPEER => false, 
      // CURLOPT_PROXY => '127.0.0.1:5000',
      //CURLOPT_SSL_VERIFYPEER =>false,
      //CURLOPT_SSL_VERIFYHOST =>false,
      CURLOPT_POSTFIELDS => array(
        'lang' => $lang,
        'name' => $name,
        'application_number' => $proposal,
       'video_blob' => new \CURLFILE($url),
      )
    ));

    $response = curl_exec($curl);
    // if ($response === false) {
    //   $error = curl_error($curl);dd($error);
    // }
    //echo $response;die;
    curl_close($curl);

    // Try decoding
  $responseArray = json_decode($response, true); //dd($responseArray);
   //
 $responseArray['spchstatus'] = 'success'; //dd($responseArray);

        //return response()->json($responseArray); //dd($reponse);
        $response = json_encode($responseArray);
        ApiController::logs($proposal, 'speech to text response',"",$response,  "Success");

        //return $response;
        } catch (Exception $e) {//dd('dfdd');
        // Log or handle the error
        Log::error("Exception during cURL request: " . $e->getMessage());
        return response()->json([
            'message' => 'Exception occurred',
            'error' => $e->getMessage(),
            'spchstatus' => 'fail'
        ]);
    }

    //ApiController::logs($proposal, 'addvideo stop',"","",  "Success");

    //return $response;
  }

  public function speechToText($proposal, $url, $lang, $name)
  {
//dd('sdsd');
    ApiController::logs($proposal, 'speech to text started',"","",  "Success");
    try {  

        //$response = '{invalid json'; // malformed JSON
    
    $curl = curl_init();
    //$tenure = (int)$tenure;
    curl_setopt_array($curl, array(      //CURLOPT_URL => 'http://192.168.30.147:5002',
      CURLOPT_URL => 'https://demo.anurcloud.com/sud_life_stt',
      //CURLOPT_URL => 'https://vbawcliuat.sudlife.in/speechtotext_check',      
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      // CURLOPT_SSL_VERIFYPEER => false, 
      // CURLOPT_PROXY => '127.0.0.1:5000',
      //CURLOPT_SSL_VERIFYPEER =>false,
      //CURLOPT_SSL_VERIFYHOST =>false,
      CURLOPT_POSTFIELDS => array(
        'lang' => $lang,
        'name' => $name,
        'application_number' => $proposal,
       'video_blob' => new \CURLFILE($url),
      )
    ));

    $response = curl_exec($curl);
   // dd($response);
    // if ($response === false) {
    //   $error = curl_error($curl);dd($error);
    // }
    //echo $response;die;
    curl_close($curl);
//echo $response;die;
    // Try decoding
  $responseArray = json_decode($response, true); //dd($responseArray);

$responseArray['spchstatus'] = 'success'; //dd($responseArray);
 ApiController::logs($proposal, 'speech to text completed',"",$response,  "Success");
  
       //$response = json_encode($responseArray);
        return response()->json($responseArray);
        //$response = json_encode($responseArray); 
        //echo $response;die;
        //return $response;
        } catch (Exception $e) {//dd('dfdd');
        // Log or handle the error
        //Log::error("Exception during cURL request: " . $e->getMessage());

         $res = $e->getMessage();
         //$responseArray['spchstatus'] = 'fail';
        //$response = json_encode($responseArray);
         
        ApiController::logs($proposal, 'speech to text completed',"",$res,  "Exception occurred");
       //return $response;
        return response()->json([
            'message' => 'Exception occurred',
            'error' => $e->getMessage(),
            'spchstatus' => 'fail'
        ]);
    }

    //ApiController::logs($proposal, 'addvideo stop',"","",  "Success");

    //return $response;
  }

  public function speechToText_old($proposal, $url, $lang, $name)
  {

       ApiController::logs($proposal, 'speechToText start',"","",  "Success");
    try {   
    
    $curl = curl_init();
    //$tenure = (int)$tenure;
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://demo.anurcloud.com/sud_life_stt',  //   https://demo.anurcloud.com/sud_life_stt
      //CURLOPT_URL => 'https://demo.anurcloud.com/sud_life_stt',
      //CURLOPT_URL => 'https://vbawcli.sudlife.in/speechtotext_check',      
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      // CURLOPT_SSL_VERIFYPEER => false, 
      // CURLOPT_PROXY => '127.0.0.1:5000',
      //CURLOPT_SSL_VERIFYPEER =>false,
      //CURLOPT_SSL_VERIFYHOST =>false,
      CURLOPT_POSTFIELDS => array(
        'lang' => $lang,
        'name' => $name,
        'application_number' => $proposal,
       'video_blob' => new \CURLFILE($url),
      )
    ));

    $response = curl_exec($curl);

    //dd($response);

    $req =  array(
        'lang' => $lang,
        'name' => $name,
        'application_number' => $proposal
    );
    // dd($response);
    //echo $response;die;
        ApiController::logs($proposal, 'speechToText end',json_encode($req), $response,  "Success");
        curl_close($curl);
// Try decoding
$responseArray = json_decode($response, true); //dd($responseArray);

// If decode fails or is empty, fallback
if (!is_array($responseArray)) {
    $responseArray = ['message' => 'Invalid JSON response'];
}

//  Add your status
$responseArray['spchstatus'] = 'success'; //dd($responseArray);

// Return full response
return response()->json($responseArray);
        //return $response;
        } catch (Exception $e) {
        // Log or handle the error
        Log::error("cURL request failed: " . $e->getMessage());
        return response()->json(['spchstatus' => 'fail', 'message' => $e->getMessage()]);
    }
  }

  public function base64_to_video($file_details, $file_video_data, $proposal_no)
  {
    $video_file_data = array(
      'status' => FALSE,
      'name' => '',
      'path' => '',
      'url' => '',
      'key' => ''
    );

    $domain_url = $this->file_url;

    $path = public_path('upload/' . $proposal_no . '/vid/');
    if (!File::isDirectory($path)) {
      File::makeDirectory($path, 0777, true, true);
    }

    $video_dir_rel = public_path('upload/' . $proposal_no . '/vid/');
    $video_path_without_Domain = '/public/upload/' . $proposal_no . '/vid/';
    $video_dir = $video_dir_rel;

    if ($file_details['device'] != 'ios') {
      $ext = '.webm';
    } else {
      $ext = '.mov';
    }
    $video_name = $file_details['file_name'] . "_" . $proposal_no . $ext;
    $video_path = $video_dir . '/' . $video_name;
    $video_url = $domain_url . $video_path_without_Domain . $video_name;
    $video_key = $video_path_without_Domain . $video_name;
    // open the output file for writing
    $ifp = fopen($video_path, 'wb');
    // echo $file_video_data;exit;
    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode('base64,', $file_video_data);

    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[1]));

    // clean up the file resource
    fclose($ifp);

    if (true) {
      if ($file_details['device'] == 'ios') {
        $open = $video_path;
        $video_name = $file_details['file_name'] . '.webm';
        $video_path = $video_dir . $video_name;
        $video_url = $domain_url . $video_path_without_Domain . $video_name;
        $video_key = $video_path_without_Domain . $video_name;

        shell_exec("ffmpeg -i $open -c:a libvorbis -ac 1 -b:a 96k -ar 48000 -b:v 1100k -maxrate 1100k -bufsize 1835k $video_path");
      }
      $video_file_data['status'] = TRUE;
      $video_file_data['name'] = $video_name;
      $video_file_data['path'] = $video_path;
      $video_file_data['url'] = $video_url;
      $video_file_data['key'] = $video_key;
    }

    $aws_file_upload = APIController::dataImageS3Upload($video_path, $video_key, $video_name);
    if ($aws_file_upload == "Image NOT uploaded successfully") {
      $img_file_data['path'] = $video_path;
      $img_file_data['status'] = FALSE;
    } else // AWS file URL's only accepted
    {
      $img_file_data['path'] = $video_path;
      $img_file_data['url'] = $aws_file_upload;
    }

    return $img_file_data;
  }

  public function updateConsentVideoUrl($proposal_no, $consent_video_url)
  {
    // To check if the app no is valid
    $transactionId = Link::where('proposal_no', $proposal_no)->get('id');

    if (count($transactionId) > 0) {
      $transaction = Link::where('proposal_no', $proposal_no)->update(['video_url' => $consent_video_url]);
      ApiController::msg(TRUE, 'Video link copied!');
    } else {
      ApiController::msg(FALSE, 'Invalid Application number');
    }
  }

  public function updateConsentpdfUrl($proposal_no, $consent_pdf_url)
  {
    // To check if the app no is valid
    $transactionId = Link::where('proposal_no', $proposal_no)->get('id');

    if (count($transactionId) > 0) {
      $transaction = Link::where('proposal_no', $proposal_no)->update(['transcript_pdf_url' => $consent_pdf_url]);
      ApiController::msg(TRUE, 'PDF link copied!');
    } else {
      ApiController::msg(FALSE, 'Invalid Proposal number');
    }
  }

  public function createPIVCLink(Request $request)
  {
  //   $jsonResult='wY6xfxsEU+uUGu5YGnm5TE3\/KTY4Pk0NULMiKCWfHWrtJIFVikAeh\/8pVMv+euzic9hoLJ1NSXxjCYRK4b3T6RMnVmYbLxc+rZx8leRRGBzvzwJKYkvhOHveZB+AhnBe';
  //      return $dd =  APIController::decrypt($jsonResult);
  //  die;
      $reqst = $request->all();//dd($reqst['ReqPayload']);

      //unset($reqst['image_base64']);

      //dd($reqst);

      try {

      $jsonString = APIController::decrypt($reqst['ReqPayload']);  

      //$post = explode(",", $jsonString); //dd($post); dd();

      //dd($jsonString);

      $post = json_decode($jsonString, true);  //dd($post);

      $checkvalidation = ApiController::linkValidation($post);//dd($checkvalidation);

      if(!empty($checkvalidation)){ 
     
      $data = json_decode($checkvalidation, true);

      //$array = explode(",", $string);
       //print_r($data['status']);//die;
        ApiController::logs($post['Application_Number'], 'createPIVCLinkStart', json_encode(($post)), '', "Success");

      if ($data['status']=='success') {//dd($post['Application_Number']);
        $link = ApiController::linkgen(trim($post['Application_Number']));
        $short_link = ApiController::shortenTiny(trim($link));//dd($short_link);

        $transactionUrl = Link::where('proposal_no', $post['Application_Number'])->get('url');

        if (count($transactionUrl) > 0) {//dd('ddf');
          $jsonresult =  json_encode(["status" => false,  "error_code" =>601, "message" => "Link Already Exists"]);
          return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonresult),'Source'=>'SUD']);
        } else {//dd('ddf1');
            $params = $post;
            //dd($params);
            unset($params['image_base64']);
            $trans = new Link;
            $trans->proposal_no = trim($post['Application_Number']);
            $trans->url = $link;
            $trans->short_link = $short_link;
            $trans->params = json_encode($params);
            $trans->status = 1;
            //$trans->medical_checked_response = $params['Medical_Flag'];

            $trans->created_at = date('Y-m-d H:i:s');
            $trans->updated_at = date('Y-m-d H:i:s');
            $trans->save();

            $photo  = isset($post['image_base64']) ? ($post['image_base64']) : '';

            if ($photo != '') {

               $path = public_path('upload/' . $trans->proposal_no . '/img/');
              if (!File::isDirectory($path)) {
                 File::makeDirectory($path, 0777, true, true);
               }

              ApiController::base64_to_all($photo, $path . '/org_upload');

              ApiController::image_base64check($photo,$trans->proposal_no); //dd($photo);
              // $path = public_path('upload/' . $trans->proposal_no . '/img/');
              // if (!File::isDirectory($path)) {
              //   File::makeDirectory($path, 0777, true, true);
              // }

              // // $imagesid = array();
              // // $url = $path . '/link_upload.jpeg';
              // // $imgid = array('screen' => 'IDproof', 'image_url' => $url, 'type' => 'photo');
              // // array_push($imagesid, $imgid);
              // // $json_images = json_encode($imagesid);
              // // $linkSingle = Link::where('proposal_no', '=', $trans->proposal_no)->first();
              // // $linkSingle->images = $json_images;
              // // $linkSingle->save();

              // ApiController::base64_to_jpeg($photores, $path . '/link_upload.jpeg');

            }
            
            //ApiController::pivcLinkStatus($post['Application_Number'], 'Not Attempted', '', 'Not Attempted');
            //dd($short_link);
            $jsonResult = json_encode(["status" => TRUE,  "link" => $short_link], 200);
            
            return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
            //return response()->json(["status" => TRUE,  "link" => $short_link], 200);
        }
      }
    }else{
      return response()->json(['error' => 'createPIVCLinkFailed'], 200);
    }
    } catch (\Exception $e) {//dd($data);
      ApiController::logs($post['Application_Number'], 'createPIVCLinkFailed', json_encode($post), $e->getMessage(), "Failure");
      //return response()->json(['error' => $e->getMessage()], 400);
       $array = json_decode($checkvalidation, true);

       if (isset($array[0])) {
        $result = $array[0];
       }
       else{
        $result = $array;
       }

       

      // // Convert back to JSON if needed
       $jsonResult = json_encode($result, JSON_PRETTY_PRINT);

       return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
       //$jsonResult='uIi6rfTM0nbyuZMQOnAXlpZIp7b37nf60TxIgkdy+uvdXf6de3x8gEXb2qibFVdh59ieJEMtH9u1HKbbvTJ7A9PihqIgIzxopcu4zOqe287Wx7vGpX0zB5naFjp7AJ6AMYXF/ilUxK93F1hUA2PnfzEHp/4oPPEG/zT64hUD8OO/okdngi2B1fTUT4qSSIdm+ZQIjYeEskFEHI7jkYA4Fg==';
       //return APIController::decrypt($jsonResult);
      //return json_decode($checkvalidation, true);
    }
  }


  public function getNameAppno(Request $request)
  {
    $post = APIController::decrypt($request->data);
    $post = json_decode($post);
    //You will need to create another API, which will accept inputs - Mobile Number, DOB, OTP and retireve the lastest Application Number that is not complete and return Customer Name and Application Number to the user
    $contact_number = $post->contact_number;
    $date_of_birth = $post->date_of_birth;
    $otp = $post->otp;

    $check = Transaction::join('proposals', 'proposals.application_no', '=', 'transactions.proposal_no')->where('transactions.otp', '=', $otp)->whereDate('proposals.date_of_birth', '=', ($date_of_birth))->where('proposals.contact_number', '=', $contact_number)->where('transactions.complete_status', '=', 1)->select('proposals.application_no', 'proposals.customer_name')->orderBy('transactions.id', 'desc')->get();

    $data = Transaction::join('proposals', 'proposals.application_no', '=', 'transactions.proposal_no')->where('transactions.otp', '=', $otp)->whereDate('proposals.date_of_birth', '=', ($date_of_birth))->where('proposals.contact_number', '=', $contact_number)->where('transactions.complete_status', '!=', 1)->select('proposals.application_no', 'proposals.customer_name', 'transactions.otp_created_at')->orderBy('transactions.id', 'desc')->get();
    $time_1 = 0;
    $time_2 = 0;
    foreach ($data as $datas) {
      $currenttime = Carbon::now()->toDateTimeString();
      $time_1 = strtotime($currenttime);
      $time_2 = strtotime($datas['otp_created_at']);
    }

    if ($time_1 > $time_2) {
      $result = json_encode(array(FALSE, 'OTP has been expired. Please try using Resend OTP'));
      ApiController::logs("", 'getNameAppno', json_encode(($post)), $result, "Fail");

      echo ApiController::msg(FALSE, 'OTP has been expired. Please try using Resend OTP');
    } elseif (count($data) > 0) {

      $result = json_encode($data);
      ApiController::logs($data[0]->application_no, 'getNameAppno', json_encode($post), $result, "Success");

      echo ApiController::msg(TRUE, $data);
    } elseif (count($check) > 0) {
      // Log related codes
      $result = json_encode(array(FALSE, 'PIVC is complete'));
      ApiController::logs("", 'getNameAppno', json_encode(($post)), $result, "Fail");

      echo ApiController::msg(FALSE, "PIVC is complete");
    } else {

      // Log related codes
      $result = json_encode(array(FALSE, 'Unable to fetch data, check the input'));
      ApiController::logs("", 'getNameAppno', json_encode(($post)), $result, "Fail");

      echo ApiController::msg(FALSE, 'Unable to fetch data, check the input');
    }
  }

  public function testFunc()
  {
    $Emailmessage = "<p>Dear Tester,<br/>Thank you for your application XXXXX for Canara HSBC OBC Life Insurance iSelect Star Term Plan. Please click on <a href='https://anoor.link/9ywVHNd' target='_blank'>https://anoor.link/9ywVHNd</a> to complete pre-issuance verification. Policy issuance is subject to underwriting and verification process.<br/>Canara HSBC OBC Life Insurance.</p>";
    $this->sendEmail('Tester', 'hariharanv@anoorcloud.com', $Emailmessage);
  }

  public function sendEmail($name, $email, $message)
  {

    $array = array(
      'from' => [
        'email' => 'hariharanvvu@canarahsbclife.in',
        'name' => 'CanaraHSBC life',
      ],
      'subject' => 'CanaraHSBC life Email',
      'content' => [
        [
          'type' => 'html',
          'value' => $message,
        ]
      ],
      'personalizations' => [
        [
          'to' => [
            [
              'email' => $email,
              'name' => $name,
            ]
          ]
        ]
      ]
    );

    $arrToJSON = json_encode($array);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.pepipost.com/v5.1/mail/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $arrToJSON,
      CURLOPT_HTTPHEADER => array(
        'api_key: 326469f65b4c4f569ae8374a66af59de',
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    echo $response;
  }

  public function CreateUserAccount(Request $request)
  {
    $details =  $request->all();
    if (($request->id != "") && ($request->email != "")) {
      $proposal = $details['id'];
      $count = User::where('id', $proposal)->count();
      if ($count >= 1) {
        $link = User::where('id', $proposal)->get();
        $url = $link[0]->link;
        $array = array('status' => true, 'message' => 'User no already exist');
        $result = json_encode($array);
        // print_r($result);die();
        ApiController::logs($proposal, 'recreate', json_encode($details), $result);
        //print_r($result);die();
        return $result;
      }


      $user = new User;
      //print_r($user);die();
      $user->id = $proposal;
      $user->name = 'pavithra';
      $user->email = 'test@gmail.com';
      $user->email_verified_at = date("Y-m-d H:i:s");
      $user->username = 'pavithra_velmurugan';
      $user->password = '$2y$10$apMsyFuzbUEpfs.6lH9jR.D3VmBgc9gqAIjdMV.qH47Eka/9HeuWO';
      $user->remember_token = 'NULL';
      $user->created_at = date("Y-m-d H:i:s");
      $user->updated_at = date("Y-m-d H:i:s");
      //print_r($user->updated_at);die();
      $user->save();

      $array = array('status' => true);
      $result = json_encode($array);
      return ('<b>User Created Successfully ! </b>');
    } else {
      $arr = array("status" => false,  "message" => "User no is missing");
      $result = json_encode($arr);
      return $result;
    }
  }


  //Clear Logs
  public function Clearlogs()
  {
    //echo "bgd";die;
    $res =  DB::table('logs')->where('module', '=', "login")->delete();
  }

  public function getProposalPIVCLink(Request $request)
  {
    // $post = APIController::decrypt($request->data);
    // $post = json_decode($post);
    $post = $request;
    $data = $post->anur_pivc_data;

    try {
      $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
      $json = json_encode($xml);
      $xml_array = json_decode($json, TRUE);
    } catch (\Exception $e) {
      log_message('KFD_LOG', 'KFD -- xml_data_parse_error --- Error : ' . $e->getMessage());
      return FALSE;
    }

    $linksCheck = Link::where('proposal_no', trim($xml_array['Table']['PROPOSAL_NUMBER']))->get();
    if (count($linksCheck) > 0) {
      return ('<b>Proposal Already Exists ! </b>');
    }
    // elseif( $post != $xml_array['Table']['PROPOSAL_NUMBER']){
    //   return ('<b>Proposal Not Matched ! </b>');
    // }
    else {

      $link = new Link;
      $link->proposal_no  = trim($xml_array['Table']['PROPOSAL_NUMBER']);
      $link->params  = json_encode($xml_array['Table']);
      $link->expiry  = 45;
      $linkValue = ApiController::linkgen(trim($xml_array['Table']['PROPOSAL_NUMBER']));
      $short_link = ApiController::shorten(trim($linkValue));


      $link_uid = ApiController::generate_link_uid();
      $link->uid = $link_uid;

      $link_ukey = ApiController::generate_link_key();
      $link->ukey = $link_ukey;

      $link->link = $linkValue;
      $link->link_short = $short_link;
      $link->link_short = $short_link;
      $link->updated_on = date('Y-m-d H:i:s');
      $link->save();
      return ('<b>Link Created Successfully ! </b>');
    }

    // echo "<pre>"; print_r($xml_array);die;

  }

  public function addCapturedImage(Request $request)
  {
    $link_key = $request->key;
    $link_img = $request->reg_img;

    $link_media_append = ($request->media_append == 'true') ? TRUE : FALSE;
    $link_img = str_replace('data:image/jpeg;base64,', '', $link_img);
    $link_img = str_replace(' ', '+', $link_img);
    $link_img_data = base64_decode($link_img);

    $meta_details = array(
      'lat' => $request->lat ?: 0,
      'long' => $request->long ?: 0,
      'loc' => $request->loc ?: '',
      'lang' => $request->lang ?: '',
      'scrn' => $request->scrn ?: ''
    );

    if (($link_key != '') && ($link_img_data !== false)) {
      $link_key = trim($link_key);
      $link_detail = ApiController::checkLinkKeyExist($link_key);

      if ($link_detail !== false) {
        $link_id = $link_detail['id'];
        $file_name_details = ApiController::captureImageFile($link_id, $meta_details['scrn']);

        if (!$file_name_details['status']) {
          die(json_encode(array('status' => FALSE, 'msg' => 'Given file data is invalid!')));
        } else {
          $file_name_data = array(
            'file_name' => $file_name_details['name'],
            'product_name' => $file_name_details['p_name'],
            'file_loc' => 'adc/capture_images'
          );

          $image_details = ApiController::addCapturedJPEGImageFile($file_name_data, $link_img_data, $meta_details);

          if ($image_details['status']) {
            $info_param = array(
              'latitude' => $meta_details['lat'],
              'longitude' => $meta_details['long'],
              'location' => $meta_details['loc'],
              'screen' => $meta_details['scrn'],
              'language' => $meta_details['lang']
            );
            if (PROJECT_SERVER_ENV == 'local') {
              $reg_img_name = $image_details['name'];
            } else {
              $this->localFileDelete($image_details['path']);
              $reg_img_name = $image_details['url'];
            }
            $link_media_url_update = ApiController::updateRegPhotoUrl($link_id, $reg_img_name, $link_media_append, $info_param);
            echo json_encode(array('status' => TRUE, 'msg' => 'Successfully added the captured user image!'));
          } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Error occurred while creating the image!'));
          }
        }
      } else {
        echo json_encode(array('status' => FALSE, 'msg' => 'Given Link is not valid!'));
      }
    } else {
      echo json_encode(array('status' => FALSE, 'msg' => 'Given Link or data is not valid!'));
    }
  }

  function localFileDelete($filePath)
  {
    if (File::exists($filePath)) {
      return File::delete($filePath);
    }
    return false;
  }

  public function addCapturedScreenShot(Request $request)
  {
    $link_key = $request->key;
    $link_img = $request->screen_img;

    $link_media_append = ($request->media_append == 'true') ? TRUE : FALSE;

    $link_img = str_replace('data:image/jpeg;base64,', '', $link_img);
    $link_img = str_replace(' ', '+', $link_img);
    $link_img_data = base64_decode($link_img);

    $meta_details = array(
      'lat' => $request->lat ?: 0,
      'long' => $request->long ?: 0,
      'loc' => $request->loc ?: '',
      'lang' => $request->lang ?: '',
      'scrn' => $request->scrn ?: ''
    );

    if (($link_key != '') && ($link_img_data !== false)) {
      $link_key = trim($link_key);
      $link_detail = ApiController::checkLinkKeyExist($link_key);

      if ($link_detail !== false) {
        $link_id = $link_detail['id'];
        $file_name_details = ApiController::captureImageScreenShotFile($link_id, $meta_details['scrn']);

        if (!$file_name_details['status']) {
          die(json_encode(array('status' => FALSE, 'msg' => 'Given file data is invalid!')));
        } else {
          $file_name_data = array(
            'file_name' => $file_name_details['name'],
            'product_name' => $file_name_details['p_name'],
            'file_loc' => 'adc/capture_images'
          );


          $image_details = ApiController::addCapturedJPEGImageFile($file_name_data, $link_img_data, $meta_details);


          if ($image_details['status']) {
            $info_param = array(
              'latitude' => $meta_details['lat'],
              'longitude' => $meta_details['long'],
              'location' => $meta_details['loc'],
              'screen' => $meta_details['scrn'],
              'language' => $meta_details['lang']
            );
            if (PROJECT_SERVER_ENV == 'local') {
              $reg_img_name = $image_details['name'];
            } else {
              $this->localFileDelete($image_details['path']);
              $reg_img_name = $image_details['url'];
            }
            $link_media_url_update = ApiController::updateScreenShotPhotoUrl($link_id, $reg_img_name, $link_media_append, $info_param);
            echo json_encode(array('status' => TRUE, 'msg' => 'Successfully added the captured user image!'));
          } else {
            echo json_encode(array('status' => FALSE, 'msg' => 'Error occurred while creating the image!'));
          }
        }
      } else {
        echo json_encode(array('status' => FALSE, 'msg' => 'Given Link is not valid!'));
      }
    } else {
      echo json_encode(array('status' => FALSE, 'msg' => 'Given Link or data is not valid!'));
    }
  }

  function captureImageFile($link_id, $scrn = null)
  {
    $file_data = array(
      'status' => FALSE,
      'name' => '',
      'p_id' => ''
    );
    $param_data = APIController::getParamData($link_id);

    if (!empty($param_data)) {
      $file_name = '';
      $file_name .= (!empty($param_data['proposal_no'])) ? $param_data['proposal_no'] . '_' : '';
      $file_name .= 'PIVCPHOTO_';
      //$file_name .= (!empty($scrn))? $scrn.'_':'';
      $file_name .= date('Y_m_d_H_i_s');
      $file_name = APIController::fileNameStd($file_name);

      $file_data['status'] = TRUE;
      $file_data['name'] = $file_name;
      $file_data['p_name'] = (!empty($param_data['PRODUCT_SLUG'])) ? $param_data['PRODUCT_SLUG'] : '';
    }

    return $file_data;
  }

  function captureImageScreenShotFile($link_id, $scrn = null)
  {
    $file_data = array(
      'status' => FALSE,
      'name' => '',
      'p_id' => ''
    );
    $param_data = APIController::getParamData($link_id);

    if (!empty($param_data)) {
      $file_name = '';
      $file_name .= (!empty($param_data['proposal_no'])) ? $param_data['proposal_no'] . '_' : '';
      $file_name .= 'PIVCPHOTO_SCREEN_';
      //$file_name .= (!empty($scrn))? $scrn.'_':'';
      $file_name .= date('Y_m_d_H_i_s');
      $file_name = APIController::fileNameStd($file_name);

      $file_data['status'] = TRUE;
      $file_data['name'] = $file_name;
      $file_data['p_name'] = (!empty($param_data['flow_key'])) ? $param_data['flow_key'] : '';
    }

    return $file_data;
  }

  function captureIdFile($link_id)
  {
    $file_data = array(
      'status' => FALSE,
      'name' => '',
      'p_id' => ''
    );
    $param_data = APIController::getParamData($link_id);

    if (!empty($param_data)) {
      $file_name = '';
      $file_name .= (!empty($param_data['proposal_no'])) ? $param_data['proposal_no'] . '_' : '';
      $file_name .= 'IDCARDPHOTO_';
      //$file_name .= (!empty($scrn))? $scrn.'_':'';
      $file_name .= date('Y_m_d_H_i_s');
      $file_name = APIController::fileNameStd($file_name);

      $file_data['status'] = TRUE;
      $file_data['name'] = $file_name;
      $file_data['p_name'] = (!empty($param_data['PRODUCT_SLUG'])) ? $param_data['PRODUCT_SLUG'] : '';
    }

    return $file_data;
  }

  function getParamData($link_id)
  {
    $linksCheck = Link::where('id', $link_id)->get();
    $param_str = $linksCheck[0]['params'];

    if (!empty($param_str)) {
      $param_arr = json_decode($param_str, true);

      if (!empty($param_arr)) {
        return $param_arr;
      } else {
        return null;
      }
    } else {
      return null;
    }
  }

  function addCapturedJPEGImageFile($file_details, $file_data, $meta_details = null)
  {
    $img_file_data = array(
      'status' => FALSE,
      'name' => '',
      'path' => '',
      'url' => '',
      'key' => ''
    );

    $data_dir = '../data/';
    $img_dir_rel = $file_details['file_loc'] . '/' . $file_details['product_name'] . '/';
    $img_dir = $data_dir . $img_dir_rel;
    $dir_status = APIController::makeDirs($img_dir);

    $ext = '.jpeg';
    $img_name = $file_details['file_name'] . $ext;
    $img_path = $img_dir . $img_name;
    $img_url = 'https://dev1.anurcloud.com/anur_pivc/data' . $img_dir_rel . $img_name;
    $img_key = $img_dir_rel . $img_name;

    $img_create = file_put_contents($img_path, $file_data);

    if ($img_create) {
      $img_file_data['status'] = TRUE;
      $img_file_data['name'] = $img_name;
      $img_file_data['path'] = $img_path;
      $img_file_data['url'] = $img_url;
      $img_file_data['key'] = $img_key;

      /*if($meta_details!=null)
            {
                $this->addJpegMetaInfo($img_file_data,$meta_details);
            }*/

      //$this->uploadAssetFileSFTP($img_file_data,'IMAGESOURCE');

      if (PROJECT_SERVER_ENV == 'aws') {
        // echo $img_path.",".$img_key.",".$img_name;die;
        $aws_file_upload = APIController::dataImageS3Upload($img_path, $img_key, $img_name);
        if ($aws_file_upload == "Image NOT uploaded successfully") {
          $img_file_data['status'] = FALSE;
        } else // AWS file URL's only accepted
        {
          $img_file_data['url'] = $aws_file_upload;
        }
      }
    }
    return $img_file_data;
  }



  function addIdCardImageFile($file_details, $file_data)
  {
    $img_file_data = array(
      'status' => FALSE,
      'name' => '',
      'path' => '',
      'url' => '',
      'key' => ''
    );

    $data_dir = '../data/';
    $img_dir_rel = $file_details['file_loc'] . '/';
    $img_dir = $data_dir . $img_dir_rel;
    $dir_status = APIController::makeDirs($img_dir);

    $ext = '.jpeg';
    $img_name = $file_details['file_name'] . $ext;
    $img_path = $img_dir . $img_name;
    $img_url = 'https://dev1.anurcloud.com/anur_pivc/data' . $img_dir_rel . $img_name;
    $img_key = $img_dir_rel . $img_name;

    $img_create = file_put_contents($img_path, $file_data);

    if ($img_create) {
      $img_file_data['status'] = TRUE;
      $img_file_data['name'] = $img_name;
      $img_file_data['path'] = $img_path;
      $img_file_data['url'] = $img_url;
      $img_file_data['key'] = $img_key;

      /*if($meta_details!=null)
            {
                $this->addJpegMetaInfo($img_file_data,$meta_details);
            }*/

      //$this->uploadAssetFileSFTP($img_file_data,'IMAGESOURCE');

      if (PROJECT_SERVER_ENV == 'aws') {
        // echo $img_path.",".$img_key.",".$img_name;die;
        $aws_file_upload = APIController::dataImageS3Upload($img_path, $img_key, $img_name);
        if ($aws_file_upload == "Image NOT uploaded successfully") {
          $img_file_data['status'] = FALSE;
        } else // AWS file URL's only accepted
        {
          $img_file_data['url'] = $aws_file_upload;
        }
      }
    }
    return $img_file_data;
  }


  function makeDirs($dirPath, $mode = 0777)
  {
    return is_dir($dirPath) || mkdir($dirPath, $mode, true);
  }

  public function dataVideoS3Upload($path, $key, $filename)
  {
    $localFilePath = str_replace('//', '/', $path); // Path to the local file

    $path = Storage::disk('s3')->put($filename, file_get_contents($localFilePath));
    if ($path) {
      $path = Storage::disk('s3')->url($filename); // Get the bucket url for uploaded DB backup file.
      return str_replace('anur_pivc/anur_pivc', 'anur_pivc', $path);
      //return 'Image uploaded successfully';
    } else {
      return 'Image NOT uploaded successfully';
    }
  }

  public function dataImageS3Upload($path, $key, $filename)
  {

    // $url = 'https://anoor.s3.us-east-1.amazonaws.com/anur_pivc/';
    // $images = [];
    // $files = Storage::disk('s3')->files();
    // dd($files);die;

    // foreach ($files as $file) {
    // $images[] = [
    // 'name' => str_replace('images/', '', $file),
    // 'src' => $url . $file
    // ];
    // }
    // die; 

    $localFilePath = str_replace('//', '/', $path); // Path to the local file
    //  echo $filename."<br>";
    // echo $key;die;

    $path = Storage::disk('s3')->put($filename, file_get_contents($localFilePath));

    if ($path) {
      $path = Storage::disk('s3')->url($filename); // Get the bucket url for uploaded DB backup file.
      return str_replace('anur_pivc/anur_pivc', 'anur_pivc', $path);
      //return 'Image uploaded successfully';
    } else {
      return 'Image NOT uploaded successfully';
    }

  }


  public function updateRegPhotoUrl($id, $media_url, $media_append = FALSE, $info_params)
  {
    $info_arr = array();
    if (!$media_append) {
      $info_params['media_url'] = $media_url;
      array_push($info_arr, $info_params);
      $media_list_arr = $info_arr;
      $media_list_str = json_encode($media_list_arr);
      return Link::where('id', $id)->update(['reg_photo_url' => $media_list_str]);
    } else {
      $arr = array('Medical Questionnaire', 'Welcome Screen', 'Benefit Illustration');
      $links = Link::where('id', $id)->get();

      $media_list_str = $links[0]['reg_photo_url'];

      $media_list_arr = array();
      if (!empty($media_list_str)) {
        $media_list_arr = APIController::removeImageJson($media_list_str, $info_params['screen']);
      }

      $info_params['media_url'] = $media_url;
      array_push($media_list_arr, $info_params);

      $media_list_str = json_encode($media_list_arr);

      if (in_array($info_params['screen'], $arr)) {
        $media_list_str1 = $links[0]['consent_image_url'];
        $media_list_arr1 = APIController::removeImageJson($media_list_str1, $info_params['screen']);
        $media_list_str1 = json_encode($media_list_arr1);
        Link::where('id', $id)->update(['consent_image_url' => $media_list_str1]);
      }
      return Link::where('id', $id)->update(['reg_photo_url' => $media_list_str]);
    }
  }

  public function updateScreenShotPhotoUrl($id, $media_url, $media_append = FALSE, $info_params)
  {
    $info_arr = array();
    if (!$media_append) {
      $info_params['media_screen_url'] = $media_url;
      array_push($info_arr, $info_params);
      $media_list_arr = $info_arr;
      $media_list_str = json_encode($media_list_arr);
      return Link::where('id', $id)->update(['reg_photo_url' => $media_list_str]);
    } else {
      $arr = array('Medical Questionnaire', 'Welcome Screen', 'Benefit Illustration');
      $links = Link::where('id', $id)->get();
      $media_list_str = $links[0];

      $media_list_arr = array();
      if (!empty($media_list_str)) {
        $media_list_arr = APIController::removeImageJson($media_list_str['reg_photo_url'], $info_params['screen']);
      }


      if (in_array($info_params['screen'], $arr)) {
        $media_list_str1 = $media_list_str['consent_image_url'];
        $data_fine = json_decode($media_list_str1);
        $media_list_final = array();
        foreach ($data_fine as $vi => $vg) {
          $media_list_final[$vi] = $vg;
          if ($info_params['screen'] == $vg->screen) {
            $media_list_final[$vi]->media_screen_url = $media_url;
          }
        }
        //$media_list_arr1 = $this->removeImageJson($media_list_str1,$info_params['screen']);
        $media_list_str1 = json_encode($media_list_final);
        Link::where('id', $id)->update(['consent_image_url' => $media_list_str1]);
      } else {
        $media_list_str1 = $media_list_str['reg_photo_url'];
        $data_fine = json_decode($media_list_str1);
        $media_list_final = array();
        foreach ($data_fine as $vi => $vg) {
          $media_list_final[$vi] = $vg;
          if ($info_params['screen'] == $vg->screen) {
            $media_list_final[$vi]->media_screen_url = $media_url;
          }
        }
        //$media_list_arr1 = $this->removeImageJson($media_list_str1,$info_params['screen']);
        if (count($media_list_final) != 0) {
          $media_list_str1 = json_encode($media_list_final);
          Link::where('id', $id)->update(['reg_photo_url' => $media_list_str1]);
        }
      }

      return;
    }
  }

  public function checkLinkKeyExist($key)
  {
    $links = Link::where('ukey', $key)->where('status', 1)->get();
    return $links[0];
  }

  public function fileNameStd($str, $replace = '_', $upper = true)
  {
    if (!empty($str)) {
      $str = trim($str);
      $str = ($upper) ? strtoupper($str) : strtolower($str);
      return preg_replace('/\s+/', $replace, $str);
    } else {
      return '';
    }
  }

  public function removeImageJson($media_list_str, $info_params_screen)
  {
    $temp_list = json_decode($media_list_str, true);

    $media_list_arr = array();
    if (is_array($temp_list)) {
      foreach ($temp_list as $rs) {

        $screen = APIController::chgName($rs['screen']);
        if (isset($media_list_arr[$screen])) {
          $media_list_arr[$screen . '_disagree'] = $rs;
        } else {
          $media_list_arr[$screen] = $rs;
        }
      }
    }
    //print_r($media_list_arr);exit;
    $rmv_screen = APIController::chgName($info_params_screen);
    $rmv_screen_count = count(explode('-', $info_params_screen));
    // unset($media_list_arr[$rmv_screen]);
    // unset($media_list_arr[$rmv_screen.'_disagree']);
    if ($rmv_screen_count == 2) // and in_array($media_list_arr[$rmv_screen.'_disagree'],$media_list_arr))
    {
      //unset($media_list_arr[$rmv_screen]);
      unset($media_list_arr[$rmv_screen . '_disagree']);
    } else {
      unset($media_list_arr[$rmv_screen]);
      unset($media_list_arr[$rmv_screen . '_disagree']);
    }
    $temp_list_new = array_values($media_list_arr);
    //print_r($temp_list_new);exit;
    if (!empty($temp_list_new)) {
      $media_list_arr = $temp_list_new;
    } else {
      $media_list_arr = array();
    }
    return $media_list_arr;
  }

  public function chgName($str)
  {
    $name = preg_replace('/ - Disagree/', '', trim($str));
    $name = str_replace(' ', '_', trim($name));
    $name = strtolower($name);
    return $name;
  }

  public function generate_link_uid()
  {
    $id = APIController::generate_id();
    return $id;
  }

  public function generate_link_key()
  {
    $id = APIController::generate_id();
    return $id;
  }

  function generate_id($format = 'random', $length = '24')
  {
    $format = trim($format);

    if ($format == "random" || empty($format)) {
      $final_id = md5(time() . rand(1000, 999999999) . uniqid(rand(), true)) . md5(rand(1, 999) . rand(999, 999999));
    } else {
      $final_id = '';
      $letters_lower = 'abcdefghijklmnopqrstuvwxyz';
      $letters_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $the_format = preg_split('//', $format, -1, PREG_SPLIT_NO_EMPTY);
      foreach ($the_format as $aLetter) {
        if ($aLetter == "l") {
          $temp_rand = rand(0, 25);
          $get_one = $letters_lower[$temp_rand];
          $final_id .= $get_one;
        } elseif ($aLetter == "L") {
          $temp_rand = rand(0, 25);
          $get_one = $letters_upper[$temp_rand];
          $final_id .= $get_one;
        } elseif ($aLetter == "n") {
          $temp_rand = rand(1, 9);
          $final_id .= $temp_rand;
        } else {
          $final_id .= $aLetter;
        }
      }
    }
    $final_id = substr($final_id, 0, $length);
    return $final_id;
  }

  public function check_link_uid_exist($key)
  {
    $links = Link::where('uid', strtolower($key))->where('status', 1)->get();
    return (count($links[0]) == 0) ? FALSE : TRUE;
  }

  public function check_link_key_exist($key)
  {
    $links = Link::where('ukey', strtolower($key))->where('status', 1)->get();
    return (count($links[0]) == 0) ? FALSE : TRUE;
  }

  public function updateDisAgreeStatus(Request $request)
  {
    $links = Link::where('ukey', strtolower($request->anur_pivc_key))->where('status', 1)->get();

    $link_response = $links[0]['response'];
    $res_arr = json_decode($link_response, true);
    $remarks = ApiController::pivcFullRemarks($res_arr);
    if ($remarks != 'Clear Case') {
      ApiController::setDisAgreeStatus($links[0]['id']);
    }
    //$links=Link::where('proposal_no',$proposal)->update(['disagree_status'=>1]);
    echo ApiController::msg(TRUE, 'Updated the link disagreement status!');
  }


  public function pivcFullRemarks($resArr)
  {
    $arr = array('ePerDet', 'ePerDet_1', 'ePerDet_2', 'ePolDet', 'ePolDet_1', 'ePolDet_2', 'eMedQuest', 'eMedQuest_1', 'eMedQuest_2', 'eBenIll', 'eBenIll_1', 'eBenIll_2', 'eProdBenef', 'eProdBenef_1', 'eProdBenef_2', 'eSmsOtp', 'eSmsOtp_1', 'eSmsOtp_2');

    $ret = '';
    if (!empty($resArr)) {
      $list = array();
      foreach ($arr as $kR => $vR) {
        if (isset($resArr[$vR])) {
          $list[] = $vR;
        }
      }
      if (count($list) == 1 and (in_array('ePerDet', $list) || in_array('ePerDet_1', $list) || in_array('ePerDet_2', $list))) {
        $ret = "Major Address Correction";
      } elseif (count($list) == 1 and (in_array('eMedQuest', $list) || in_array('eMedQuest_1', $list) || in_array('eMedQuest_2', $list))) {
        $ret = "Medical Dispute";
      } elseif (count($list) == 2 and (in_array('ePerDet', $list) || in_array('ePerDet_1', $list) || in_array('ePerDet_2', $list)) and (in_array('eMedQuest', $list) || in_array('eMedQuest_1', $list) || in_array('eMedQuest_2', $list))) {
        $ret = "Medical Dispute";
      } elseif (count($list) >= 1 and (in_array('eMedQuest', $list) || in_array('eMedQuest_1', $list) || in_array('eMedQuest_2', $list)) and ((in_array('eSmsOtp', $list)) || (in_array('eSmsOtp_1', $list)) || (in_array('eSmsOtp_2', $list)))) {
        $ret = "Medical Dispute";
      } elseif (count($list) >= 1) {
        $ret = "Mismatch";
      } else {
        $ret = 'Clear Case';
      }
      unset($list);
    }
    return $ret;
  }

  public function setDisAgreeStatus($id)
  {
    $link = Link::where('id', $id)->update(['disagree_status' => 1]);
    return;
  }

  public function clearDisAgreeStatus($id)
  {
    $link = Link::where('id', $id)->update(['disagree_status' => 0]);
    return;
  }

  public function updateLinkResponse(Request $request)
  {
    $links = Link::where('ukey', strtolower($request->anur_pivc_key))->where('status', 1)->get();
    $link_configKey = $request->anur_pivc_ckey;
    $link_id = $links[0]['id'];
    $configParams = array(
      'page' => $request->anur_pivc_cpage ?: NULL,
      'agree_status' => ($request->anur_pivc_castatus) ? filter_var($request->anur_pivc_castatus, FILTER_VALIDATE_BOOLEAN) : false,
      'created_on' => date('Y-m-d H:i:s')
    );

    $config_response = ApiController::updateLinkResponse_new($link_id, $link_configKey, $configParams);
    echo ApiController::msg(TRUE, 'Updated the link Response!');
  }

  public function updateEditLinkResponse(Request $request)
  {
    $links = Link::where('ukey', strtolower($request->anur_pivc_key))->where('status', 1)->get();
    $link_configKey = $request->anur_pivc_ekey;
    $link_id = $links[0]['id'];
    $configParams = array(
      'page' => $request->anur_pivc_epage ?: NULL,
      'input' => ($request->anur_pivc_edata) ? $request->anur_pivc_edata : false,
      'created_on' => date('Y-m-d H:i:s')
    );
    ApiController::setDisAgreeStatus($link_id);

    $config_response = ApiController::updateLinkResponse_new($link_id, $link_configKey, $configParams);
    echo ApiController::msg(TRUE, 'Updated the link Response!');
  }

  public function updateLinkResponse_new($link_id, $link_configKey, $configParams)
  {

    $link_details = Link::where('id', $link_id)->get();
    if (!empty($link_details)) {
      $link_response = ($link_details[0]['response']);
      $res_arr = array();
      if ($link_details[0]['response'] != '') {
        // echo 'Y';exit; 
        $res_arr = json_decode($link_response, true);

        if (isset($configParams['agree_status']) && $configParams['agree_status']) {

          $ky = ltrim($link_configKey, 'c');
          $edit = 'e' . $ky;
          unset($res_arr[$link_configKey]);
          unset($res_arr[$edit]);

          $stus = ApiController::pivcFullRemarks($res_arr);

          if ($stus == "Clear Case") {
            // echo 'N';exit;
            ApiController::clearDisAgreeStatus($link_id);
          } else {
            ApiController::setDisAgreeStatus($link_id);
            // echo 'Y';exit;
          }
        }
        $res_arr[$link_configKey] = $configParams;
      } else {
        $res_arr[$link_configKey] = $configParams;
      }

      $res_json = json_encode($res_arr);
      // echo "<pre>"; print_r($res_json);exit;
      ApiController::updateResponse($link_id, $res_json);

      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function updateResponse($link_id, $res_json)
  {
    $link = Link::where('id', $link_id)->update(['response' => $res_json]);
    return;
  }

  public function deviceDetails(Request $request)
  {

    $proposal_no = $request->proposal_no;
    $device_name = $request->device_name;
    $nettype = $request->nettype;
    $netrtt = $request->netrtt;
    $netdown = $request->netdown;
    $network = array('device_name' => $device_name, 'type' => $nettype, 'rtt' => $netrtt, 'downlink' => $netdown);

    $agent = ApiController::agent();

    $agentval = json_decode($agent, true);
    $agentval_os = strtolower($agentval["os"]);
    $browser = "";

    if ($agentval_os == "ios") {
      $device_os = $agentval_os;
      $browser = $request->header('User-Agent');
      if (str_contains($browser, "Safari")) {
        $device_os = $agentval_os;
        $browser = "Safari";
      } else {
        $device_os = $agentval_os;
        $browser = "Invalid";
      }
    } elseif ($agentval_os == "androidos" || $agentval_os == "windows") {
      //echo "else and";die;
      $device_os = $agentval_os;
      $browser_details = $request->header('sec-ch-ua');
      if (str_contains($browser_details, "Google Chrome")) {
        $browser = "Chrome";
      } else {
        $device_os = $agentval_os;
        $browser = "Invalid";
        ApiController::logs($proposal_no, $browser_details . ' Browser', '', '', "Success");
      }
    } else {
      $device_os = $agentval_os;
      $browser = "Invalid";
      ApiController::logs($proposal_no, $agentval_os . ' OS', '', '', "Success");
    }

    Link::where('proposal_no', $proposal_no)->update(['device' => $agent, 'network' => json_encode($network), 'browserdetails' => $browser]);
    echo ApiController::msg(TRUE, 'Updated the Device details!');
  }


  public function LangProposalPIVCLink(Request $request)
  {
    $num = '0123456789';
    $characters = '1G' . +$num;
    $randomAlphaNumeric = '';
    $length = 8;

    for ($i = 0; $i < $length; $i++) {
      $randomAlphaNumeric .= $characters[rand(0, strlen($characters) - 1)];
    }

    $anur_pivc_proposal_no = $randomAlphaNumeric;
    $anur_pivc_data = [
      "SOURCE" => "MCONNECT",
      "PROPOSAL_NUMBER" => $anur_pivc_proposal_no,
      "GENDER" => "Male",
      "CUSTOMER_NAME" => "Mr. Hariharan",
      "MOBILE_NUMBER" => "9789102864",
      "DOB" => "18-11-1992",
      "SUM_ASSURED" => "250000",
      "FREQUENCY" => "Single",
      "POLICY_TERM" => "10",
      "PREMIUM_PAYING_TERM" => "5",
      "PRODUCT_NAME" => "SBI Life-Retire Smart",
      "ADDRESS" => "New 123, New City, New Area, Chennai - 600045",
      "PERMANENTPINCODE" => "600044,",
      "PERMANENTSTATE" => "TAMILNADU",
      "EMAIL" => "kumar@anurcloud.com",
      "NOMINEE_NAME" => "Mr.ddu cc",
      "NOMINEE_RELATION" => "Brother",
      "PREFERED_LANG" => "English",
      "SALUTATION" => "Mr.",
      "UIN_NO" => "111L077V03",
      "PREMIUM_AMOUNT" => "200000",
      "PAYMENT_TYPE" => "Single"
    ];
    $post = $anur_pivc_proposal_no;
    $data = $anur_pivc_data;

    try {
      $json = json_encode($data);
      $xml_array = json_decode($json, TRUE);
    } catch (\Exception $e) {
      log_message('KFD_LOG', 'KFD -- xml_data_parse_error --- Error : ' . $e->getMessage());
      return FALSE;
    }

    $linksCheck = Link::where('proposal_no', trim($anur_pivc_proposal_no))->get();
    if (count($linksCheck) > 0) {
      return ('<b>Proposal Already Exists ! </b>');
    } else {

      $link = new Link;
      $link->proposal_no  = trim($anur_pivc_proposal_no);
      $link->params  = json_encode($xml_array);
      $link->expiry  = 45;
      $linkValue = ApiController::linkgen(trim($anur_pivc_proposal_no));
      $short_link = ApiController::shorten(trim($linkValue));


      $link_uid = ApiController::generate_link_uid();
      $link->uid = $link_uid;

      $link_ukey = ApiController::generate_link_key();
      $link->ukey = $link_ukey;

      $link->link = $linkValue;
      $link->link_short = $short_link;
      $link->updated_on = date('Y-m-d H:i:s');

      $link->save();
      $validate_link = $link->link;

      //validate the
      $url = $validate_link;
      $proposal = base64_decode(explode('?', $url)[1]);
      $agent = ApiController::agent();
      $links = Link::where('proposal_no', $proposal)->get();
      if ($links[0]->is_open == 0) {
        Link::where('proposal_no', $proposal)->update(['is_open' => 1, 'is_open_at' => date('Y-m-d H:i:s')]);
      }
      if (!empty($links) and $links[0]->status == 1 and $links[0]->complete_status == 0) {
        $arr = array();
        $arr['status'] = true;
        $arr['expired'] = false;
        $arr['completed'] = false;
        $arr['msg'] = 'Given PIVC URL is valid!';
        $arr['output'] = json_decode($links[0]->params);
        $arr['lkey'] = $links[0]->ukey;
        $arr['flow_key'] = "sbilm_retire_smart";
        $result = json_encode($arr);
      } elseif ($links[0]->complete_status == 1) {
        $arr = array();
        $arr['status'] = false;
        $arr['expired'] = false;
        $arr['completed'] = true;
        $arr['msg'] = 'Given PIVC already Completed';
        $arr['lkey'] = $links[0]->ukey;
        $result = json_encode($arr);
      } elseif ($links[0]->status == 0) {
        $arr = array();
        $arr['status'] = false;
        $arr['expired'] = false;
        $arr['completed'] = false;
        $arr['msg'] = 'Given PIVC URL is Invalid!';
        $arr['lkey'] = $links[0]->ukey;
        $result = json_encode($arr);
      }
      return $arr;
    }
  }

  public function pdfgen()
  {
    // We change the status as completed_status 1 and generate pdf
    $proposal = '2GG18391';
    $vd = Link::where('proposal_no', $proposal)->select('video_url')->get();

    $vd = $vd['0'];
    //$video=json_decode($ vd->video);
    if ($vd != "" and $vd != NULL) {
      //$links=Link::where('proposal_no',$proposal)->update(['complete_status'=>1,'completed_on'=>date('Y-m-d H:i:s'),'status'=>1]);
      return  ApiController::pdfcheck($proposal);
    } else {
      // Log related codes
      $result = json_encode(array(FALSE, 'PIVC complete Failed'));

      echo ApiController::msg(FALSE, 'PIVC complete Failed');
    }
  }
  public function pdfcheck($proposal)
  {
    //$proposal=$request->id;
    $name = $proposal . '.pdf';

    $pdf_path_without_Domain = '/public/upload/' . $proposal . '/pdf/';
    $data_dir = '../data/adc/';
    $img_dir_rel = 'adc/';

    $path = $data_dir . $name;
    $links = Link::where('proposal_no', $proposal)->get();
    $links = $links[0];
    $params = json_decode($links->params);
    $device = json_decode($links->device);
    $network = json_decode($links->network);
    $response = json_decode($links->response);
    //echo "<pre>"; print_r($links);die;
    $data = json_decode($links['reg_photo_url']);

    $pdf = PDF::loadView('pdf', compact('links', 'data', 'params', 'device', 'network', 'response'));
    //Link::where('proposal_no',$proposal)->update(['pdf_url'=>$url]);
    // $pdf->save($path)->stream($name);
    return   $pdf->stream($name);
  }

  public function getFormProposalPIVCLink(Request $request)
  {

    $details =  $request->all();
    $num = '0123456789';
    $characters = '1G' . +$num;
    $randomAlphaNumeric = '';
    $length = 8;

    for ($i = 0; $i < $length; $i++) {
      $randomAlphaNumeric .= $characters[rand(0, strlen($characters) - 1)];
    }

    $anur_pivc_proposal_no = $randomAlphaNumeric;
    $anur_pivc_data = [
      "SOURCE" => "MCONNECT",
      "PROPOSAL_NUMBER" => $anur_pivc_proposal_no,
      "GENDER" => $details['gender'],
      "CUSTOMER_NAME" => $details['name'],
      "MOBILE_NUMBER" => $details['mobile'],
      "DOB" => $details['dob'],
      "SUM_ASSURED" => "250000",
      "FREQUENCY" => $details['payment_frequency'],
      "POLICY_TERM" => $details['policy_term'],
      "PREMIUM_PAYING_TERM" => $details['premium_paying_term'],
      "PRODUCT_NAME" => $details['product_name'],
      "ADDRESS" => $details['address'],
      "PERMANENTPINCODE" => "600044,",
      "PERMANENTSTATE" => "TAMILNADU",
      "EMAIL" => $details['email'],
      "NOMINEE_NAME" => $details['nominee_name'],
      "NOMINEE_RELATION" => $details['relationship'],
      "PREFERED_LANG" => "English",
      "SALUTATION" => "Mr.",
      "UIN_NO" => "111L077V03",
      "PREMIUM_AMOUNT" => $details['premium_amount'],
      "PAYMENT_TYPE" => $details['payment_type']
    ];


    $data = $anur_pivc_data;
    $json = json_encode($data);
    $params = json_decode($json, TRUE);

    $linksCheck = Link::where('proposal_no', $anur_pivc_proposal_no)->get();
    if (count($linksCheck) > 0) {
      return "Proposal Number Already Exists !";
      // return ('<b style="color:red">Proposal Number Already Exists ! </b>');
    } else {

      $link = new Link;
      $link->proposal_no  = $anur_pivc_proposal_no;
      $link->params  = json_encode($params);
      $link->expiry  = 45;
      $linkValue = ApiController::linkgen($anur_pivc_proposal_no);
      $short_link = ApiController::shorten(trim($linkValue));


      $link_uid = ApiController::generate_link_uid();
      $link->uid = $link_uid;

      $link_ukey = ApiController::generate_link_key();
      $link->ukey = $link_ukey;

      $link->link = $linkValue;
      $link->link_short = $short_link;
      $link->link_short = $short_link;
      $link->updated_on = date('Y-m-d H:i:s');
      $link->save();
      return $link->link;

      //return ('<b>'.$link->link.'</br>Link Created Successfully ! </b>');
    }

    // echo "<pre>"; print_r($xml_array);die;

  }


  public function reactivateApi(Request $request)
  {
    // echo "test"; die;
    $reqst = $request->all();
    $jsonString = APIController::decrypt($reqst['ReqPayload']);  

    //$post = explode(",", $jsonString);

    $details = json_decode($jsonString, true);  //print_r($post);die;
    if(!empty($details['Application_Number'])){ 
    //$proposal_no = str_replace("'", "", $details['Application_Number']);
    $proposal_no = $details['Application_Number'];
    //echo $proposal_no;die;
    // print_r($request->proposal_no); die;
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


      $listarchive->setTable('links_archive');
      $listarchive->save();
      $path = public_path('upload/' . $proposal_no . '-' . $listarchive->version);
      $spath = public_path('upload/' . $proposal_no);
      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true, true);
      }
      File::copyDirectory($spath, $path);

      //dd($lists[0]);

      $lists = $lists[0];
      $lists->complete_status = 0;
      $lists->device = NULL;
      $lists->personal_disagree = NULL;
      $lists->policy_disagree = NULL;
      $lists->personal_disagree_response = NULL;
      $lists->personal_agree_response = NULL;
      $lists->policy_disagree_response = NULL;
      $lists->policy_agree_response = NULL;
      $lists->nominee_disagree = NULL;
      $lists->rider_disagree = NULL;
      $lists->callbackres = NULL;
      $lists->completed_on = NULL;
      $lists->is_open = 0;
      $lists->is_open_at = NULL;


      // $lists->disagree_response = NULL;
      $lists->save();

      $trans = Link::where('proposal_no', $proposal_no)->first();

     // $params = json_decode($trans->params, true);
      //$to   = $params['personal_mobile'];
      //$var1 = $params['policy_prod_name'] . '-' . $params['policy_plan'];
      $var2 = $trans->short_link;
     // $message = "Dear customer, you have successfully applied for $var1 . To complete your verification please click on $var2 . In case of any error, please copy paste the link directly to your web browser. Pramerica Life Insurance Limited.";
      //$response = $this->sendSMSPramerica($to, $message);
      //  dd($response);
      //return response()->json(['status' => true, 'link' => $var2], 200);
      $jsonResult = json_encode(['status' => true, 'link' => $var2], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
      } else {
        //return $arr = ["status" => "false"];
        //return response()->json(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
      $jsonResult = json_encode(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
      }
    }
    else{
      $jsonResult = json_encode(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
      //return response()->json(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
    }
  }

  public function sendEmailTest()
  {
    $subject  = ' test mail';
    $to_email = "saravanak@anurcloud.com";
    $to_name  = "test";
    $message1  = "";

    $from_name    =   'test';
    $from_email    =   'test@gmail.com';
    //user   
    $data = array('message1' => $message1);

    Mail::send('mail', $data, function ($message)  use ($to_name, $to_email, $from_name, $from_email) {
      $message->to($to_email, $to_name)->subject('test ');
      $message->from($from_email, $from_name);
    });
  }

  public function genPdf($proposal)
  {

     //$proposal = '53920355';

    $downloadPdf = ApiController::getPDFdownload($proposal);

    $filepath ='';

    if($downloadPdf){
              if(isset($downloadPdf)){
          
                $decoderesponse = json_decode($downloadPdf);

                ///dd($proposal);

                if(isset($decoderesponse->path)){
 
                    $fullPath = $decoderesponse->path;

                   // dd($fullPath);

                   $filepath = str_replace('D:\\vc\\portal\\public\\', '', $fullPath);

                   $relativePath = str_replace('\\', '/', $filepath);

                   $filepath = $this->uaturl.$relativePath; //dd($filepath);

                   ApiController::logs($proposal,'GetPDFPathApi', '',json_encode($decoderesponse->path), "success");

                   $filename = $proposal;
                   return redirect()->away($filepath);

                }
                else if(isset($decoderesponse->error) || $decoderesponse==null){ //dd('dfdf');

                     //ApiController::genPdf($proposal);

                     ApiController::logs($proposal,'GetPDFPathApi', '', json_encode($decoderesponse), "fail");

                     $name = $proposal . '.pdf';
                    $path = public_path('upload/' . $proposal . '/') . $name;  //dd($path);
                    $links = Link::where('proposal_no', $proposal)->get();
                    $data = $links[0];
                    $pdf = '';
                    $policydisagreeResult = [];
                    $personaldisagreeResult = [];
                    //$link = new Link;

                          $json1 = json_decode($data->params, true);
                          if($data->policy_disagree==1){
                            $policy_disagree = $data->policy_disagree_response;
                            $json2 = json_decode($policy_disagree, true);
                            $nwjson1=$json1['plan_details'];
                            $policydisagreeResult = $this->compareJson($json2,$nwjson1);

                            //print_r($json2);

                            //dd($nwjson1);

                            $policydisagreeResult['statementdisagree'] = "Yes";
                            
                            $data->rider_disagree = json_encode($policydisagreeResult);   
                            
                            
                              
                            if(isset($policydisagreeResult['Rider_details'])){
                              foreach ($policydisagreeResult['Rider_details'] as $key => $valueride) {

                                //$policydisagreeResult[]=$valueride;
                                //print_r($valueride['Rider_name']);

                                if(isset($valueride['Rider_name'])){
                                  $policydisagreeResult['Rider_name'.$key] = $valueride['Rider_name'];
                                }
                                
                                if(isset($valueride['Rider_Sum_Assured'])){
                                  $policydisagreeResult['Rider_Sum_Assured'.$key] = $valueride['Rider_Sum_Assured'];
                                  unset($policydisagreeResult['screen'], $policydisagreeResult['status']);
                                  unset($policydisagreeResult['Rider_details']);
                                }
                              
                                
                              }
                              //print_r($policydisagreeResult);
                            }
                            
                          }
                          
                          if($data->personal_disagree==1){
                          
                            unset($json1['plan_details']);
                            $personal_disagree = $data->personal_disagree_response;
                            $json2 = json_decode($personal_disagree, true); 
                            $personaldisagreeResult = $this->compareJson($json2,$json1);

                          // dd($personaldisagreeResult);

                            if($data->medical_checked_response=="yes"){
                              $personaldisagreeResult['medicaldiscrpncy'] = $data->medical_checked_response; 
                            }

                            $data->nominee_disagree = json_encode($personaldisagreeResult);

                            
                            if(isset($personaldisagreeResult['Nominee_details'])){
                              foreach ($personaldisagreeResult['Nominee_details'] as $key => $valuenom) {
                              // dd($valuenom);
                                if(isset($valuenom['Nominee_name'])){
                                  $personaldisagreeResult['Nominee_name'.$key] = $valuenom['Nominee_name'];
                                  //print_r($personaldisagreeResult);
                                }
                                if(isset($valuenom['Nominee_dob'])){
                                  $personaldisagreeResult['Nominee_dob'.$key] = $valuenom['Nominee_dob'];
                                }
                                
                              }
                              unset($personaldisagreeResult['screen'], $personaldisagreeResult['status']);
                              unset($personaldisagreeResult['Nominee_details']);
                            }
                            
                          }  
                          //dd($personaldisagreeResult, $policydisagreeResult);
                          $data->save();
                    $location = json_decode($data->location, true); //dd($location);

                    $longitude = (isset($location['lat'])) ? $location['lat'] : '';
                    $latitude = (isset($location['long'])) ? $location['long'] : '';
                    $address_disp = '';
                    $city = '';
                    
//                     if($data->sys_lang=='kan'){//dd('ddf');
//                       $pass_data['data'] = $data;
//                       $pass_data['address_disp'] = $address_disp;
//                       $pass_data['policydisagreeResult'] = $policydisagreeResult;
//                       $pass_data['personaldisagreeResult'] = $personaldisagreeResult;  
//                     //  $html = view('genpdf', $pass_data)->render();
// //echo ($html);die;   // or dump($html);
//                       //$html = view('genpdf', compact('data', 'address_disp', 'policydisagreeResult', 'personaldisagreeResult'))->render();
//                      // $pdf = ZPDF::loadView('genpdf', $pass_data); 
                    
//                         $pdf = BPDF::loadView('genpdf',compact('data', 'address_disp', 'policydisagreeResult', 'personaldisagreeResult'));
//                         return $pdf->download('kannada-doc.pdf');

//                       return $pdf->download('kannada-doc.pdf');
//                     }

                    $mpdf = new Mpdf([
                      'default_font' => 'TimesNewRoman',
                      'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                          storage_path('fonts'),
                      ]),
                      'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                          'FreeSerif' => ['R' => 'FreeSerif.otf'],
                          'DejaVu' => ['R' => 'DejaVu Serif Condensed.ttf'],
                          'TimesNewRoman' => ['R' => 'times new roman.ttf'],
                          'kannada' => ['R' => 'NotoSansKannadat.ttf'],
                          'telugu' => ['R' => 'telugu.ttf'], 
                          'gujarati' => ['R' => 'Shree-Guj-0768.ttf'],
                          //'bengali' => ['R' => 'NotoSansBengali-Regular.ttf'],
                          //'marathi' => ['R' => 'NotoSansDevanagari-Regular.ttf'],
                          'default_font' => 'TimesNewRoman',
                      ],
                  ]);
                  
                    $html = view('genpdf', compact('data', 'address_disp', 'policydisagreeResult', 'personaldisagreeResult'))->render();

                
                    //echo $html; die;
                    // Write HTML to PDF
                    $mpdf->WriteHTML($html);
                    

                    // Save PDF file
                    File::put($path, $mpdf->Output('', 'S')); // 'S' returns PDF as a string

                    //$base64Pdf = base64_encode($path); dd($base64Pdf);

                    // Retrieve the file
                    return response()->file($path);
                }

     }
    }
  }


   
  public function retriggerApi(Request $request)
  {
     //echo "test"; die;
    $reqst = $request->all();
    $jsonString = APIController::decrypt($reqst['ReqPayload']);  

      //$post = explode(",", $jsonString);

      $details = json_decode($jsonString, true);  //print_r($post);die;
    if(!empty($details['Application_Number'])){ 
    $proposal_no = $details['Application_Number'];
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


      //$params = json_decode($trans->params, true);

      //$to   = $params['personal_mobile'];
      //$var1 = $params['policy_prod_name'] . '-' . $params['policy_plan'];
      $var2 = $trans->short_link;//dd($var2);
      //$message = "Dear customer, you have successfully applied for $var1 . To complete your verification please click on $var2 . In case of any error, please copy paste the link directly to your web browser. Pramerica Life Insurance Limited.";
      //$response = $this->sendSMSPramerica($to, $message);
      //  dd($response);
      // return $arr = ["status" => "true", "msg" => "reactivated"];

      //return response()->json(['status' => true, 'link' => $var2], 200);
      $jsonResult = json_encode(['status' => true, 'link' => $var2], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    } else {
      //return $arr = ["status" => "false"];
      //return response()->json(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
      $jsonResult = json_encode(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    }
  }
  else{
    $jsonResult = json_encode(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    //return response()->json(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
  }
  }

  public function fedostatus(Request $request)
  {
    try {
      if ($request->hasHeader('api-client') and $request->hasHeader('x-api-key')) {
        $api_client = $request->header('api-client');
        $api_key = $request->header('x-api-key');

        // echo 'test ->>>'.$api_client; die;

        if ($api_client != 'fedo' or $api_key != '9RDLFA-DKYD7-ZCVBN4TC4-3LIA3') {
          $res_msg = array("msgCode" => "500", "msg" => "Failure", "msgDescription" => "There seems to be something wrong. Please try after sometime");
          $res['response'] = array("header" => [], "msgInfo" => $res_msg);
          return response()->json($res);
        }
      } else {
        $res_msg = array("msgCode" => "500", "msg" => "Failure", "msgDescription" => "There seems to be something wrong. Please try after sometime");
        $res['response'] = array("header" => [], "msgInfo" => $res_msg);

        return response()->json($res);
      }
      if (!empty($request)) {
        $arr = array();
        $arr['status'] = $request->status;
        $arr['message'] = $request->message;
        $arr['tenantID'] = $request->tenantID;
        $arr['clientID'] = $request->clientID;
        $arr['scanID'] = $request->scanID;
        $arr['customerID'] = $request->customerID;
        $status = 'Success';
        $req = $request->all();

        $link = Link::where('proposal_no', $request->customerID)->first();
        if (!empty($link)) {
          $link->fedo_status = $request->status;
          $link->save();
        } else {
          return response()->json(['status' => 'Error', 'message' => 'Invalid Proposal Number'], 400);
        }


        $fedo_logs = new Fedologs;
        $fedo_logs->app_no = $request->customerID;
        $fedo_logs->module = 'fedo_' . $request->status;
        $fedo_logs->request = json_encode($req);
        $fedo_logs->status = $status;
        $fedo_logs->save();
        return response()->json(['status' => 'Success', 'message' => 'Status updated successfully']);
      }
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }


  public function fedoVitalsApi(Request $request)
  {
    try {
      if ($request->hasHeader('api-client') and $request->hasHeader('x-api-key')) {
        $api_client = $request->header('api-client');
        $api_key = $request->header('x-api-key');

        // echo 'test ->>>'.$api_client; die;

        if ($api_client != 'fedo' or $api_key != '9RDLFA-DKYD7-ZCVBN4TC4-3LIA3') {
          $res_msg = array("msgCode" => "500", "msg" => "Failure", "msgDescription" => "There seems to be something wrong. Please try after sometime");
          $res['response'] = array("header" => [], "msgInfo" => $res_msg);
          return response()->json($res);
        }
      } else {
        $res_msg = array("msgCode" => "500", "msg" => "Failure", "msgDescription" => "There seems to be something wrong. Please try after sometime");
        $res['response'] = array("header" => [], "msgInfo" => $res_msg);

        return response()->json($res);
      }
      // dd($request);
      if (!empty($request)) {

        foreach ($request->all() as $apiSingle) {

          $link = Link::where('proposal_no', $apiSingle['customerID'])->first();
          if (!empty($link)) {
            $link->fedo_vitals = json_encode($apiSingle);
            $link->save();
          }

          $fedo_logs = new Fedologs;
          $fedo_logs->app_no = $apiSingle['customerID'];
          $fedo_logs->module = 'fedo_vitals';
          $fedo_logs->request = json_encode($apiSingle);
          $fedo_logs->status = $apiSingle['status'];
          $fedo_logs->save();
        }
      }
      return response()->json(['status' => 'Success', 'message' => 'Status updated successfully']);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }


  public function update_Questions(Request $request)
  {

    $data = APIController::decrypt($request->data);

    $data = json_decode($data, true);
    try {


      $proposal_no = $data['proposal_no'];

      $questions = $data['questions'];
      $links = Link::where('proposal_no', $proposal_no)->first();
      $links->questions = json_encode($questions);
      $links->save();
      $arr =  array('Status' => true, "Message" => "Question Updated");

      ApiController::logs($proposal_no, 'update_Questions', json_encode($data), "", "Success");

      $enc_data = json_encode($arr, JSON_FORCE_OBJECT);
      ApiController::logs($proposal_no, 'Medica Questions', '', '', "Success");
      return APIController::encrypt($enc_data);
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($data['proposal_no'], 'update_QuestionsFailed', json_encode($data), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }
  public function update_Page(Request $request)
  {

    $data = APIController::decrypt($request->data);

    $data = json_decode($data, true);
    $proposal_no = $data['proposal_no'];
    try {

      
      $page_name = $data['page_name'];
      $page_res = $data['page_res'];
      ApiController::logs($proposal_no, $page_name, '',  $page_res, "Success");
      $arr =  array('Status' => true, "Message" => " Updated");
      $enc_data = json_encode($arr, JSON_FORCE_OBJECT);
      return APIController::encrypt($enc_data);
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($data['proposal_no'], 'update_PageFailed', json_encode($data), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }

  public function IND_money_format($number){
    $decimal = (string)($number - floor($number));
    $money = floor($number);
    $length = strlen($money);
    $delimiter = '';
    $money = strrev($money);

    for($i=0;$i<$length;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
            $delimiter .=',';
        }
        $delimiter .=$money[$i];
    }

    $result = strrev($delimiter);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);

    if( $decimal != '0'){
        $result = $result.$decimal;
    }

    return $result;
}
 

  public function linkValidation($val)
  {
   
    //$array = json_decode($val, true);

  $validator = Validator::make($val, [
          "Application_Number" => 'required|max:50',
          //"Proposer_name" => 'required|string|max:30',
          "Life_assured_name" => 'required',
          "Life_assured_dob" => 'required',
          "Mobile_number"=> 'required|numeric|digits:10',
          //"Email_id" => 'required',
          "Address" => 'required|max:125',
          "plan_details.Plan_Name" => 'required',
          "plan_details.Sum_Assured" => 'required',
          //"plan_details.Policy_Term" => 'required',
          //"plan_details.Frequency_Of_Premium_Payment" => 'required',
          "plan_details.Premium_Amount" => 'required',
          "plan_details.Premium_Payment_Term" => 'required',
          //"image_base64" => 'required',
  ],
  [
        "Application_Number.required" => "Mandatory Field Not Available - Application Number.",
        //"Proposer_name.required" => "Mandatory Field Not Available - Proposer Name.",
        "Mobile_number.numeric" => "Mobile Number Must Be A Numeric Value  - Mobile Number.",
        "Application_Number.max" => "Application Number Must Be Exactly 15 in Length  - Application Number.",
        "Mobile_number.digit" => "Mobile Number Must Be Exactly 10 Digits  - Mobile Number.",
        "Address.digit" => "Address Has Exceeded The Maximum Of 125 Characters - Address.",
        "Life_assured_name.required" => "Mandatory Field Not Available - Life Assured Name.",
        "Life_assured_dob.required" => "Mandatory Field Not Available - Life Assured DOB.",
        "Mobile_number.required" => "Mandatory Field Not Available - Mobile Number.",
        //"Email_id.required" => "Mandatory Field Not Available - E-mail.",
        "Address.required" => "Mandatory Field Not Available - Address.",
        "plan_details.Plan_Name.required" => "Mandatory Field Not Available - Plan Name.",
        "plan_details.Sum_Assured.required" => "Mandatory Field Not Available - Sum Assured.",
        //"plan_details.Policy_Term.required" => "Mandatory Field Not Available - Policy Term.",
        //"plan_details.Frequency_Of_Premium_Payment.required" => "Mandatory Field Not Available - Frequency Of Premium Payment.",
        "plan_details.Premium_Amount.required" => "Mandatory Field Not Available - Premium Amount.",
        "plan_details.Premium_Payment_Term.required" => "Mandatory Field Not Available - Premium Payment Term.",
        //"Proposer_name.max" => "Proposer name cannot exceed the maximum length of 30 characters - Proposer Name.",
        //"image_base64.required" => "Mandatory Field Not Available - Photo.",
  
    ]);

     if ($validator->fails()) { 

            $errorCodes = [
              "Application_Number.Required" => 602,
             // "Proposer_name.Max" => 603,
              "Mobile_number.Numeric" => 604,
              "Application_Number.Max" => 605,
              "Mobile_number.Digits" => 606,
              "Address.Max" => 608,
              //"Proposer_name.Required" => 602,
              "Life_assured_name.Required" => 602,
              "Life_assured_dob.Required" => 602,
              //"Email_id.Required" => 602,
              "Address.Required" => 602,
              "plan_details.Plan_Name.Required" => 602,
              "plan_details.Sum_Assured.Required" => 602,
              //"plan_details.Policy_Term.Required" => 602,
              //"plan_details.Frequency_Of_Premium_Payment.Required" => 602,
              "plan_details.Premium_Amount.Required" => 602,
              "plan_details.Premium_Payment_Term.Required" => 602,
              //"image_base64.Required" => 602,
          ];

          $failedRules = $validator->failed();  

          foreach ($validator->errors()->messages() as $field => $messages) {//die;

            foreach ($messages as $message) { 
        
              if (isset($failedRules[$field])) {
                $ruleKey = array_key_first($failedRules[$field]);  // Get first failed rule
                $fullRuleKey = $field . '.' . $ruleKey;  // e.g., "Application_Number.required"
               // echo $fullRuleKey;
                $errorCode =  $errorCodes[$fullRuleKey]; 
              }
                $errorsWithCodes[] = [
                    "status" => false,
                    "error_code" => $errorCode,
                    "message" => $message,
                ];
                
            }
        }
        //dd($errorsWithCodes);
          return json_encode([
              $errorsWithCodes
          ], 200);
      }
      return json_encode([
        'status' => 'success',
        'error_code' => 200,
        //'message' => 'Validation Passed',
        //'link' => 'https://', 
    ], 200);

    

  }

  public function callbackurl($proposal)
  //public function callbackurl(Request $request)
  {


     //dd($request->proposal_no);

    // $proposal_no = $request->proposal_no;

     $spchscore='';
     $proposal_no = $proposal;

     $link = Link::where('proposal_no', $proposal_no)->first();

    //dd($link['speech_res']);

     if (strpos($link['speech_res'], "\r\n\r\n") !== false) {
            // Split headers and body
            list(, $body) = explode("\r\n\r\n",$link['speech_res'], 2);
            } else {
                // It's plain JSON
                $body = $link['speech_res'];
            }

        // Decode the JSON stored in speech_response
        $speechResponse = json_decode($body, true); //dd($speechResponse);

        $spchscore = $speechResponse['score'];

    $linkdcode = json_decode($link['params']); //dd($link);

    $callbacklink = $linkdcode->callbackurl; //dd($callbacklink);

    //$callbacklink = 'IU1K5DUqCr8hLIV7tWa4xAMxrFtTUrn4K0qmZV0KSc3nU/YMXyKtROLURo0FfdkJRfBv5vEogWC+BTa/8QpD0g==';

    $callbckurl = APIController::decrypt($callbacklink);

    //$details = json_decode($callurl, true);  
    //dd($callbckurls);

    //$callbckurl = $callurl['callbackurl'];

    

    if ($link->complete_status == 0) {
      $complete_status =  "Fail";
    } else{
      $complete_status =  "Pass";
    }

     //$speechres = json_decode($link['speech_res']);

      if($link['policy_disagree']==1){
        $plicydisresp = json_decode($link['rider_disagree']);
        //dd($plicydisresp->Rider_details);
      }

     
 

      $Application_Number = $linkdcode->Application_Number;
      $Face_Match = $link['face_response']==null || $link['face_score']<40 ? "Failure" : "Success";
      $Face_Match_Per = $link['face_score']==null ? 0 : (number_format($link['face_score'], 1) . '%');
      //dd($speechres);
      $Speech_Score = isset($spchscore)?$spchscore:null;
      $Speech_Text_Match = isset($speechResponse['match'])?$speechResponse['match']=="true"?"Yes":"No":null;
      $Agreement_Status = ($link['policy_disagree']==0 && $link['personal_disagree']==0)?"Yes":"No";
      $Location = json_decode($link['location'], true);
      //$Location = $link['location'];
      //print_r($Location);die;
      
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_encode($link['nominee_disagree']):null; 
      $Personal_Details_Disagreement = isset($link['nominee_disagree']) ? 'Disagree':null; 
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_decode($link['nominee_disagree'],true):null; 
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_encode($link['rider_disagree']):null;
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_decode($link['rider_disagree'],true):null;
      $Plan_Details_Disagreement = isset($link['rider_disagree']) ? 'Disagree':null;
     // $Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?$plicydisresp->Rider_details:null;
      $Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?'Disagree':null;
      $Health_Medical_Disagreement = isset($link['medical_checked_response'])?$link['medical_checked_response']:null;
      $Timestamp = $link['completed_on'];
      $Final_Result = $complete_status;

     $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');

     if(!file_exists($imagePath)){

             $fullPath = APIController::getDocsdownloadApi($Application_Number);  
              
                      //$fullPath = $decoderesponse->path;
                    // Example: D:\vc\portal\public\upload\53923254

                    // Remove only the public base folder
                    $relative = str_replace('D:\\vc\\portal\\public\\', '', $fullPath);

                    // Convert to URL-safe slashes
                    $relative = str_replace('\\', '/', $relative);

                    // Append image
                    $relative = rtrim($relative, '/') . '/img/video_consent.jpeg';

                    // Build final URL
                    $imagePath = rtrim($this->uaturl, '/') . '/' . ltrim($relative, '/');

     }else{
      $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');
     }

     
                    //$imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);

      $imageData = isset($imagePath)?base64_encode(file_get_contents($imagePath)):null;//dd($imageData);
      $bawc_imgBase64 =  isset($imageData)?$imageData:null;

      //$bawc_imgBase64 =  null;

    //   $postData = [
    //     "TransactionId" => "12345",
    //     "ResPayload" => [
    //         "Application_Number" => $Application_Number,
    //         "Face_Match" => $Face_Match,
    //         "Face_Match_Per" => $Face_Match_Per,
    //         "Speech_Text_Match" => $Speech_Text_Match,
    //         "Agreement_Status" => $Agreement_Status,
    //         "Location" => $Location, // <-- Correct here
    //         "Personal_Details_Disagreement" => $Personal_Details_Disagreement,
    //         "Plan_Details_Disagreement" => $Plan_Details_Disagreement,
    //         "Rider_Details_Disagreement" => $Rider_Details_Disagreement,
    //         "Health_Medical_Disagreement" => $Health_Medical_Disagreement,
    //         "Timestamp" => $Timestamp,
    //         "Final_Result" => $Final_Result,
    //         "bawc_imgBase64" => $bawc_imgBase64
    //     ],
    //     "Source" => "SUD"
    // ];


    $LocationStr = '"Location":"' .json_encode($Location) . '"';
      if($Location==null){
        $LocationStr = '"Location":null';
      }

      $Plan_Details_DisagreementStr = '"Plan_Details_Disagreement":"' .$Plan_Details_Disagreement. '"';//json_encode($Plan_Details_Disagreement) . '"';
      if($Plan_Details_Disagreement==null){
        $Plan_Details_DisagreementStr = '"Plan_Details_Disagreement":null';
      }

      $Rider_Details_DisagreementStr = '"Rider_Details_Disagreement":"' .$Rider_Details_Disagreement. '"'; //json_encode($Rider_Details_Disagreement) . '"';
      if($Rider_Details_Disagreement==null){
        $Rider_Details_DisagreementStr = '"Rider_Details_Disagreement":null';
      }


      $Personal_Details_DisagreementStr = '"Personal_Details_Disagreement":"' .$Personal_Details_Disagreement. '"';//json_encode($Personal_Details_Disagreement) . '"';
      if($Personal_Details_Disagreement==null){
        $Personal_Details_DisagreementStr = '"Personal_Details_Disagreement":null';
      }


      $ResPayload  = '{"Application_Number":"' .$Application_Number. '","Face_Match":"' .$Face_Match. '","Face_Match_Per":"' .$Face_Match_Per .'","Speech_Text_Match":"' .$Speech_Text_Match. '","Speech_Score":"' .$spchscore. '","Agreement_Status":"' .$Agreement_Status. '",'.$LocationStr.','.$Plan_Details_DisagreementStr.','.$Rider_Details_DisagreementStr.',
      '.$Personal_Details_DisagreementStr.',"Health_Medical_Disagreement":"'.$Health_Medical_Disagreement. '","Timestamp":"' .$Timestamp. '","Final_Result":"' .$Final_Result. '","bawc_imgBase64":"' .$bawc_imgBase64. '"}';
      
      //  $ResPayload = json_decode($ResPayload);
      //$ResPayload = serialize($ResPayload);
      //dd($ResPayload);
      
      $postData = [
        "TransactionId" => "12345",
        "ResPayload" => $ResPayload,
        "Source" => "SUD"
    ];

    //print_r($postData);die;
    // 4. Then encode everything for cURL
    //$jsonData = json_encode($postData);

    //print_r($jsonData);die;
    //$postData = '"'.json_encode($postData).'"';
    //$link->callbackres=json_encode($postData);
    //$link->save();
    ApiController::logs($proposal_no,'callbackurlreq',json_encode($postData),"", "Success");

    

    return $postData;
    die;
  }

public function callbackurl_0705($proposal)
  {


     $proposal_no = $proposal;

     $link = Link::where('proposal_no', $proposal_no)->first();

    $linkdcode = json_decode($link['params']); //dd($linkdcode);

    $callbacklink = $linkdcode->callbackurl; //dd($callbacklink);

    //$callbacklink = 'IU1K5DUqCr8hLIV7tWa4xAMxrFtTUrn4K0qmZV0KSc3nU/YMXyKtROLURo0FfdkJRfBv5vEogWC+BTa/8QpD0g==';

    $callbckurl = APIController::decrypt($callbacklink);

    //$details = json_decode($callurl, true);  
    //dd($callbckurls);

    //$callbckurl = $callurl['callbackurl'];

    

    if ($link->complete_status == 0) {
      $complete_status =  "Fail";
    } else{
      $complete_status =  "Pass";
    }

      if($link['policy_disagree']==1){
        $plicydisresp = json_decode($link['rider_disagree']);
        //dd($plicydisresp->Rider_details);
      }

      $speechres = json_decode($link['speech_res']);
 

      $Application_Number = $linkdcode->Application_Number;
      $Face_Match = $link['face_response']==null || $link['face_score']<40 ? "Failure" : "Success";
      $Face_Match_Per = $link['face_score']==null ? 0 : (number_format($link['face_score'], 1) . '%');
      //dd($speechres);
      $Speech_Text_Match = isset($speechres->match)?$speechres->match=="true"?"Yes":"No":null;
      $Agreement_Status = ($link['policy_disagree']==0 && $link['personal_disagree']==0)?"Yes":"No";
      $Location = json_decode($link['location'], true);
      //$Location = $link['location'];
      //print_r($Location);die;
      
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_encode($link['nominee_disagree']):null; 
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? 'Disagree':'Agree'; 
      $Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_decode($link['nominee_disagree'],true):null; 
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_encode($link['rider_disagree']):null;
      $Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_decode($link['rider_disagree'],true):null;
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? 'Disagree':'Agree';
      $Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?$plicydisresp->Rider_details:null;
      //$Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?'Disagree':'Agree';
      $Health_Medical_Disagreement = isset($link['medical_checked_response'])?$link['medical_checked_response']:null;
      $Timestamp = $link['completed_on'];
      $Final_Result = $complete_status;

      $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');
      $imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);
      $bawc_imgBase64 =  $imageData;


      // $ResPayload  = "{'Application_Number':'" .$Application_Number. "','Face_Match':'" .$Face_Match. "','Face_Match_Per':'" .$Face_Match_Per ."','Speech_Text_Match':'" .$Speech_Text_Match. "','Agreement_Status':'" .$Agreement_Status. "','Location':'" .json_encode ($Location) . "','Plan_Details_Disagreement':'".json_encode($Plan_Details_Disagreement). "','Rider_Details_Disagreement':'" .json_encode($Rider_Details_Disagreement). "',
      // 'Personal_Details_Disagreement':'" .json_encode($Personal_Details_Disagreement). "','Health_Medical_Disagreement':'".$Health_Medical_Disagreement. "','Timestamp':'" .$Timestamp. "','Final_Result':'" .$Final_Result. "','bawc_imgBase64':'" .$bawc_imgBase64. "'}";
      
      // $ResPayload  = '{"Application_Number":"' .$Application_Number. '","Face_Match":"' .$Face_Match. '","Face_Match_Per":"' .$Face_Match_Per .'","Speech_Text_Match":"' .$Speech_Text_Match. '","Agreement_Status":"' .$Agreement_Status. '","Location":"' .json_encode($Location) . '","Plan_Details_Disagreement":"' .json_encode($Plan_Details_Disagreement). '","Rider_Details_Disagreement":"' .json_encode($Rider_Details_Disagreement). '",
      // "Personal_Details_Disagreement":"' .json_encode($Personal_Details_Disagreement). '","Health_Medical_Disagreement":"'.$Health_Medical_Disagreement. '","Timestamp":"' .$Timestamp. '","Final_Result":"' .$Final_Result. '","bawc_imgBase64":"' .$bawc_imgBase64. '"}';


      
      // $LocationStr = "'Location':" .json_encode($Location)  ;
      $LocationStr = "'Location':{'application_number': '".$Location['application_number']."','lat': '".$Location['lat']."','syslang': '".$Location['syslang']."','_long': '".$Location['_long']."'}" ;
      if($Location==null){
        $LocationStr = "'Location':null";
      } 
      $Plan_Details_DisagreementStr = '"Plan_Details_Disagreement":' .json_encode($Plan_Details_Disagreement) . '';
      if($Plan_Details_Disagreement==null){
        $Plan_Details_DisagreementStr = '"Plan_Details_Disagreement":null';
      }

      $Rider_Details_DisagreementStr = '"Rider_Details_Disagreement":' .json_encode($Rider_Details_Disagreement) . '';
      if($Rider_Details_Disagreement==null){
        $Rider_Details_DisagreementStr = '"Rider_Details_Disagreement":null';
      }


      $Personal_Details_DisagreementStr = '"Personal_Details_Disagreement":' .json_encode($Personal_Details_Disagreement) . '';
      if($Personal_Details_Disagreement==null){
        $Personal_Details_DisagreementStr = '"Personal_Details_Disagreement":null';
      }


      // $ResPayload  = '{"Application_Number":"' .$Application_Number. '","Face_Match":"' .$Face_Match. '","Face_Match_Per":"' .$Face_Match_Per .'","Speech_Text_Match":"' .$Speech_Text_Match. '","Agreement_Status":"' .$Agreement_Status. '",'.$LocationStr.','.$Plan_Details_DisagreementStr.','.$Rider_Details_DisagreementStr.',
      // '.$Personal_Details_DisagreementStr.',"Health_Medical_Disagreement":"'.$Health_Medical_Disagreement. '","Timestamp":"' .$Timestamp. '","Final_Result":"' .$Final_Result. '","bawc_imgBase64":"' .$bawc_imgBase64. '"}';

      // $ResPayload  = '{"Application_Number":"' .$Application_Number. '","Face_Match":"' .$Face_Match. '","Face_Match_Per":"' .$Face_Match_Per .'","Speech_Text_Match":"' .$Speech_Text_Match. '","Agreement_Status":"' .$Agreement_Status. '",'.$LocationStr.','.$Plan_Details_DisagreementStr.','.$Rider_Details_DisagreementStr.',
      // '.$Personal_Details_DisagreementStr.',"Health_Medical_Disagreement":"'.$Health_Medical_Disagreement. '","Timestamp":"' .$Timestamp. '","Final_Result":"' .$Final_Result. '","bawc_imgBase64":"' .$bawc_imgBase64. '"}';

      $ResPayload  = "{'Application_Number':'" .$Application_Number. "','Face_Match':'" .$Face_Match. "','Face_Match_Per':'" .$Face_Match_Per ."','Speech_Text_Match':'" .$Speech_Text_Match. "','Agreement_Status':'" .$Agreement_Status. "',".$LocationStr.",".$Plan_Details_DisagreementStr.",".$Rider_Details_DisagreementStr.",
      ".$Personal_Details_DisagreementStr.",'Health_Medical_Disagreement':'".$Health_Medical_Disagreement. "','Timestamp':'" .$Timestamp. "','Final_Result':'" .$Final_Result. "','bawc_imgBase64':'" .$bawc_imgBase64. "'}";
      

      //  $ResPayload = json_decode($ResPayload);
      
      $postData = [
        "TransactionId" => "12345",
        "ResPayload" => $ResPayload,
        "Source" => "SUD"
    ];
    //   $postData = [
    //     "TransactionId" => "12345",
    //     "ResPayload" => [
    //         "Application_Number" => $Application_Number,
    //         "Face_Match" => $Face_Match,
    //         "Face_Match_Per" => $Face_Match_Per,
    //         "Speech_Text_Match" => $Speech_Text_Match,
    //         "Agreement_Status" => $Agreement_Status,
    //         "Location" => $Location, // <-- Correct here
    //         "Personal_Details_Disagreement" => $Personal_Details_Disagreement,
    //         "Plan_Details_Disagreement" => $Plan_Details_Disagreement,
    //         "Rider_Details_Disagreement" => $Rider_Details_Disagreement,
    //         "Health_Medical_Disagreement" => $Health_Medical_Disagreement,
    //         "Timestamp" => $Timestamp,
    //         "Final_Result" => $Final_Result,
    //         "bawc_imgBase64" => $bawc_imgBase64
    //     ],
    //     "Source" => "SUD"
    // ];
    //print_r($postData);die;
    // 4. Then encode everything for cURL
    //$jsonData = json_encode($postData);

    //print_r($jsonData);die;
    // $postData['ResPayload'] = '"'.json_encode($postData['ResPayload']).'"';
    $link->callbackres=json_encode($postData);
    $link->save();
    ApiController::logs($proposal_no,'callbackurlreq',json_encode($postData),"", "Success");

    

    return $postData;
    die;


    //dd($jsonData);
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $callbckurl,  
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $jsonData,
     CURLOPT_SSL_VERIFYPEER =>false,
     CURLOPT_SSL_VERIFYHOST =>false,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    

    $response = curl_exec($curl);

    // $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    // if ($httpCode != 200) {
    //     echo "HTTP Response Code: $httpCode\n";
    //     echo "Response: $response\n";
    // }
    
   
  //print_r($response);
//dd($response);
    $link->callbackres=json_encode($response);
    $link->save();
    ApiController::logs($proposal_no,'callbackurl',$jsonData,json_encode($response), "Success");
    
    //$proposal_no, $mod, $req, $res, $status
    //die;
    return response()->json(['TransactionId'=>'12345','ResPayload'=>APIController::encrypt($response),'Source'=>'SUD']);
   
    curl_close($curl);
    
  }


//   public function compareJson($array1, $array2) {
//     $difference = [];

//     foreach ($array1 as $key => $value) {
//         if (array_key_exists($key, $array2)) {
//             if (is_array($value)) {
//                 if (!is_array($array2[$key])) {
//                     $difference[$key] = $value;
//                 } else {
//                     $new_diff = $this->compareJson($value, $array2[$key]);
//                     if (!empty($new_diff)) {
//                         $difference[$key] = $new_diff;
//                     }
//                 }
//             } else {
//                 if ($value !== $array2[$key]) {
//                     $difference[$key] = $value;
//                 }
//             }
//         } else {
//             $difference[$key] = $value;
//         }
//     }

//     return $difference;
// }

public function compareJson($array1, $array2) {
  $difference = [];

  foreach ($array1 as $key => $value) {
      if (array_key_exists($key, $array2)) {
          if (is_array($value)) {
              if (!is_array($array2[$key])) {
                  $difference[$key] = $value;
              } else {
                  $new_diff = $this->compareJson($value, $array2[$key]);
                  if (!empty($new_diff)) {
                      $difference[$key] = $new_diff;
                  }
              }
          } else {
              // Normalize both values before comparison
              $normalized1 = $this->normalizeValue($value);
              $normalized2 = $this->normalizeValue($array2[$key]);

              if ($normalized1 !== $normalized2) {
                  $difference[$key] = $value;
              }
          }
      } else {
          $difference[$key] = $value;
      }
  }

  return $difference;
}

// Helper function to normalize values
private function normalizeValue($value) {
  // Remove rupee symbol and commas
  $value = str_replace(['₹', ','], '', $value);

  // Remove country code (e.g., +91)
  $value = preg_replace('/^\+91\s?/', '', $value);

  // Remove any remaining non-digit characters if it's a phone number
  if (preg_match('/^\d{10}$/', preg_replace('/\D/', '', $value))) {
      $value = substr(preg_replace('/\D/', '', $value), -10);
  }

  return trim($value);
}

public function singleReactivate(Request $request)
  {
    // echo "test"; die;
    $details = $request->all();

    $proposal_no = str_replace("'", "", $details['proposal_no']);
    //echo $proposal_no;die;
    // print_r($request->proposal_no); die;
    $lists = Link::where('proposal_no', $proposal_no)->get();

    $listsarch = Linksarchive::where('proposal_no', $proposal_no)->get();
    // print_r($lists); die;
    if ($lists->isNotEmpty()) {
      // echo "test"; die;
      //$listarchive=new Linksarchive; 
      //dd($lists[0]['complete_status']);

     $checknow = false;
     //dd($listsarch);
     if($listsarch->isNotEmpty()){
         if($listsarch[0]['complete_status']==0){//dd('uncom');
      $checknow = ApiController::retriggerIncomplete($proposal_no, $lists[0]['complete_status']);
     }else{//dd('com');
      //$checknow = ApiController::retriggerComplete($proposal_no, $lists[0]['complete_status']);
     }
     }
     

     if($checknow==true){
      $deletedRows = Linksarchive::where('proposal_no', $proposal_no)->delete();
     }else{

      $listarchive = $lists[0]->replicate();

      $created_at = $lists[0]['created_at'];
      $updated_at = $lists[0]['updated_at'];

      $listarchive->version = Linksarchive::where('proposal_no', $proposal_no)->count() + 1;

      $listarchive->created_at = $created_at;
      $listarchive->updated_at = $updated_at;


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
      $lists->personal_disagree_response = NULL;
      $lists->personal_agree_response = NULL;
      $lists->policy_disagree_response = NULL;
      $lists->policy_agree_response = NULL;
      $lists->nominee_disagree = NULL;
      $lists->rider_disagree = NULL;
      // $lists->callbackres = NULL;
      $lists->completed_on = NULL;
      $lists->is_open = 0;
      $lists->is_open_at = NULL;


      $lists->save();

      $trans = Link::where('proposal_no', $proposal_no)->first();
      $var2 = $trans->short_link;

      return $arr = ["status" => "true", "msg" => "retriggered"];

     }

    } else {
      return $arr = ["status" => "false"];
    }
  }

   public function retriggerComplete($proposal_no, $status){
    if($status==1){


       $versionpath = public_path('upload/' . $proposal_no . '-1');
       $orginalpath = public_path('upload/' . $proposal_no);

             //@chmod($versionpath, 0775);
             //@chmod($originalpath, 0775);

             $linkorg = Link::where('proposal_no', $proposal_no)->get();
             $linkarchve = Linksarchive::where('proposal_no', $proposal_no)->get();

             //dd($linkorg[0]['complete_status'], $linkarchve[0]['complete_status']);

             if($linkorg[0]['complete_status']==1 && $linkarchve[0]['complete_status']==1){//dd('dfdf');

                    if (File::isDirectory($versionpath)) {
                    // Ensure permissions first (before copy)
                    @chmod($versionpath, 0775);
                    foreach (File::allFiles($versionpath) as $file) {
                        @chmod($file, 0775);
                    }

                    // Copy files
                    File::copyDirectory($versionpath, $orginalpath);

                    // Delete folder safely
                    if (!File::deleteDirectory($versionpath)) {
                        // Fallback manual delete if Laravel fails
                        foreach (File::allFiles($versionpath) as $file) {
                            @unlink($file);
                        }
                        @rmdir($versionpath);
                    }

                    return true;
                }

             }

    }
  }

  public function retriggerIncomplete($proposal_no, $status){
     
    if($status==0){

         $path = public_path('upload/' . $proposal_no . '-1');

        //dd($path);

        if (File::isDirectory($path)) {
           // echo "version1 is a folder";
            ApiController::logs($proposal_no,'checkversionpath','',$path, "Success");
            @chmod($path, 0775);
            foreach (File::allFiles($path) as $file) {
                @chmod($file, 0775);
            }
            File::deleteDirectory($path);
            return true;
        } else {
            ApiController::logs($proposal_no,'checkversionpath','','', "Success");
        }

      }
  }

  public function singleReactivate_old(Request $request)
  {
    // echo "test"; die;
    $details = $request->all();

    $proposal_no = str_replace("'", "", $details['proposal_no']);
    //echo $proposal_no;die;
    // print_r($request->proposal_no); die;
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
      $lists->personal_disagree_response = NULL;
      $lists->personal_agree_response = NULL;
      $lists->policy_disagree_response = NULL;
      $lists->policy_agree_response = NULL;
      $lists->nominee_disagree = NULL;
      $lists->rider_disagree = NULL;
      // $lists->callbackres = NULL;
      $lists->completed_on = NULL;
      $lists->is_open = 0;
      $lists->is_open_at = NULL;


      // $lists->disagree_response = NULL;
      $lists->save();

      $trans = Link::where('proposal_no', $proposal_no)->first();

      //$params = json_decode($trans->params, true);
     // $to   = $params['personal_mobile'];
      //$var1 = $params['policy_prod_name'] . '-' . $params['policy_plan'];
      $var2 = $trans->short_link;
      //$message = "Dear customer, you have successfully applied for $var1 . To complete your verification please click on $var2 . In case of any error, please copy paste the link directly to your web browser. Pramerica Life Insurance Limited.";
      //$response = $this->sendSMSPramerica($to, $message);
      //  dd($response);
      return $arr = ["status" => "true", "msg" => "retriggered"];
    } else {
      return $arr = ["status" => "false"];
    }
  }

  public function getCallbackurllog(request $request)
  {
     
    $data = $request->all();   //dd($data['Application_Number']);

    $proposal_no = $data['Application_Number'];
    $response = $data['response'];

    ApiController::logs($proposal_no,'callbackurlresp','',json_encode($response), "Success");
  }

  public function removeRupeeSymbol($data) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = $this->removeRupeeSymbol($value); // recursive
        } elseif (is_string($value)) {
            // Remove Unicode rupee symbol and trim whitespace
            $data[$key] = str_replace("₹", "", $value);
        }
    }
    return $data;
}



public function callbackurlget($proposal)
  {

//dd('dfdf');
     $proposal_no = $proposal;

     $link = Link::where('proposal_no', $proposal_no)->first();

    $linkdcode = json_decode($link['params']); //dd($linkdcode);

    $callbacklink = $linkdcode->callbackurl; //dd($callbacklink);

    //$callbacklink = 'IU1K5DUqCr8hLIV7tWa4xAMxrFtTUrn4K0qmZV0KSc3nU/YMXyKtROLURo0FfdkJRfBv5vEogWC+BTa/8QpD0g==';

    $callbckurl = APIController::decrypt($callbacklink);

    //$details = json_decode($callurl, true);  
    //dd($callbckurls);

    //$callbckurl = $callurl['callbackurl'];

    

    if ($link->complete_status == 0) {
      $complete_status =  "Fail";
    } else{
      $complete_status =  "Pass";
    }

      if($link['policy_disagree']==1){
        $plicydisresp = json_decode($link['rider_disagree']);
        //dd($plicydisresp->Rider_details);
      }

      $speechres = json_decode($link['speech_res']);
 

      $Application_Number = $linkdcode->Application_Number;
      $Face_Match = $link['face_response']==null || $link['face_score']<40 ? "Failure" : "Success";
      $Face_Match_Per = $link['face_score']==null ? 0 : (number_format($link['face_score'], 1) . '%');
      //dd($speechres);
      $Speech_Text_Match = isset($speechres->match)?$speechres->match=="true"?"Yes":"No":null;
      $Agreement_Status = ($link['policy_disagree']==0 && $link['personal_disagree']==0)?"Yes":"No";
      $Location = json_decode($link['location'], true);
      //$Location = $link['location'];
      //print_r($Location);die;
      
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_encode($link['nominee_disagree']):null; 
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? 'Disagree':'Agree'; 
      $Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_decode($link['nominee_disagree'],true):null; 
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_encode($link['rider_disagree']):null;
      $Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_decode($link['rider_disagree'],true):null;
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? 'Disagree':'Agree';
      $Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?$plicydisresp->Rider_details:null;
      //$Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?'Disagree':'Agree';
      $Health_Medical_Disagreement = isset($link['medical_checked_response'])?$link['medical_checked_response']:null;
      $Timestamp = $link['completed_on'];
      $Final_Result = $complete_status;

      $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');
      $imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);
      $bawc_imgBase64 =  $imageData;



      //var value = "{'Application_Number':'" + Application_Number + "','Face_Match':'" + Face_Match + "','Face_Match_Per':'" + Face_Match_Per + "','Speech_Text_Match':'" + Speech_Text_Match + "','Agreement_Status':'" + Agreement_Status + "','Location':'" + JSON.stringify(Location) + "','Plan_Details_Disagreement':'" + JSON.stringify(Plan_Details_Disagreement) + "','Rider_Details_Disagreement':'" + Rider_Details_Disagreement + "','Health_Medical_Disagreement':'" + Health_Medical_Disagreement + "','Timestamp':'" + Timestamp + "','Final_Result':'" + Final_Result + "','bawc_imgBase64':'" + bawc_imgBase64 + "'}";
      $LocationStr = '"Location":"' .json_encode($Location) . '"';
      if($Location==null){
        $LocationStr = '"Location":null';
      }

      $Plan_Details_DisagreementStr = '"Plan_Details_Disagreement":"' .json_encode($Plan_Details_Disagreement) . '"';
      if($Plan_Details_Disagreement==null){
        $Plan_Details_DisagreementStr = '"Plan_Details_Disagreement":null';
      }

      $Rider_Details_DisagreementStr = '"Rider_Details_Disagreement":"' .json_encode($Rider_Details_Disagreement) . '"';
      if($Rider_Details_Disagreement==null){
        $Rider_Details_DisagreementStr = '"Rider_Details_Disagreement":null';
      }


      $Personal_Details_DisagreementStr = '"Personal_Details_Disagreement":"' .json_encode($Personal_Details_Disagreement) . '"';
      if($Personal_Details_Disagreement==null){
        $Personal_Details_DisagreementStr = '"Personal_Details_Disagreement":null';
      }


      $ResPayload  = '{"Application_Number":"' .$Application_Number. '","Face_Match":"' .$Face_Match. '","Face_Match_Per":"' .$Face_Match_Per .'","Speech_Text_Match":"' .$Speech_Text_Match. '","Agreement_Status":"' .$Agreement_Status. '",'.$LocationStr.','.$Plan_Details_DisagreementStr.','.$Rider_Details_DisagreementStr.',
      '.$Personal_Details_DisagreementStr.',"Health_Medical_Disagreement":"'.$Health_Medical_Disagreement. '","Timestamp":"' .$Timestamp. '","Final_Result":"' .$Final_Result. '","bawc_imgBase64":"' .$bawc_imgBase64. '"}';
      
      //  $ResPayload = json_decode($ResPayload);
      //$ResPayload = serialize($ResPayload);

      
      $postData = [
        "TransactionId" => "12345",
        "ResPayload" => $ResPayload,
        "Source" => "SUD"
    ];
    //  dd($postData);
    // print_r($postData);die;
    // 4. Then encode everything for cURL
    //$jsonData = json_encode($postData);

    print_r($postData);die;
    // $postData['ResPayload'] = '"'.json_encode($postData['ResPayload']).'"';
    $link->callbackres=json_encode($postData);
    $link->save();
    ApiController::logs($proposal_no,'callbackurlreq',json_encode($postData),"", "Success");

    

    return $postData;
    die;


    
  }
  
  //   public function testaddVideo(Request $request)
  //   {

  //       //dd($request);
  //       $file = $request->file('videoChunk');
  //       $proposal = $request->application_number;
  //       $chunkIndex = $request->chunkIndex;
  //       $totalChunks = $request->totalChunks;

  //      // dd($request->all());
  //   //dd('hfh');
  //   $file = $request->file('videoChunk'); //dd($file);
  //   //$file = $request->videoChunk;
  //   $dataspch='';
  //   $name = 'consent.webm';
  //   $proposal = $request->application_number;
  //   $url = public_path('upload/' . $proposal . '/vid/consent.webm');
  //   try{ 
     
  //   ApiController::logs($proposal, 'Liveness video capture started',"","",  "Success");

  //   $path = public_path('upload/' . $proposal . '/vid/');
  //   if (!File::isDirectory($path)) {
  //     File::makeDirectory($path, 0777, true, true);
  //   }
  //   $targetFile = $path . $name; 
  //   // dd($targetFile);
  //   $chunkIndex = $request->chunkIndex; //dd($targetFile);
  //   if ($chunkIndex == 0) {
  //     if (file_exists($targetFile)) {
  //       unlink($targetFile);
  //     }
  //   }
    
  //   $totalChunks = $request->totalChunks;
  //   $_speech_res = '';
  //   // dd($_FILES['videoChunk']['tmp_path']);
  //   $chunkContent = file_get_contents($_FILES['videoChunk']['tmp_name']); 
  //   $appendSuccess = file_put_contents($targetFile, $chunkContent, FILE_APPEND);
  //   chmod($targetFile,0777); 
  //   ApiController::logs($proposal, 'video chunk total index',$totalChunks,"",  "Success");

  //   if ($appendSuccess !== false) { 
  //     $currentFileSize = filesize($targetFile);
   
  //       if ($currentFileSize < 51200) { //dd('kyuy');   //To check file size is less than 5KB or not
  //           $_speech_res = "Low";
  //       } 
  //   }
  //   else {
  //       echo "Failed to append chunk.";
  //       ApiController::logs($proposal, 'Failed to append video chunk',"","",  "Success");
  //   } 
  //  //dd($totalChunks, $chunkIndex, $appendSuccess, $targetFile);
  //   if($totalChunks == ($chunkIndex + 1)){//dd('dfd');

      
  //     ApiController::logs($proposal, 'video chunk index',$chunkIndex,"",  "Success");

  //     $lang= 'english';
  //     $url = $this->file_url.'/public/upload/' . $proposal . '/vid/consent.webm';
  //     $url_pass = public_path().'/upload/' . $proposal . '/vid/consent.webm';
  //     Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);
  //     $linksave = Link::where('proposal_no', $proposal)->first();
  //      $lang = $linksave->sys_lang;
  //     if($lang=='eng'){
  //       $lang= 'english';
  //     }elseif($lang=='hin'){
  //       $lang= 'hindi';
  //     }
  //     elseif($lang=='ben'){
  //       $lang= 'bengali';
  //     }elseif($lang=='mar'){
  //       $lang= 'marathi';
  //     }
  //     elseif($lang=='ori'){
  //       $lang= 'oriya';
  //     }
  //     elseif($lang=='tel'){
  //       $lang= 'telugu';
  //     }
  //      elseif($lang=='tam'){
  //       $lang= 'tamil';
  //     }
  //     elseif($lang=='mal'){
  //       $lang= 'Malayalam';
  //     }
  //      elseif($lang=='kan'){
  //       $lang= 'Kannada';
  //     }
  //     //speach to text
  //     sleep(2);
  //     $link = Link::where('proposal_no', $proposal)->get();
  //        $params = json_decode($link[0]['params'], TRUE);
  //        //dd($params);
  //        //ApiController::logs($params, 'Video Uploaded ',"","",  "Success");
  //        $name = $params['Life_assured_name']; 

  //            $sp = ApiController::speechToText($proposal, $url_pass, $lang, $name);
  //            dd($sp);

  //           // Save video URL in DB
  //           //$url = $this->file_url . '/public/upload/' . $proposal . '/vid/consent.webm';
  //           //Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);

  //           // Log success
  //           ApiController::logs($proposal, "Video upload complete", "", "", "Success");

  //           return response()->json(['status' => 'success', 'message' => 'Video uploaded', 'speech_res' => $speechRes]);
  //       }

  //       return response()->json(['status' => 'chunk_saved', 'chunk_index' => $chunkIndex]);
  //   }

  // public function image_base64check(Request $request)
   public function image_base64check($image_base64_input, $proposalno)
{
   // $image_base64_input = $request->input('pdf_base64');

   // $proposalno = '568972_sintestpdf';

   $tempDir = public_path('upload/' . $proposalno . '/img');

   if (!File::isDirectory($tempDir)) {
                    File::makeDirectory($tempDir, 0777, true, true);
                }

    if (empty($image_base64_input)) {
        return response()->json([
            'error_code' => false,
            'message' => 'No image data provided.',
        ], 400);
    }

    $decodedData = base64_decode($image_base64_input, true);


    if ($decodedData === false || substr($decodedData, 0, 4) !== '%PDF') {

      $response = base64_encode($decodedData);
      ApiController::base64_to_jpeg($response, $tempDir . '/link_upload.jpeg'); 
      //return base64_encode($decodedData);  
    }

    

    try {
        $imagick = new \Imagick();
        $imagick->setResolution(100, 100);
        $imagick->readImageBlob($decodedData);

        $totalPages = $imagick->getNumberImages();
        $responses = [];

        for ($i = 0; $i < $totalPages; $i++) {
            if ($imagick->setIteratorIndex($i)) {
                $page = $imagick->getImage();
                $page->setImageFormat('jpeg');
                $page->setImageCompressionQuality(90);

                // Create temp folder
                
                

                $imagePath = $tempDir . '/page' . $i . '.jpeg';
                $page->writeImage($imagePath);

                //  Encode actual file content
                $image_base64 = base64_encode(file_get_contents($imagePath));

                //  Call your API for each image
                $response = ApiController::singleimagereturn($image_base64, $proposalno);
                //dd($response=='-1');
//                 dd([
//     'Length' => strlen($response),
//     'Starts with' => substr($response, 0, 30),
//     'Ends with' => substr($response, -30),

// ]);          if()
                if($response==-1){

                  if (!empty($faceScore)) {
                  //dd($faceScore);
                  if (isset($faceScore['confidence'])) {
                    $links = Link::where('proposal_no', $proposal)->first();
                    $links->face_score = (int) $faceScore['confidence'];
                    $links->face_response = ApiController::face_codes($faceScore['response_code']);
                    //$links->face_response = $faceScore['message'];
                    $links->save();
                  }
                }
                  
                }else{
                   ApiController::base64_to_jpeg($response, $tempDir . '/link_upload.jpeg'); 
                }
            }
        }

        // return response()->json([
        //     'error_code' => true,
        //     'total_pages' => $totalPages,
        //     'results' => $responses,
        //     'message' => 'PDF pages converted and sent successfully.',
        // ]);

    } catch (\Exception $e) {
        return response()->json([
            'error_code' => false,
            'message' => 'Error converting PDF to image: ' . $e->getMessage(),
        ], 500);
    }
}


   
public function image_base64check_old($image_base64){
//$image_base64 = $request->pdf_base64;
    if (!empty($image_base64)) {
          $url = null;

          // Try to detect if the input is a PDF (since no prefix is provided)
          $decodedData = base64_decode($image_base64, true);

          // Check if the decoded data starts with the PDF signature "%PDF"
          if (substr($decodedData, 0, 4) === '%PDF') {
              // Convert PDF to Image
              try {
                  $imagick = new \Imagick();
                  $imagick->readImageBlob($decodedData . '[0]');
                  $imagick->setImageFormat('jpeg');
                  $imagick->setImageCompressionQuality(90);
                  $imageData = $imagick->getImageBlob();

                  return base64_encode($imageData);

                  
              } catch (\Exception $e) {
                  return response()->json([
                      'error_code' => false,
                      'message' => 'Error converting PDF to image: ' . $e->getMessage(),
                  ], 500);
              }
          } else {
              // Process it as an image 
              return base64_encode($decodedData);  
          }
      } else {
          return response()->json([
              'error_code' => false,
              'message' => 'No image data provided.',
          ], 400);
      }

  }



    public function docCheckstatus(Request $request){

      $proposal = $request->input('Application_Number');

      $url = 'https://partnerintegrationuat.sudlife.in/DocUploadStatus/Document/Status/' . $proposal;

      $curl = curl_init();

      curl_setopt_array($curl, [
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_HTTPHEADER => [
              'Content-Type: application/json'
          ],
      ]);

      $response = curl_exec($curl);

      if (curl_errno($curl)) {
          // Optional: Handle error
          $error = curl_error($curl);
          curl_close($curl);
          return response()->json(['error' => $error], 500);
      }

      curl_close($curl);

      // Decode if JSON response
      $data = json_decode($response, true);

      dd($data);

      //return response()->json($data);

    }

    

    //  public function updateExcel()
    //   {

    //     $path = public_path('excel/retrigger1.xlsx');

    //     //$link = new Link;

    //     $import = new YourImport;

    //     Excel::import($import, $path);

    //     $rows = $import->getData();

    //    foreach ($rows as $index => &$row) {
            
    //          //print_r($link[0]['proposal_no']);

    //         if(!empty($row)){ 

    //           $link = Link::where('proposal_no', $row[0])->get()->toArray();

    //           $proposal_no =$link[0]['proposal_no'];
    //           //echo $proposal_no;die;
    //           // print_r($request->proposal_no); die;
    //           //dd($details);
    //           $lists = Link::where('proposal_no', $proposal_no)->get();
    //           // print_r($lists); die;
    //           if ($lists->isNotEmpty()) {
    //             // echo "test"; die;
    //             //$listarchive=new Linksarchive; 
    //             $listarchive = $lists[0]->replicate(); 

    //             $created_at = $lists[0]['created_at'];
    //             $updated_at = $lists[0]['updated_at'];

    //             $listarchive->version = Linksarchive::where('proposal_no', $proposal_no)->count() + 1;

    //             $listarchive->created_at = $created_at;
    //             $listarchive->updated_at = $updated_at;

    //             //dd($listarchive);
    //             $listarchive->setTable('links_archive');
    //             $listarchive->save();
    //             $path = public_path('upload/' . $proposal_no . '-' . $listarchive->version);
    //             $spath = public_path('upload/' . $proposal_no);
    //             if (!File::isDirectory($path)) {
    //               File::makeDirectory($path, 0777, true, true);
    //             }
    //             File::copyDirectory($spath, $path);

    //             $lists = $lists[0];
    //             $lists->complete_status = 0;
    //             $lists->device = NULL;
    //             $lists->personal_disagree = NULL;
    //             $lists->policy_disagree = NULL;
    //             $lists->completed_on = NULL;
    //             $lists->is_open = 0;
    //             $lists->is_open_at = NULL;


    //             // $lists->disagree_response = NULL;
    //             $lists->save();

    //             $trans = Link::where('proposal_no', $proposal_no)->first();


    //             //$params = json_decode($trans->params, true);

    //             //$to   = $params['personal_mobile'];
    //             //$var1 = $params['policy_prod_name'] . '-' . $params['policy_plan'];
    //             $var2 = $trans->short_link;//dd($var2);
    //             //$message = "Dear customer, you have successfully applied for $var1 . To complete your verification please click on $var2 . In case of any error, please copy paste the link directly to your web browser. Pramerica Life Insurance Limited.";
    //             //$response = $this->sendSMSPramerica($to, $message);
    //             //  dd($response);
    //             // return $arr = ["status" => "true", "msg" => "reactivated"];

    //             //return response()->json(['status' => true, 'link' => $var2], 200);
    //             $jsonResult = json_encode(['status' => true, 'link' => $var2], 200);
    //             return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    //           } else {
    //             //return $arr = ["status" => "false"];
    //             //return response()->json(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
    //             $jsonResult = json_encode(['status' => false, 'error_code' => 701, 'message' => "Invalid Application Number"], 200);
    //             return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    //           }
    //         }
    //         else{
    //           $jsonResult = json_encode(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
    //             return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    //           //return response()->json(['status' => false, 'error_code' => 702,  'message' => "Invalid Request"], 200);
    //         } 

    //     }//die;

    //     Excel::store(new ArrayExport($rows), 'public/retigger1.xlsx');

    //     return response()->download(storage_path('app/public/retigger1.xlsx'));
    //   }
   //public function singleimagereturn($image_base64, $proposal)
   public function singleimagereturn(Request $request)
{ //dd('df');
    // $postData = [
    //     "image_base64" => $image_base64,
    //     "proposal_number" => $proposal
    // ];

    //dd($request['image_base64']);

    $postData = [
        "image_base64" => $request['image_base64'],
        "proposal_number" => $request['proposal_number']
    ];


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://test.anurcloud.com/sud_life_crop',  //https://test.anurcloud.com/sud_life_document
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($postData),  //  encode it
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
    ));

    $response = curl_exec($curl);
    curl_close($curl);
     //print_r($response);die;
    $decoded = json_decode($response, true);
    if ($decoded && $decoded['document_found'] === true) {
       $base64 = $decoded['page_base64'];
       //echo $base64;
      return $base64;
     }else if($decoded && $decoded['document_found'] === false){
       $score = $decoded['score'];
       return $score;
     }
}



public static function base64_to_all($base64_string, $path_without_ext)
{
    // Step 1: Clean base64
    if (preg_match('/^data:(application\/pdf|image\/\w+);base64,/', $base64_string)) {
        $base64_string = substr($base64_string, strpos($base64_string, ',') + 1);
    }

    // Step 2: Decode safely
    $binaryData = base64_decode($base64_string, true);
    if ($binaryData === false) {
        return false; // Invalid base64
    }

    // Step 3: Detect MIME
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($binaryData);

    //dd($mime);

    // Step 4: Get correct extension
   $mimeToExt = [
    'image/jpeg'        => 'jpg',
    'image/png'         => 'png',
    'image/gif'         => 'gif',
    'image/webp'        => 'webp',
    'image/bmp'         => 'bmp',
    'image/x-icon'      => 'ico',
    'image/svg+xml'     => 'svg',
    'application/pdf'   => 'pdf', //include this
];

    if (!isset($mimeToExt[$mime])) {
        return false; // Not a supported image
    }

    $ext = $mimeToExt[$mime];
    $finalPath = $path_without_ext . '.' . $ext;

    // Step 5: Save file
    $result = file_put_contents($finalPath, $binaryData);
    if (!$result || !file_exists($finalPath)) {
        return false;
    }

    return $finalPath; // Return final file path with extension
}

public function downloadfile($proposalno)
{

  //$proposalno  = 'dp6AmUHCZZglbBcm';
  $proposal = base64_decode($proposalno);//dd(base64_decode($proposal));

    $proposal = '53923117'; //53918101 53920355 53923117 

    $url = 'https://vbawcwiuat.sudlife.in/GetVideoPathApi/api/videos/'. $proposal;
 
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);

    $filepath='';

   //dd($response, public_path());

      if(isset($response)){
          
                $decoderesponse = json_decode($response);

                //dd($proposal);

                if(isset($decoderesponse->path)){
 
                    $fullPath = $decoderesponse->path;

                   // dd($fullPath);

                   $filepath = str_replace('D:\\vc\\portal\\public\\', '', $fullPath);

                   $relativePath = str_replace('\\', '/', $filepath);

                   $filepath = $this->uaturl.$relativePath;

                    //echo $relativePath;die;

                    ApiController::logs($proposal,'GetVideoPathApi', '',$decoderesponse->path, "success");
                }
                else if(isset($decoderesponse->error) || $decoderesponse==null){
                    // $filepath = public_path();

                  $filepath = $this->uaturl.'upload/' . $proposal . '/vid/consent.webm';

                     //ApiController::logs($proposal,'GetVideoPathApi', '',$decoderesponse->error, "Fail");
                }

        //         header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');   //to video download
        // header('Content-Length: ' . filesize($filepath));
        // flush();
        // readfile($filepath);

             //dd($filepath);



     }


      //dd($filepath);



                       $filename = $proposal;
                       return redirect()->away($filepath);

//dd($filepath);
                    //         if ($filepath) {
                    //     return response()->view('video_player', compact('filepath'));
                    //     //return view('video_player', compact('filepath'));
                    // }  
}

public function getPDFdownload($proposal)
{

    $url = 'https://vbawcwiuat.sudlife.in/GetVideoPathAPI/api/pdf/'. $proposal;
 
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
     curl_close($curl);
    return $response;
}

// public function getHashfordoclist($proposalno, $dob, $expectedName, $expectedCode){

//       $curl = curl_init();

//             $postData = [
//           "PartnerDetails" => [
//               "Partner" => $expectedName,
//               "PartnerCode" => $expectedCode,
//           ],
//           "PolicyDetails" => [
//               "PolicyNo" => $proposalno,
//               "DOB" => $dob,
//               "ClientId" => "50200278",
//               "ApplicationNumber" => $proposalno,
//           ]
//       ];

//       curl_setopt_array($curl, array(
//         CURLOPT_URL => 'https://dev1.anurcloud.com/sudlife/portal/api/compute-hash',
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => '',
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 0,
//         CURLOPT_FOLLOWLOCATION => true,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => 'POST',
//         CURLOPT_POSTFIELDS =>json_encode($postData),
//         CURLOPT_HTTPHEADER => array(
//           'Content-Type: application/json'
//         ),
//       ));

//       $response = curl_exec($curl);

//       curl_close($curl);
//       return $response;

// }

// public function getHashfordocdownload($proposalno, $dob, $expectedName, $expectedCode, $docname, $docindex){


// $curl = curl_init();

// $postData = [
//     "PartnerDetails" => [
//         "Partner" => $expectedName,
//         "PartnerCode" => $expectedCode
//     ],
//     "PolicyDetails" => [
//         "PolicyNo" => $proposalno,
//         "DOB" => $dob,
//         "ClientId" => "50200278",
//         "ApplicationNumber" => $proposalno
//     ],
//     "DocDownload" => [
//         "DocumentIndex" => $docindex,
//         "FileName" => $docname
//     ]
// ];

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://dev1.anurcloud.com/sudlife/portal/api/compute-hash',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>json_encode($postData),
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/json'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// echo $response;


// }
    
public function getdocumentlistApi($proposal_no, $dob, $expectedName, $expectedCode)
{
  
    $postData = [
      "PartnerDetails" => [
          "Partner" => $expectedName,
          "PartnerCode" => $expectedCode
      ],
      "PolicyDetails" => [
          "PolicyNo" => "",
          "DOB" => "",
          "ClientId" => "",
          "ApplicationNumber" => $proposal_no
      ]
  ];

  // $postData = [
  //     "PartnerDetails"=> [
  //         "Partner"=> "ANURCLOUD",
  //         "PartnerCode"=> "456316"
  //     ],
  //     "PolicyDetails"=> [
  //         "PolicyNo"=> "00001703",
  //         "DOB"=> "03/05/2000",
  //         "ClientId"=> "50200278",
  //         "ApplicationNumber"=> "53911129"
  //     ]
  //   ];

    //$postData = json_encode($postData);
  $jsonPayload = json_encode($postData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

  //dd($jsonPayload);

  $getApiresp = ComputeHashFromJSON::generateHashFromRequest($jsonPayload); //dd($getApiresp->getData(true));

  if($getApiresp){

      ApiController::logs($proposal_no,'GetVideoPathApi', '',$getApiresp, "success");

      //dd($getApiresp);

      //$decodeHash = json_decode($getApiresp, true); 

      $data = $getApiresp->getData(true); // Get the JSON response as an associative array

      //dd($data['hash']);
      $hash = $data['hash'];

      // dd($hash);

      // $getHashval = $getApiresp['hash']; dd($getHashval);

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'Https://eaapgtprime.sudlife.in/cs/api/CustomerService/GetDocList',//'https://eadvapgt.sudlife.in/Cs/api/CustomerService/GetDocList',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($postData),
        CURLOPT_HTTPHEADER =>  array(
          'x-HMAC-CS: ' . $hash,
          'Content-Type: application/json',
          // 'Cookie: your_cookie_here'
      ),
      ));

      $response = curl_exec($curl);

      ApiController::logs($proposal_no,'GetVideoPathApi', '', $response, "success");

      curl_close($curl);
      //dd($response);
      return $response;
  }else{
      ApiController::logs($proposal_no,'GetVideoPathApi', '',$getApiresp, "success");
  }

}

public function getdownloaddocumentApi($proposal_no, $dob, $expectedName, $expectedCode, $docname, $docindex)
{
 
  $postData = [
    "PartnerDetails" => [
        "Partner" => $expectedName,
        "PartnerCode" => $expectedCode
    ],
    "PolicyDetails" => [
        "PolicyNo" => "",
        "DOB" => "",
        "ClientId" => "",
        "ApplicationNumber" => $proposal_no
    ],
    "DocDownload" => [
        "DocumentIndex" => $docindex,
        "FileName" => $docname
    ]
 ];

  $getApiresp = ComputeHashFromJSON::generateHashFromRequest($postData);

  if($getApiresp){

      ApiController::logs($proposal_no,'GetVideoPathApi', '',$getApiresp, "success");

      // $decodeHash = json_decode($getApiresp, true);

      // $getHashval = $decodeHash['hash'];

      $data = $getApiresp->getData(true); // Get the JSON response as an associative array
      $hash = $data['hash'];

      $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://eaapgtprime.sudlife.in/cs/api/CustomerService/DocumentDownload',//'https://eadvapgt.sudlife.in/Cs/api/CustomerService/DocumentDownload',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($postData),
          CURLOPT_HTTPHEADER =>  array(
            'x-HMAC-CS: ' . $hash,
            'Content-Type: application/json',
            // 'Cookie: your_cookie_here'
        ),
        ));

        $response = curl_exec($curl);

        ApiController::logs($proposal_no,'GetVideoPathApi', '', $response, "success");
        
        curl_close($curl);
        return $response;

     }else{
        ApiController::logs($proposal_no,'GetVideoPathApi', '',$getApiresp, "success");
     }

}

public function checkKannada(Request $request)
{
  dd('erer');
    // Remove Zero-width space, ZWNJ, ZWJ, etc.
    $text = str_replace(
        ["\u{200B}", "\u{200C}", "\u{200D}", "\u{FEFF}"], 
        "", 
        $text
    );
 
    // Replace non-breaking space with normal space
    $text = str_replace("\u{00A0}", " ", $text);
 
    // Normalize Unicode (NFC form)
    if (class_exists("Normalizer")) {
        $text = Normalizer::normalize($text, Normalizer::FORM_C);
    }
 
    return $text;

}


public function copyApplicationImages_old(Request $request)
{
    // 1. Load Excel Numbers
    //$import = new ImageNumbersImport(); 
    //Excel::import($import, public_path('upload/number.xlsx')); // Excel with numbers
    //$appNumbers = $import->numbers;

   $appNumbers='70011251';

    $apiCtrl = new HomeController();

        $expectedName  = config('partner.name');
        $expectedCode  = config('partner.code');
        $partnerKey    = config('partner.secret');

        $image_base64=null;

        $dob = "";
        $image_base64 = $apiCtrl->getimagebase64($appNumbers, $dob, $expectedName, $expectedCode);

    //dd($image_base64);

    // 2. Define target base path
    $targetBase = 'D:/Sud_Life/Sep/13-9-25/upload/';   // destination folder

    $limit = 1; // limit of applications to process
    $count = 0;

    //dd($appNumbers);

    foreach ($appNumbers as $appNo) { 
        if ($count >= $limit) break;

        $targetFolder = $targetBase . $appNo . '/img';

       

        // source files (remote or local depending on your setup)
        $fileLinkUpload   = $this->uaturl . 'upload/' . $appNo . '/img/link_upload.jpeg';
        $fileVideoConsent = $this->uaturl . 'upload/' . $appNo . '/img/video_consent.jpeg';
        $fileOrgUpload    = $this->uaturl . 'upload/' . $appNo . '/img/org_upload.pdf';

       //dd($fileLinkUpload, $fileVideoConsent, $fileOrgUpload);

        // create target folder if not exists
        if (!File::exists($targetFolder)) {
            File::makeDirectory($targetFolder, 0777, true);
        }

         //File::copy($fileVideoConsent, $targetFolder . '/video_consent.jpeg'); //die;

        // --- Copy video_consent.jpeg ---
        if (File::exists($fileVideoConsent)) {//dd($fileVideoConsent);
            File::copy($fileVideoConsent, $targetFolder . '/video_consent.jpeg');
        }

        // --- Copy link_upload.jpeg ---
        if (File::exists($fileLinkUpload)) {
            File::copy($fileLinkUpload, $targetFolder . '/link_upload.jpeg');
        }
        // --- If link_upload.jpeg not found, fallback to org_upload.pdf ---
        elseif (File::exists($fileOrgUpload)) {
            File::copy($fileOrgUpload, $targetFolder . '/org_upload.pdf');
        }

         // --- Copy video_consent.jpeg ---
        // if ($this->remoteFileExists($fileVideoConsent)) {
        //     file_put_contents($targetFolder . '/video_consent.jpeg', file_get_contents($fileVideoConsent));
        // }

        // --- Copy link_upload.jpeg ---
        // if ($this->remoteFileExists($fileLinkUpload)) {
        //     file_put_contents($targetFolder . '/link_upload.jpeg', file_get_contents($fileLinkUpload));
        // }
        // // --- If link_upload.jpeg not found, fallback to org_upload.pdf ---
        // elseif ($this->remoteFileExists($fileOrgUpload)) {
        //     file_put_contents($targetFolder . '/org_upload.pdf', file_get_contents($fileOrgUpload));
        // }

        $count++;
    }

    return "Copied files for {$count} application(s) successfully.";
}


public function copyApplicationImages(Request $request)
{

 
    $folderPath = public_path('upload/VC'); // path to folder
    $files = File::files($folderPath);

    $appNumbers = [];

    foreach ($files as $file) {
        if ($file->getExtension() === 'jpeg') {
            $appNumbers[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        }
    }

    // return count($appNumbers); // ["john", "mary", "alex"]

    // // 1. Load application numbers from Excel
    // $import = new ImageNumbersImport(); 
    // Excel::import($import, public_path('upload/number.xlsx')); // your Excel file
    // $appNumbers = $import->numbers; // should be an array of app numbers

    // HomeController for image fetching
    $apiCtrl = new HomeController();

    $expectedName  = config('partner.name');
    $expectedCode  = config('partner.code');
    $partnerKey    = config('partner.secret');

    // 2. Define target base path
    $targetBase =  public_path('upload/KYC/');  // destination folder

    //$appNo = "16010120";

    $count = 0;
    foreach ($appNumbers as $appNo) { //dd($appNo);
        $dob = ""; // adjust if DOB comes from Excel
        $image_base64 = $apiCtrl->getimagebase64($appNo, $dob, $expectedName, $expectedCode);

        if ($image_base64) {
            $targetFolder = $targetBase . $appNo;

            // Create folder if not exists
            if (!File::exists($targetFolder)) {
                File::makeDirectory($targetFolder, 0777, true);
            }

            // 3. Decode base64 and save image
            //$imageData = base64_decode($image_base64);

            ApiController::base64_to_all($image_base64, $targetFolder . '/org_upload');

            // Save as JPEG (you can change extension based on what getimagebase64 returns)
            //$filePath = $targetFolder . '/image.jpeg';
            //file_put_contents($filePath, $imageData);

            $count++;
        }
    }

    return "Copied and saved {$count} application image(s) successfully.";
}


private function remoteFileExists($url)
    {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }

    public function doBulkUpload($appno, $jsonData){

        $expectedName  = config('partner.name');
        $expectedCode  = config('partner.code');
        $partnerKey    = config('partner.secret');

        $image_base64=null;

        $dob = "";

      $apiCtrl = new HomeController();

      $image_base64 = $apiCtrl->getimagebase64($appno, $dob, $expectedName, $expectedCode);

      $jsonData['image_base64'] = $image_base64;

      $request = Request::create('/dummy-url', 'POST', [], [], [], [
                  'CONTENT_TYPE' => 'application/json',
              ], $jsonData);
              $decryptreq = ApiController::testApi($request);
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
              // $createreq = ApiController::createPIVCLink($request);

              // if($createreq){

              //    $lists = Link::where('proposal_no', $appno)->first();  
              //     if (!empty($lists)) { 
              //       uploadlink::where('app_no', $appno)->update(['create_status' => 1]);

              //       $appno = $lists->proposal_no;
              //       $shrtlink = $lists->short_link;
              //       $createdate = $lists->created_at;



              //     }
              // }

              $pendingLinks = uploadlink::where('create_status', 0)->get();

        foreach ($pendingLinks as $record) {
            try {
                $appno = $record->app_no;
                $request = ['proposal_no' => $appno]; // adjust according to your API structure

                $createreq = ApiController::createPIVCLink($request);

                if ($createreq) {
                    $lists = Link::where('proposal_no', $appno)->first();

                    if (!empty($lists)) {
                        uploadlink::where('app_no', $appno)->update(['create_status' => 1]);

                         $this->generatedLinks[] = [
                            $lists->proposal_no,
                            $lists->short_link,
                            $lists->created_at->format('Y-m-d H:i:s'),
                        ];

                        Log::info("Generated link saved: {$lists->proposal_no}");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error processing {$appno}: " . $e->getMessage());
            }
        }

        // Once all links are done
        ApiController::exportAndMailReport();

    }

}

public function exportAndMailReport()
    {
        if (empty($this->generatedLinks)) {
            Log::info('No links found to export.');
            return;
        }

        $fileName = 'Bulk_Link_List_' . date('Ymd_His') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        Excel::store(new GeneratedLinkExport($this->generatedLinks), 'public/' . $fileName);

        Mail::send('emails.generated_links', [], function ($message) use ($filePath, $fileName) {
            $message->to('vinothkumar@anurcloud.com')
                    ->subject('Generated Links Report')
                    ->attach($filePath);
        });

        Log::info('Excel exported and mail sent: ' . $fileName);
    }


public function video_check_api (Request $request)
{

    $post = $request->all();  

    //$proposal_no = 'sud147-20';

    $proposal_no = $post['application_number'];

    $name = 'consent.webm';
   
    ApiController::logs($proposal_no, 'Liveness video capture started',"","",  "Success");

    $path = public_path('upload/' . $proposal_no . '/vid/');
    
    $targetFile = $path . $name; 
    // dd($targetFile);
   
      if (file_exists($targetFile)) {
        $arr['status'] = true;
        

      }
      else{
        $arr['status'] = false;
      }
      //dd( $arr['status'] );
        $enc_data = json_encode($arr, JSON_FORCE_OBJECT);
        return $enc_data;
      
}

public function retriggerRequest($proposal){

    $version=1; 

    $path = public_path('upload/' . $proposal_no . '-' . $version);

    $link = Link::where('proposal_no', $proposal)->first();

    if($link){

      if($link->complete_status=='1'){

      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true, true);
      }

      }
      // elseif($link->complete_status=='0'){

      // if (File::isDirectory($path)) {
      //   File::makeDirectory($path, 0777, true, true);
      // }

      // }

    }else{

      $jsonResult = json_encode(['status' => false, 'message' => 'Link does not exsist'], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);

    }

    

}

public function getDocsdownloadApi($proposal)
{

    $url = 'https://vbawcwiuat.sudlife.in/GetVideoPathApi/api/GetDocumentsAPI/'. $proposal;
 
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

     $response = curl_exec($curl);

     curl_close($curl);

      ApiController::logs($proposal,'getDocsdownloadApi', '',$response, "success");

      $data = json_decode($response, true);

      // Access the decoded path
      $path = @$data['path'];

      return $path;
}

}
