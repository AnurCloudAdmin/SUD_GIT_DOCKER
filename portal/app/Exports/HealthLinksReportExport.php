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

class HealthLinksReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{ 
  use Exportable;

  public function __construct($min,$max,$statuscheck)
  { 
    // echo "test"; die;
    
    $this->min = $min;
    $this->max = $max; 
    $this->statuscheck = $statuscheck; 
    
  }

  public function query()
  {
      set_time_limit(0);
  
      $query = Link::query()->whereDate('created_at', '>=', $this->min)->whereDate('created_at', '<=', $this->max);
  
      if ($this->statuscheck === '0') {
          return $query;
      } elseif ($this->statuscheck == 1) 
      {
          return $query->where('complete_status', 0)->where('is_open', 1);
      } elseif ($this->statuscheck == 2)
       {
          return $query->where('complete_status', 1)->where('personal_disagree', 'Agree')->where('policy_disagree', 'Agree');
      } elseif ($this->statuscheck == 3) 
      {
          return $query->where('complete_status', 1)->where(function ($query)
           {
                  $query->where('personal_disagree', 'Disagree')->orWhere('policy_disagree', 'Disagree');
            });
      } elseif ($this->statuscheck === '4') 
      {
          return $query->where('is_open', 0);
      }
  
      return $query; // fallback if no conditions match
  }
  
  public function map($export): array
  {
    // echo "<pre>";

    // print_r($export); die;
    
   $params = json_decode($export->params,true);

   if( $export->status == "1") { $export->status = "Active"; } else { $export->status = "In active"; }
   if( $export->is_open == "1") { $export->is_open = "Is open"; } else { $export->is_open = "Not open"; }

   if( $export->complete_status == "1") { 

    if( $export->personal_disagree=='Agree' && $export->policy_disagree=='Agree') { 

      $export->complete_status = "Completed";

      }else {
        $export->complete_status = "Completed with Discrepancy";
      }

    }else{
      $export->complete_status = "Not Completed";
      
    }
    
    $fedo_links = json_decode($export->fedo_vitals,true);
   $healthVitals = "No";
   if(!empty($fedo_links)){
    $healthVitals = "Yes";
   }
   $healthVitalsStatus = $export->fedo_status;
    return [
 
      $export->id,
      $export->proposal_no,
      // $export->url, 
      $export->short_link,
      (isset($params['personal_name']))? $params['personal_name']: '',
      $params["personal_dob"],
      $params["personal_gender"],
      $params["personal_occupation"],
      $params["personal_email"],
      $params["personal_mobile"],
      $params["personal_address"],
      $params["policy_prod_name"],
      $params["policy_sum_assured"],
      $params["policy_rider_name"],
      $params["policy_rider_sum_assured"],
      $params["policy_preimum_amount"],
      $params["policy_payment_type"],
      $params["policy_Frequency"],
      $healthVitals,
      $healthVitalsStatus,
      (isset($fedo_links['customerID'])) ? $fedo_links['customerID'] : '',
      (isset($fedo_links['clientID'])) ? $fedo_links['clientID'] : '',
      (isset($fedo_links['gender'])) ? $fedo_links['gender'] : '',
      (isset($fedo_links['heart_rate'])) ? $fedo_links['heart_rate'] : '',
      (isset($fedo_links['systolic'])) ? $fedo_links['systolic'] : '',
      (isset($fedo_links['diastolic'])) ? $fedo_links['diastolic'] : '',
      (isset($fedo_links['stress_level'])) ? $fedo_links['stress_level'] : '',
      (isset($fedo_links['respiration_rate'])) ? $fedo_links['respiration_rate'] : '',
      (isset($fedo_links['blood_oxygen'])) ? $fedo_links['blood_oxygen'] : '',
      (isset($fedo_links['hemoglobin'])) ? $fedo_links['hemoglobin'] : '',
      (isset($fedo_links['HRV-SDNN'])) ? $fedo_links['HRV-SDNN'] : '',
      (isset($fedo_links['RBS'])) ? $fedo_links['RBS'] : '',
      (isset($fedo_links['bmi'])) ? $fedo_links['bmi'] : '',
      (isset($fedo_links['smoker'])) ? $fedo_links['smoker'] : '', 
      

      // $export->images,
      // $export->video,
      $export->status,
      $export->personal_disagree,
      $export->policy_disagree,
      $export->is_open,
      $export->is_open_at,
      $export->device,
      $export->network,
      $export->location,
      $export->complete_status,
      $export->completed_on,
      $export->created_at,
      $export->updated_at,
   
    ];
    
  }
  
  public function headings(): array
  {
    return [

      "Id", 
      "Proposal No", 
      // "URL", 
      "Short Link", 
      "Personal Name",
      "Personal DOB",
      "Personal Gender",
      "Personal Occupation",
      "Personal Email",
      "Personal Mobile",
      "Personal Address",
      "Policy Prod Name",
      "Policy Sum Assured",
      "Policy Rider Name",
      "Policy Rider Sum Assured",
      "Policy Premium Amount",
      "Policy Payment Type",
      "Policy Frequency",
      "Health Vitals",
      "Health Vitals Status",
      "CustomerID",
      "ClientID",
      "Gender",
      "Heart rate",
      "Systolic",
      "Diastolic",
      "Stress level",
      "Respiration rate",
      "Blood oxygen",
      "Hemoglobin",
      "HRV-SDNN",
      "RBS",
      "Bmi",
      "Smoker", 
      
      // "Images", 
      // "Video", 
      "Status", 
      "Personal Disagree", 
      "Policy Disagree", 
      "Is Open", 
      "Is Open At", 
      "Device", 
      "Network", 
      "Location", 
      "Complete Status", 
      "Completed On", 
      "Created At", 
      "Updated At", 
      
    ];
  }
}
