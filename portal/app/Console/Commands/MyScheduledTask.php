<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\ApiController;

class MyScheduledTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:my-scheduled-task';

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

        $link_data = Links::where('docpush', 0)
                ->where('completed_status', 1) 
                ->get(); 
                if(!empty($link_data)){
                    foreach($link_data as $link){
                        $apiCtrl = new ApiController();
                        $apiCtrl->docCheckstatus($link->proposal_no);
                    }
                }
                
    }
}
