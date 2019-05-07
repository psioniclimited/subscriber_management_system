<?php
namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\MessageRangeJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class MessageCardRangeController extends Controller{

	/**
	 * [messageCardRange -loads message range form]
	 * @return [view] [description]
	 */
	public function messageCardRange(){
        return view('SubscriberManagement::cardrange.message_card_range');
    }

    /**
     * [messageCardRangeProcess -sends the task to a queue]
     * @param  Request      $request      [description]
     * @param  CommandMaker $commandMaker [description]
     * @param  SocketHelper $socketHelper [description]
     * @return [type]                     [description]
     */
    public function messageCardRangeProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
    	$this->dispatch(new MessageRangeJob($request->all(), $commandMaker, $socketHelper, $updateCommandInformationHelper));
    	return back();
    }
}

