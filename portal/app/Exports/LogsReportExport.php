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
use App\Models\Logs;

class LogsReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{ 
  use Exportable;

  public function __construct($application_no)
  { 
    // echo "test"; die;
    $this->application_no = $application_no;    
  }

  public function query()
  {
    set_time_limit(0);
    return Logs::query()->where('app_no',$this->application_no)->orderBy('id', 'desc');
  }
  public function map($export): array
  {
    // echo "<pre>";

    // print_r($export); die;
   
    return [

      $export->app_no,
      $export->created_at,
      $export->module,
    ];
  }

  public function headings(): array
  {
    return [
     
      "Application No", 
      "Created at", 
      "Module",
   
    ];
  }
}
