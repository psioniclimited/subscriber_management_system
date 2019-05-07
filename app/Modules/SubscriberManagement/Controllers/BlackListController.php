<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Commands\BlackListCommand;
use Illuminate\Http\Request;
use Entrust;
use Datatables;
use Carbon\Carbon;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Models\BlacklistHistory;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class BlackListController extends Controller {

    public function blackListCard(ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body, SocketHelper $socketHelper, $id, Request $request){

        // dd('Test. In BlackListController.');
        


        $configure_command_header->getSessionId(5)
            ->getCasVersion()
            ->getCommandType(11);

        $command_body = $configure_command_body->getCardId('57398895')
            ->getSendOrNotSend()
            ->getCardStatus(0)
            ->getExpiredTime('1483052389')
            ->getCommandBody();

        $command_header = $configure_command_header->getCommandBodyLength($command_body)
            ->getCommandHeader();
        //completed command string
        $command_string = $command_header . $command_body;
        //send command to cas
        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);
        dd($response_from_cas);
    }

    public function unBlackListCard(ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body, SocketHelper $socketHelper){
        $configure_command_header->getSessionId(5)
            ->getCasVersion()
            ->getCommandType(11);

        $command_body = $configure_command_body->getCardId('57398895')
            ->getSendOrNotSend()
            ->getCardStatus()
            ->getExpiredTime('1483052389')
            ->getCommandBody();

        $command_header = $configure_command_header->getCommandBodyLength($command_body)
            ->getCommandHeader();
        //completed command string
        $command_string = $command_header . $command_body;
        //send command to cas
        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);
        dd($response_from_cas);
    }

    /**
     * [blackListCardProcess -blacklist a card]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function blackListCardProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        
        // Card id
        $card_id = $request->input('card_id'); 
        $card = Card::findOrFail($card_id);
        // Form data
        $form_data = $request->input('form_data');
        // Blacklist duration in days
        $blacklist_duration = $form_data[1]['value'];
        // Blacklist note
        $blacklist_note = $form_data[2]['value'];
        // Today's date
        $today = Carbon::today();
        // Add days to today
        $formatted_duration = $today->addDays($blacklist_duration);

        $blacklistHistory = new BlacklistHistory();
        $blacklistHistory->expired_time = $formatted_duration;
        $blacklistHistory->send_or_not_send = 1;
        $blacklistHistory->cards_id = $card->id;
        $blacklistHistory->card_status = 0;
        $blacklistHistory->note = $blacklist_note;

        $blacklistHistory->save();
        
        $command_string = $commandMaker->getBlackListCommand($blacklistHistory);

        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);

        $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $blacklistHistory);

        // Update blacklisted status to 1 in cards table
        $card_blacklisted_status = Card::where('id', $card->id)
                                       ->update(['blacklisted' => 1]);

        return "success";

    }

    /**
     * [unBlackListCardProcess -unblacklist a card]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function unBlackListCardProcess(Request $request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper){
        
        // Card id
        $card_id = $request->input('card_id'); 
        $card = Card::findOrFail($card_id);

        $blacklistHistory = new BlacklistHistory();
        $blacklistHistory->send_or_not_send = 1;
        $blacklistHistory->cards_id = $card->id;
        $blacklistHistory->card_status = 1;

        $blacklistHistory->expired_time = Carbon::tomorrow();
        $blacklistHistory->save();

        $command_string = $commandMaker->getBlackListCommand($blacklistHistory);

        $message = hex2bin($command_string);
        $response_from_cas = $socketHelper->sendCommandToCas($message);

        $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $blacklistHistory);
        
        // Update blacklisted status to 1 in cards table
        $card_blacklisted_status = Card::where('id', $card->id)
                                       ->update(['blacklisted' => 0]);

        return "success";

    }


    public function allBlacklistedCards(){

        return view('SubscriberManagement::card.all_blacklisted_cards');
    }

    public function getBlacklistedCards(){
        $blacklisted_cards = Card::with(['last_blacklist_history'=> function($query){
            $query->where('expired_time', '<', Carbon::now())
            ->where('execution_status', 1);
        }])->where('blacklisted', 1)
        ->get();

        return Datatables::of($blacklisted_cards)
        ->make(true);
    }
}
