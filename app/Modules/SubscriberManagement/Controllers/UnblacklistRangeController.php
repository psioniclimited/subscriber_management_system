<?php
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Jobs\UnblacklistRangeJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class UnblacklistRangeController extends Controller{

    /**
     * [unblacklistRange loads unblacklist range form]
     * @return [view] [description]
     */
    public function unblacklistRange(){
        return view('SubscriberManagement::cardrange.unblacklist_range');
    }

    public function unblacklistRangeProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        $this->dispatch(new UnblacklistRangeJob($request->all(), $commandMaker, $socketHelper, $updateCommandInformationHelper));
    }
}