<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Commands\MessageCommand;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use Illuminate\Http\Request;
use Datatables;
use App\Modules\SubscriberManagement\Models\Message;
use App\Modules\SubscriberManagement\Models\EmailHistory;
use App\Modules\SubscriberManagement\Models\MessageHistory;
use App\Modules\SubscriberManagement\Models\Card;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\ScheduledMessagesJob;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class MessageController extends Controller {

    public function sendOSD() {
        return view('SubscriberManagement::messages.send_osd');
    }

    public function sendOsdProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        // Store message data in message history table
        $expire_date = $request->input('datepicker_expire_date');
        $expire_time = $request->input('timepicker_expire_time');
        $merged_expire_date_time = $expire_date.' '.$expire_time;

        $message_history = new MessageHistory();

        $message_history->text = $request->input('text');
        $message_history->message_type = $request->input('message_type');
        $message_history->expired_time = $merged_expire_date_time;
        $message_history->show_time_length = $request->input('show_time_length');
        $message_history->show_times = $request->input('show_times');
        $message_history->coverage_rate = $request->input('coverage_rate');
        $message_history->cards_id = $request->input('cards_id');

        $message_history->save();
        
        $command_string = $commandMaker->getMessageCommand($message_history);
        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);

        $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $message_history);
        return back();
    }

    public function sendEmail(){
    	return view('SubscriberManagement::messages.send_email');
    }

    public function sendEmailProcess(Request $request) {
        $email_history = EmailHistory::create($request->all());
        return back();
    }

    public function allOsdMessages(){
        return view('SubscriberManagement::messages.all_osd_messages');
    }

    public function getOsdMessages(){
        $osd_messages = MessageHistory::with('card')
        ->where('execution_status', 1);
        
        return Datatables::of($osd_messages)
        ->make(true);
    }

    /**
     * TO BE REMOVED LATER
     */
    public function testScheduledMessages() {
        $cards = Card::with('user', 'last_blacklist_history')->get();
        foreach ($cards as $card) {
            dd($card->user->manager());
            dd('test');
        }
        // $this->dispatch(new ScheduledMessagesJob());
    }
}
