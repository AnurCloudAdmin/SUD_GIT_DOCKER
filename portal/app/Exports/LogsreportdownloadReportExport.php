<?php
namespace App\Exports;
// use App\Transaction;
// use App\Proposal;
use App\Models\Logs;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
class LogsreportdownloadReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
 
    // return FailedProductLogs::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max);
    $subQuery = Logs::selectRaw('MAX(id) as max_id')
                      ->whereDate('created_at', '>=',  $this->min)
                      ->whereDate('created_at', '<=',  $this->max)
                      ->groupBy('app_no');
    
  return Logs::whereIn('id', $subQuery)
                ->orderBy('id', 'desc');
              
  }
  public function map($export): array
  {
   
    $_params = json_decode(@$export->getLink->params,true); 
                      
    $is_open = " ";
     if ($export->module == 'Not Attempted' ){
      $is_open = "No";
     }else{
      $is_open = "Yes";
     }
     $submitted = "No";
     $submitted_at = "";
     if (@$export->getLink->complete_status == 1 ){
      $submitted = "Yes";
      $submitted_at = @$export->getLink->completed_on;
     }
     $responsesuccess = " ";
     $responsefailure = " ";
     if (Str::contains($export->module, 'Failed')){
          $responsesuccess = "-";
          $responsefailure =$export->response;
     }else{
          $responsesuccess = "Yes";
          $responsefailure = " ";
     }

    return [
      $export->id,
      $export->app_no,
      $is_open,
      $export->module,
      $responsesuccess,
      $responsefailure,
      $export->created_at, 
    ];
  }
 
  public function headings(): array
  {
    return [
      "Id", 
      "Application No",  
      "Mobile No",
      "Link Open",
      "Link Open At",
      "Stage",
      "Submitted",
      "Submitted At",
      "Error Log", 
      "Created At",  
    ];
  }
}
