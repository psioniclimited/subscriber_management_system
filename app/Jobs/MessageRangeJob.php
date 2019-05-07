<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\CasCommandHelper;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\MessageHistory;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class MessageRangeJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $request, $commandMaker, $socketHelper, $updateCommandInformationHelper;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper)
    {
        $this->request = $request;
        $this->commandMaker = $commandMaker;
        $this->socketHelper = $socketHelper;
        $this->updateCommandInformationHelper = $updateCommandInformationHelper;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $expire_date = $this->request['datepicker_expire_date'];
        $expire_time = $this->request['timepicker_expire_time'];
        $merged_expire_date_time = $expire_date.' '.$expire_time;
        
        $between = [intval($this->request['from_card_number']), intval($this->request['to_card_number'])];

        $cards = Card::whereBetween('card_id', $between)->get();

        foreach ($cards as $card){
            $message_history = new MessageHistory();
            $message_history->text = $this->request['text'];
            $message_history->message_type = $this->request['message_type'];
            $message_history->expired_time = $merged_expire_date_time;
            $message_history->show_time_length = $this->request['show_time_length'];
            $message_history->show_times = $this->request['show_times'];
            $message_history->coverage_rate = $this->request['coverage_rate'];
            $message_history->cards_id = $card->id;
            $message_history->save();
            $command_string = $this->commandMaker->getMessageCommand($message_history);
            $message = hex2bin($command_string);
            $response_from_cas = $this->socketHelper->sendCommandToCas($message);

            $response_from_error = $this->updateCommandInformationHelper->updateCommandInformation($response_from_cas, $message_history);
        }
    }
}
