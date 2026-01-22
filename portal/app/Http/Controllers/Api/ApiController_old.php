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
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\EncryptionCTRController;
// use Jenssegers\Agent\Facades\Agent;

class ApiController extends Controller
{

  public $main_url;
  public $enc;
  public $secret_key;

  public function __construct()
  {
    $this->secret_key = '1234567890123456';
    $this->enc = new EncryptionController;

    $this->main_url = 'https://dev.anurcloud.com/sudlife';          //https://eadvapgt.sudlife.in/vc/sudlife/portal/api/addImage
    $this->file_url = 'https://dev1.anurcloud.com/sudlife/portal';

    

  }

  public function testApi(Request $request)
  {
    
    $key = '1234567890123456'; // 16-byte key 1234567890123456
    $jsonData = json_encode($request->all());
    $encrypted = EncryptionController::encrypt($jsonData, $key);
    return $encrypted;
    // $decrypted = EncryptionController::decrypt($encrypted, $key);
    // echo "<br/><br/><br/>";
    //echo $decrypted;
  }

  public function testdecryptApi(Request $request)
  {
    $jsonData = $request->all();
    $jsonResult =  $jsonData['encrypted'];
    return $dd =  APIController::decrypt($jsonResult);
    //return $dd =  APIController::encrypt($jsonResult);
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
    if ($latitude != '' || $longitude != '') {




      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyBPnS82bRgH3-yYqK_-ikTWzKqS5P5n63g",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      $response = json_decode($response);
      if (isset($response->results[0])) {
        $address = $response->results[0]->formatted_address;
        $address_disp = $address;
      }
    }

    $html = view('genpdf', compact('data', 'address_disp', 'city'))->render();

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

    $name = $proposal . '.pdf';
    $path = public_path('upload/' . $proposal . '/') . $name;
    $links = Linksarchive::where('proposal_no', $proposal)->where('version', $version)->get();
    $data = $links[0];
    $pdf = '';

    $location = json_decode($data['location'], true);
    $longitude = $location['lat'];
    $latitude = $location['long'];
    $address_disp = '';
    $city = '';
    if ($latitude != '' || $longitude != '') {
      //echo "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyBPnS82bRgH3-yYqK_-ikTWzKqS5P5n63g";die;

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyBPnS82bRgH3-yYqK_-ikTWzKqS5P5n63g",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      $response = json_decode($response);
      if (isset($response->results[0])) {
        $address = $response->results[0]->formatted_address;
        $address_disp = $address;
      }
    }

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

    // $pdf = PDF::loadView('genpdf', compact('data', 'address_disp', 'city'));

    // return $pdf->stream($name);
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
  CURLOPT_SSL_VERIFYHOST => 0,    
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "sourcename": "AnurClud",
  "longUrl": "'.$url.'",
  "expirydays": "90"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);



curl_close($curl);
$res_decode = json_decode($response,true);//dd($res_decode);
//ApiController::logs($proposal,'validatePIVCLink',json_encode(($post)),$result, "Success");
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

  
  public function base64_to_jpeg($base64_string, $output_file)
  {
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

  public function validatePIVCLink(Request $request)
  {

    $arr = [];
    try {

      $post = $request->all();  
      $url = $post['pivc_url'];//dd($url);
      $device_name = $post['device_name'];
      $nettype = $post['nettype'];
      $netrtt = $post['netrtt'];
      $netdown = $post['netdown'];
      $network = array('device_name' => $device_name, 'type' => $nettype, 'rtt' => $netrtt, 'downlink' => $netdown);
      if(strlen($url) > 110){
        $proposal = decrypt(explode('?', $url)[1]); //dd($proposal);
      }else{
        $proposal = EncryptionCTRController::decrypt(explode('?', $url)[1],$this->secret_key);
        //dd($proposal);
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
        $params = json_decode($links['params'],true); 
        $params['plan_details']['Sum_Assured'] = (isset($params['plan_details']['Sum_Assured']) && $params['plan_details']['Sum_Assured']!='') ? ApiController::IND_money_format($params['plan_details']['Sum_Assured']) : 0;
        $params['plan_details']['Premium_Amount'] = (isset($params['plan_details']['Premium_Amount']) && $params['plan_details']['Premium_Amount']!='') ? ApiController::IND_money_format($params['plan_details']['Premium_Amount']) : 0;

        $Rider_details = [];
        if(isset($params['plan_details']['Rider_details']) && !empty($params['plan_details']['Rider_details'])){
          foreach($params['plan_details']['Rider_details'] as  $key=>$value){
            $params['plan_details']['Rider_details'][$key]['Rider_Sum_Assured'] = ApiController::IND_money_format($params['plan_details']['Rider_details'][$key]['Rider_Sum_Assured']);
          }
        }
        $params['Mobile_number'] = '+91 '.$params['Mobile_number'];
        $arr['params']=$params;
        Link::where('proposal_no', $proposal)->update(['link_attempt_count' => ($links->link_attempt_count + 1)]);
        

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
    
    $post = $request->all();//dd($post['application_number']);
    try {

      $screen = $post['screen'];
      $disagree_data = $post['disagree_data']; 
      $proposal = $post['application_number'];
      $status = $post['status'];
      //dd($screen,$status);
      ApiController::logs($proposal, $status.'ScreenStart', json_encode($post), "", "Success");

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      $links->medical_checked_response = $post['Medical_Flag'];
      if ($links != '') {
        $req = array();

        if ($screen == 'personal') {
          $disagree_data_dcode = json_decode($disagree_data, true);
          if($status=='disagree'){  
            $disagree_data_dcode['screen'] = $screen;
            $disagree_data_dcode['status'] = $status;
            $links->personal_disagree_response = json_encode($disagree_data_dcode); //personal_disagree_response
          }
          if($status=='agree'){
            $disagree_data_dcode['screen'] = $screen;
            $disagree_data_dcode['status'] = $status;
            $links->personal_disagree = 0;
            $links->personal_agree_response = json_encode($disagree_data_dcode); //personal_disagree_response
          }
          ApiController::logs($proposal, $status.'ScreenStart', json_encode($disagree_data_dcode), "", "Success");
        }
        if ($screen == 'policy') {
          $disagree_data_dcode = json_decode($disagree_data, true);
          if($status=='disagree'){
          $disagree_data_dcode['screen'] = $screen;
          $disagree_data_dcode['status'] = $status;  
          $links->policy_disagree_response = json_encode($disagree_data_dcode); //policy_disagree_response
          }
          if($status=='agree'){
            $links->policy_disagree = 0;
            $disagree_data_dcode['screen'] = $screen;
            $disagree_data_dcode['status'] = $status;  
            $links->policy_agree_response = json_encode($disagree_data_dcode); //policy_disagree_response
          }
          ApiController::logs($proposal, $status.'ScreenStart', json_encode($disagree_data_dcode), "", "Success");
        }

        $links->save();
        $arr = array('status' => 'Success', 'msg' => $status.'Status Added.');
        return json_encode($arr);

      } else {

        $arr = array('status' => 'Success', 'msg' => $status.'Status Failed.');
        return json_encode($arr);

      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post['application_number'], 'Screenstatus', json_encode($post), $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }


  public function feedback(Request $request)
  {

    $post = $request->all();//dd($post->application_number);
    try {

      ApiController::logs($post['application_number'], 'feedback started', json_encode($post), "", "Success");

      $category = $post['category'];
      $feedback = $post['feedback']; 

      $proposal = $post['application_number'];

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      if ($links != '') {

          $links->feedback = json_encode($post); //policy_agree_response


        $links->save();
        $arr = array('status' => 'Success');
        return json_encode($arr);

      } else {

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

    $post = $request->all();//dd($post->application_number);
    try {

      ApiController::logs($post['application_number'], 'location capture started', json_encode($post), "", "Success");

      $lat = $post['lat'];
      $long = $post['long']; 
      $syslang  = $post['syslang'];

      $proposal = $post['application_number'];

      $links = Link::where('proposal_no', $proposal)->get();
      $links = $links[0];
      if ($links != '') {
         
        if (isset($post['long'])) {     $post['_long'] = $post['long']; 
          unset($post['long']);
          }
          $links->location = json_encode($post); //policy_agree_response
          $links->sys_lang = $syslang;

        $links->save();
        $arr = array('status' => 'Success');
        return json_encode($arr);

      } else {

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
  public function checkStatus(Request $request)
  {
    $reqst = $request->all();
    $jsonString = APIController::decrypt($reqst['ReqPayload']);  

    //$post = explode(",", $jsonString);

    $details = json_decode($jsonString, true);  //print_r($post);die;
    if(!empty($details['Application_Number'])){ 
    $proposal = $details['Application_Number'];
    $links = Link::where('proposal_no', $proposal)->first();

    if (!empty($links)) {

      if ($links->complete_status == 1) {

        if ($links->personal_disagree == 0 || $links->personal_disagree == 1 && $links->policy_disagree == 0 || $links->policy_disagree == 1) {
          //$callbckresult = $this->callbackurl($proposal);
          //$jsonResult = json_encode(['status' => true,  'pivc_status' => "Complete", 'callbackresponse'=> $callbckresult ], 200);
          $jsonResult = json_encode(['status' => true,  'pivc_status' => "Complete"], 200);
          return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
        } else {
          $jsonResult = json_encode(['status' => true,  'pivc_status' => "Opened - Pending For Completion"], 200);
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

  
  public function addImage(Request $request)
  {

    $post = $request;
    $arr=[];
    try {
      $image = $post->reg_img;
      $screen = $post->curscrn;
      $proposal = $post->application_number;
      $screentype = $post->scrntype;

      //ApiController::logs($proposal, 'addImageStarted', json_encode($post), "Success");
      
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
      $linkSingle->images = $json_images;
      $linkSingle->save();

      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true, true);
      }
      if ($image != '' and $screen != '') {
        ApiController::base64_to_jpegFrontend($image, $path . '/' . $screen . '.jpeg');
        $arr = array('status' => TRUE, 'msg' =>'Image Uploaded.');
      } else {
        $arr = array('status' => FALSE, 'msg' => 'Data missing.');
      }

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
    $file = $request->file('videoChunk');
    $name = 'consent.webm';
    $proposal = $request->application_number;
    $url = public_path('upload/' . $proposal . '/vid/consent.webm');

    //ApiController::logs($proposal, 'addImageStarted', json_encode($post), $e->getMessage(), "Success");

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

    $chunkContent = file_get_contents($file->getRealPath()); 
    $appendSuccess = file_put_contents($targetFile, $chunkContent, FILE_APPEND);
    
    if($totalChunks== ($chunkIndex + 1)){
      $lang= 'english';
      $url = $this->file_url.'/public/upload/' . $proposal . '/vid/consent.webm';
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
          $sp = ApiController::speechToText($proposal, $url, $lang, $name);
          $_speech_res = 'Low';
          if($sp!=''){
            $sp_res = json_decode($sp,true);
            if(isset($sp_res['match']) && $sp_res['match']==true && $sp_res['score']>75){
              //$_speech_res = true;
              $_speech_res = 'High';
            }
          }
          $linksave->speech_res = $sp;
          $linksave->save();
          ApiController::logs($proposal, 'Video Uploaded ',"","",  "Success");
          }

   

    if ($appendSuccess !== false) {
      Link::where('proposal_no', '=', $proposal)->update(['video' => $url]);
      return response()->json(array('status' => TRUE, 'Message' => 'Video Uploaded.','speech_res'=>$_speech_res));
    } else {
      return response()->json( array('status' => TRUE, 'Message' => 'Upload Failed'));
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
   

    $post = $request->all();//dd($post);
    try {
      $proposal =$post['application_number'];
      $vd = Link::where('proposal_no', $proposal)->select('video')->get();//dd($vd);

      $vd = $vd['0'];

      if ($vd != "" and $vd != NULL) {

        $path = public_path('upload/' . $proposal . '/img/');
        if (file_exists($path . '/link_upload.jpeg')) {
          $linkImage =   ApiController::image_to_base64($path . 'link_upload.jpeg');

          $captureImage =   ApiController::image_to_base64($path . '/video_consent.jpeg');

          $type = 'default';
          $faceScore = ApiController::getFaceScore($proposal, $linkImage, $captureImage, $type);

          if (!empty($faceScore)) {

            if (isset($faceScore['confidence'])) {
              $links = Link::where('proposal_no', $proposal)->first();
              $links->face_score = (int) $faceScore['confidence'];
              $links->face_response = ApiController::face_codes($faceScore['response_code']);
              $links->save();
            }
          }
        }

        $links = Link::where('proposal_no', $proposal)->update(['complete_status' => 1, 'completed_on' => date('Y-m-d H:i:s'), 'status' => 1]);

        $linkspush = Link::where('proposal_no', $proposal)->first();

        $face_score  =  'No';
        if ($linkspush->face_score != null && $linkspush->face_score >= 40) {
          $face_score  =  'Yes';
        } else if (is_null($linkspush->face_score)) {
          $face_score  =  'Yes';
        }

        if ($linkspush->personal_disagree == 'Agree' && $linkspush->policy_disagree == 'Agree' && $face_score == 'Yes') {

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

        }

        ApiController::genPdf($proposal);
          $docpush = Link::where('proposal_no', $proposal)->first();
          $docpush->docpush = 1;
          $docpush->docpush_date = date('Y-m-d H:i:s');          
          $docpush->save();
        ApiController::logs($proposal, 'CompleteStatus', '', '', "Success");

        $callbackresult = $this->callbackurl($proposal);
        
        $callredirect = json_decode($docpush['params']);

        $callbckurl = APIController::decrypt($callredirect->callbackurl);

        $arr = array('status' => True, "msg" => 'PIVC Completed successfully', "callback_url"=>$callbckurl, "callback_reslt"=>$callbackresult);

        //return json_encode($arr);

        return $arr;

      } else {
        // Log related codes

        $arr = array('status' => False, "msg" => 'PIVC complete Failed');
        return json_encode($arr);

      }
    } catch (Exception $e) {
      // Log failure case
      ApiController::logs($post['application_Number'], 'CompleteStatusFailed', '', $e->getMessage(), "Failure");
      // Handle the exception, e.g., rethrow or return an error response
    }
  }


  public function getFaceScore($proposal, $consentImage, $faceImage, $type = 'default')
  {
    //$url = 'https://test.anurcloud.com/face_compare';
    $url = 'https://test.anurcloud.com/faceapi_indiafirst';
    //https://test.anurcloud.com/faceapi_indiafirst
    //$arr= ["policyno" => $proposal, "image1" => $consentImage, "image2" => $faceImage];
    $arr = ["policyno" => "1212", "image1" => $consentImage, "image2" => $faceImage];
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


  public function speechToText($proposal, $url, $lang, $name)
  {
    
    $curl = curl_init();
    //$tenure = (int)$tenure;
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://demo.anurcloud.com/sud_life_stt',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
        'lang' => $lang,
        'name' => $name,
        'application_number' => $proposal,
        'video_blob' => new \CURLFILE($url),
      )
    ));

    $response = curl_exec($curl);
    //echo $response;die;
    curl_close($curl);
    return $response;
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

      $reqst = $request->all();//dd($reqst['ReqPayload']);

      //unset($reqst['image_base64']);

      //dd($reqst);

      try {

      $jsonString = APIController::decrypt($reqst['ReqPayload']);  

      //$post = explode(",", $jsonString); //dd($post); dd();

      //dd($jsonString);

      $post = json_decode($jsonString, true);  //dd($post['Nominee_details']);

      if(!isset($post['Nominee_details'])){
        $post['Nominee_details']=[];
      }
      if(!isset($post['Rider_details'])){
        $post['Rider_details']=[];
      }

      //dd($post);

      $checkvalidation = ApiController::linkValidation($post);//dd($checkvalidation);

      if(!empty($checkvalidation)){ 
     
      $data = json_decode($checkvalidation, true);

      //$array = explode(",", $string);
       //dd($data);//die;
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
            //$trans->medical_checked_response = 1;
            $trans->agree = 1;

            $trans->created_at = date('Y-m-d H:i:s');
            $trans->updated_at = date('Y-m-d H:i:s');
            $trans->save();

            $photo  = isset($post['image_base64']) ? ($post['image_base64']) : '';

            if ($photo != '') {
              $path = public_path('upload/' . $trans->proposal_no . '/img/');
              if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
              }
              ApiController::base64_to_jpeg($photo, $path . '/link_upload.jpeg');
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


    //      $storageAt = "https://anoor.s3.us-east-1.amazonaws.com/anur_pivc/";
    // echo $path;die;
    //    $backupFilePath = $storageAt . $filename;
    // if(File::exists($backupFilePath)) {
    // $path = Storage::disk('s3')->put($filename, $path);
    // $path = Storage::disk('s3')->url($path); // Get the bucket url for uploaded DB backup file.
    // return $path;
    //echo $path; // Save to Database for backup log or etc. And, delete the local file from storage.
    // }
    // echo $storageAt . $filename;die;
    // $this->validate($request, [
    //   'image' => 'required|image|max:2048'
    //   ]);

    //   if ($request->hasFile('image')) {
    //   $file = $request->file('image');
    //   $name = time() . $file->getClientOriginalName();
    //   Storage::disk('s3')->put($path, file_get_contents($path));
    //  }

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

  public function singleReactivate(Request $request)
  {
    // echo "test"; die;
    $details = $request->all();

    $proposal_no = str_replace("'", "", $details['proposal_no']);
    //echo $proposal_no;die;
    // print_r($request->proposal_no); die;
    $lists = Link::where('proposal_no', $proposal_no)->get();
    // print_r($lists); die;
    if ($lists->isNotEmpty()) {
       //echo "test"; die;
      //$listarchive=new Linksarchive; 
      $listarchive = $lists[0]->replicate();//dd($listarchive);

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
      return $arr = ["status" => "true", "msg" => "reactivated"];
    } else {
      return $arr = ["status" => "false"];
    }
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


    $name = $proposal . '.pdf';
    $path = public_path('upload/' . $proposal . '/') . $name;
    $links = Link::where('proposal_no', $proposal)->get();
    $data = $links[0];
    $pdf = '';
    $policydisagreeResult = [];
    $personaldisagreeResult = [];

           $json1 = json_decode($data->params, true);
           if($data->policy_disagree==1){
            $policy_disagree = $data->policy_disagree_response;
            $json2 = json_decode($policy_disagree, true);
            $nwjson1=$json1['plan_details'];
            $policydisagreeResult = $this->compareJson($json2,$nwjson1);

            if(isset($policydisagreeResult['Rider_details'])){
              foreach ($policydisagreeResult['Rider_details'] as $key => $valueride) {

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

            if(isset($personaldisagreeResult['Nominee_details'])){
              foreach ($personaldisagreeResult['Nominee_details'] as $key => $valuenom) {
                if(isset($valuenom['Nominee_name'])){
                  $personaldisagreeResult['Nominee_name'.$key] = $valuenom['Nominee_name'];
                  
                }
                if(isset($valuenom['Nominee_dob'])){
                  $personaldisagreeResult['Nominee_dob'.$key] = $valuenom['Nominee_dob'];
                  unset($personaldisagreeResult['screen'], $personaldisagreeResult['status']);
                  unset($personaldisagreeResult['Nominee_details']);
                }
                
              }
              //print_r($personaldisagreeResult);
            }
         
           }  
    $location = json_decode($data->location, true); //dd($location);

    $longitude = (isset($location['lat'])) ? $location['lat'] : '';
    $latitude = (isset($location['long'])) ? $location['long'] : '';
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
    //   $response = json_decode($response);//dd($response);
    //   if (isset($response->results[0])) {
    //     $address = $response->results[0]->formatted_address;
    //     $address_disp = $address;
    //   }
    // }

    $mpdf = new Mpdf([
      'default_font' => 'TimesNewRoman',
      'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
          storage_path('fonts'),
      ]),
      'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
          'FreeSerif' => ['R' => 'FreeSerif.otf'],
          'DejaVu' => ['R' => 'DejaVu Serif Condensed.ttf'],
          'TimesNewRoman' => ['R' => 'times new roman.ttf'],
          'kannada' => ['R' => 'kannada.ttf'],
          'telugu' => ['R' => 'telugu.ttf'],
          'default_font' => 'TimesNewRoman',
      ],
  ]);
   
    $html = view('genpdf', compact('data', 'address_disp', 'policydisagreeResult', 'personaldisagreeResult'))->render();

 
    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Save PDF file
    File::put($path, $mpdf->Output('', 'S')); // 'S' returns PDF as a string

    // Retrieve the file
    return response()->file($path);

  }
    
  public function retriggerApi(Request $request)
  {

    $reqst = $request->all();
    $jsonString = APIController::decrypt($reqst['ReqPayload']);  


    $details = json_decode($jsonString, true);  //print_r($details);die;
    if(!empty($details['Application_Number'])){ 
    $proposal_no = $details['Application_Number'];

    $lists = Link::where('proposal_no', $proposal_no)->get();

    if ($lists->isNotEmpty()) {
 
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


       $lists->save();

      $trans = Link::where('proposal_no', $proposal_no)->first();

     //dd($trans);
      $var2 = $trans->short_link;

      $jsonResult = json_encode(['status' => true, 'link' => $var2], 200);
      return response()->json(['TransactionId'=>$reqst['TransactionId'],'ResPayload'=>APIController::encrypt($jsonResult),'Source'=>'SUD']);
    } else {

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
 

  public function linkValidation($val)
  {
   
    //$array = json_decode($val, true);

  $validator = Validator::make($val, [
          "Application_Number" => 'required',
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
        //"Application_Number.max" => "Application Number Must Be Exactly 15 in Length  - Application Number.",
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
              //"Application_Number.Max" => 605,
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

  public function getCallbackurllog(request $request)
  {
     
    $data = $request->all();   //dd($data['Application_Number']);

    $proposal_no = $data['Application_Number'];
    $response = $data['response'];

    ApiController::logs($proposal_no,'callbackurlresp','',json_encode($response), "Success");
  }


  public function callbackurl($proposal)
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
      $Face_Match = $link['face_response']==null ? 0 : $link['face_response'];
      $Face_Match_Per = $link['face_score']==null ? 0 : $link['face_score'];
      $Speech_Text_Match = $speechres->match=="true"?"Yes":"No";
      $Agreement_Status = ($link['policy_disagree']==0 && $link['personal_disagree']==0)?"Yes":"No";
      $Location = json_decode($link['location'], true);
      //$Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_encode($link['nominee_disagree']):null; 
      $Personal_Details_Disagreement = isset($link['nominee_disagree']) ? json_decode($link['nominee_disagree'], true):null; 
      //$Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_encode($link['rider_disagree']):null;
      $Plan_Details_Disagreement = isset($link['rider_disagree']) ? json_decode($link['rider_disagree'], true):null;
      $Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?$plicydisresp->Rider_details:null;
      $Health_Medical_Disagreement = isset($link['medical_checked_response'])?$link['medical_checked_response']:null;
      $Timestamp = $link['completed_on'];
      $Final_Result = $complete_status;

      $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');
      $imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);
      $bawc_imgBase64 =  $imageData;

      $postData = [
        "TransactionId" => "12345",
        "ResPayload" => [
            "Application_Number" => $Application_Number,
            "Face_Match" => $Face_Match,
            "Face_Match_Per" => $Face_Match_Per,
            "Speech_Text_Match" => $Speech_Text_Match,
            "Agreement_Status" => $Agreement_Status,
            "Location" => $Location, // <-- Correct here
            "Personal_Details_Disagreement" => $Personal_Details_Disagreement,
            "Plan_Details_Disagreement" => $Plan_Details_Disagreement,
            "Rider_Details_Disagreement" => $Rider_Details_Disagreement,
            "Health_Medical_Disagreement" => $Health_Medical_Disagreement,
            "Timestamp" => $Timestamp,
            "Final_Result" => $Final_Result,
            "bawc_imgBase64" => $bawc_imgBase64
        ],
        "Source" => "SUD"
    ];
    
    // 4. Then encode everything for cURL
    //$jsonData = json_encode($postData);

    //print_r($postData);die;
    ApiController::logs($proposal_no,'callbackurlreq','',json_encode($postData), "Success");

    //$strData = implode(",", $postData);

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
    
   
  print_r($response);
dd($response);
    ApiController::logs($proposal_no,'callbackurl',$jsonData,json_encode($response), "Success");
    
   
    //die;
    return response()->json(['TransactionId'=>'12345','ResPayload'=>APIController::encrypt($response),'Source'=>'SUD']);
   
    curl_close($curl);
    
  }


  //public function callbackurl($proposal)
  public function callbackurl_old(Request $resquest)
  {

     //dd($resquest->all());
     $reqno = $resquest->all();

     $proposal_no = $reqno['Application_Number'];

     //$proposal_no = $proposal;

     $link = Link::where('proposal_no', $proposal_no)->first();

    $linkdcode = json_decode($link['params']); //dd($linkdecode);

    $callbacklink = $linkdcode->callbackurl; //dd($callbacklink);

    //$callbacklink = '0x87sDUSOuDPjVNVoXOMiHP2tESj9PqypCzsgfww+pGR1aQPmiw31ZXHLdQg40OzZ9fIye/ueqXxOpZfq4RZSA779IZB2Gb8KhB16fwdeacX6c8gRWG2c3QaR9TvG/MJ';

    $callurl = APIController::decrypt($callbacklink);

     $details = json_decode($callurl, true);  //print_r($post);die;

    $callbckurl = $details['callbackurl'];

    

    if ($link->complete_status == 0) {
      $complete_status =  "PIVC not completed";
    } else{
      $complete_status =  "PIVC completed successfully";
    }

      if($link['policy_disagree']==1){
        $plicydisresp = json_decode($link['policy_disagree_response']);
      }

      //

      $speechres = json_decode($link['speech_res']);

      

      $Application_Number = $linkdcode->Application_Number;
      $Face_Match = $link['face_response']=='Face Match Complete'?"Yes":"No";
      $Face_Match_Per = $link['face_score'];
      $Speech_Text_Match = $speechres->match=="true"?"Yes":"No";
      $Agreement_Status = $link['agree']==1?"Yes":"No";
      $Location = $link['location'];
      $Personal_Details_Disagreement = isset($link['personal_disagree_response']) ? $link['personal_disagree_response']:null; 
      $Plan_Details_Disagreement = isset($link['policy_disagree_response'])  ? $link['policy_disagree_response']:null;//dd($link['policy_disagree_response']);
      $Rider_Details_Disagreement = isset($plicydisresp->Rider_details)?$plicydisresp->Rider_details:null;
      $Health_Medical_Disagreement = $link['medical_checked_response'];
      $Timestamp = $link['completed_on'];
      $Final_Result = $complete_status;

      $imagePath = public_path('upload/' . $linkdcode->Application_Number . '/img/video_consent.jpeg');
      $imageData = base64_encode(file_get_contents($imagePath));//dd($imageData);
      $bawc_imgBase64 =  $imageData;

      $data = [
        "Application_Number" => $Application_Number,
        "Face_Match" => $Face_Match,
        "Face_Match_Per" => $Face_Match_Per,
        "Speech_Text_Match" => $Speech_Text_Match,
        "Agreement_Status" => $Agreement_Status,
        "Location" => $Location,
        "Personal_Details_Disagreement" => $Personal_Details_Disagreement,
        "Plan_Details_Disagreement" => $Plan_Details_Disagreement,
        "Rider_Details_Disagreement" => $Rider_Details_Disagreement,
        "Health_Medical_Disagreement" => $Health_Medical_Disagreement,
        "Timestamp" => $Timestamp,
        "Final_Result" => $Final_Result,
        "bawc_imgBase64" => $bawc_imgBase64
      ];

      $jsondata = json_encode($data);

      // $data = [
      //   "Application_Number" => $Application_Number,
      //   "bawc_imgBase64" => $bawc_imgBase64
      // ];

        //dd($data);

    $curl = curl_init();

    if(!isset($callbckurl) && empty($callbckurl)){
      $callbckurl='https://pfsuat.sudlife.in/NewBAWC/NewCallback';
    }
//dd($callbckurl);
    curl_setopt_array($curl, array(
      CURLOPT_URL => $callbckurl,  //https://pfsuat.sudlife.in/BAWC/Callback
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "TransactionId": "12345",
        "ResPayload": $jsondata,
        "Source": "SUD"
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);

    ApiController::logs($proposal_no,'callbackurl','',json_encode($response), "Success");
    
    //dd($response);

    return response()->json(['TransactionId'=>'12345','ResPayload'=>APIController::encrypt($response),'Source'=>'SUD']);
   
    curl_close($curl);
    
  }


  

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
                if ($value !== $array2[$key]) {
                    $difference[$key] = $value;
                }
            }
        } else {
            $difference[$key] = $value;
        }
    }

    return $difference;
}
 
 
}
