<?php
namespace App\Exports;
// use App\Transaction;
// use App\Proposal;
use App\Models\FailedProductLogs;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Crypt;

class FailedProductLinksReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
 
    return FailedProductLogs::query()->whereDate('created_at', '>=',  $this->min)->whereDate('created_at', '<=',  $this->max);
    
  }
  public function map($export): array
  {
  
   
    return [
  
      $export->id,
      $export->proposal_no,
      $export->product_name,
      $export->plan_name,
      $export->created_at, 
    ];
  }

  public function headings(): array
  {
    return [

      "Id", 
      "Proposal No",  
      "Policy Prod Name",
      "Policy Plan Name", 
      "Created At",  
      
    ];
  }
}
