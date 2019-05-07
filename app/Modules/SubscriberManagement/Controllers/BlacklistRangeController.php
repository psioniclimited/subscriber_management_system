<?php
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\BlacklistRangeJob;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class BlacklistRangeController extends Controller{

	/**
	 * [blacklistRange loads blacklist range form]
	 * @return [view] [description]
	 */
	public function blacklistRange(){
        return view('SubscriberManagement::cardrange.blacklist_range');
    }

    /**
     * [blacklistRangeProcess -blacklist range of cards]
     * @param  Request      $request      [description]
     * @param  CommandMaker $commandMaker [description]
     * @param  SocketHelper $socketHelper [description]
     * @return [type]                     [description]
     */
    public function blacklistRangeProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
    	$this->dispatch(new BlacklistRangeJob($request->all(), $commandMaker, $socketHelper, $updateCommandInformationHelper));
    }
}