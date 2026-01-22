<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\ApiController;

class MyBulkuploadTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:my-bulkupload-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $link_data = uploadlink::where('create_status', 0)
                ->get(); 
                if(!empty($link_data)){
                    foreach($link_data as $link){
                        $apiCtrl = new ApiController();
                        $apiCtrl->doBulkUpload($link->app_no, $link->request);
                    }
                }
                
    }
}
