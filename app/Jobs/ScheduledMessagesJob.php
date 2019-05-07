<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\CasCommandHelper;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\EmailHistory;
use App\Modules\SubscriberManagement\Models\MessageHistory;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;
use Carbon\Carbon;

class ScheduledMessagesJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $commandMaker, $socketHelper, $updateCommandInformationHelper;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper)
    {
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
        //1- Query card_entitle_history where end_time is 3 days later and active entitlement
        //2-Create a job for scheduled messages
        //3-Call the job
        $certain_date = Carbon::now()
        ->addDays(2)
        ->toDateTimeString();
        $next_certain_date = Carbon::now()
        ->addDays(4)
        ->toDateTimeString();
         
        $card_entitle_history = CardEntitlementHistory::whereBetween('end_time', [$certain_date, $next_certain_date])
        ->where('unentitled', 0)
        ->get();

        $expired_time = Carbon::now()->addDays(1);
        
        $message = [
                  "text" => "Your subscription is about to expire please pay your bills at your earliest convenience",
                  "message_type" => "2",
                  "expired_time" => $expired_time,
                  "show_time_length" => "3600",
                  "show_times" => "500",
                  "coverage_rate" => "0"
                  ];
        $email = [
                  "message_content" => "Your subscription is about to expire please pay your bills at your earliest convenience",
                  "expired_time" => $expired_time,
                  "sender_name" => "Digi21"
                 ];
        
        foreach ($card_entitle_history as $each_history){

            $message["cards_id"] = $each_history->cards_id;
            $message_history = MessageHistory::create($message);

            $email["cards_id"] = $each_history->cards_id;
            EmailHistory::create($email);
            
             $command_string = $this->commandMaker->getMessageCommand($message_history);
             $message = hex2bin($command_string);
             $response_from_cas = $this->socketHelper->sendCommandToCas($message);

            $response_from_error = $this->updateCommandInformationHelper->updateCommandInformation($response_from_cas, $message_history);
        }
        dd('success');
    }
}
