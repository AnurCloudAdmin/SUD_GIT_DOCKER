<?php
namespace App\Exports;
// use App\Transaction;
// use App\Proposal;
use App\Models\Linksarchive;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Crypt;

class TrailLinksReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{ 
  use Exportable;

  public function __construct($min,$max)
  { 
    // echo "test"; die;
    $this->min = $min;
    $this->max = $max; 
    
  }

  public function query()
  {
    set_time_limit(0);
 
    return Linksarchive::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max);
    
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
   
    return [
  
      $export->id,
      $export->proposal_no,
      // $export->url, 
      $export->short_link,
      $params["personal_name"],
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
      // $export->images,
      // $export->video,
      $export->status,
      $export->version,
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
      // "Images", 
      // "Video", 
      "Status", 
      "Version", 
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
