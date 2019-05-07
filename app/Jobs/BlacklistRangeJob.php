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
use App\Modules\SubscriberManagement\Models\Product;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\BlacklistHistory;
use Carbon\Carbon;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class BlacklistRangeJob extends Job implements ShouldQueue
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
        $between = [intval($this->request['from_card_number']), intval($this->request['to_card_number'])];

        $cards = Card::whereBetween('card_id', $between)->get();

        foreach ($cards as $card){
            $blacklist_card = new BlacklistHistory();

            // Blacklist duration in days
            $blacklist_duration = $this->request['blacklist_duration'];
            // Today's date
            $today = Carbon::today();
            // Add days to today
            $formatted_duration = $today->addDays($blacklist_duration);

            $blacklist_card->expired_time = $formatted_duration;
            $blacklist_card->send_or_not_send = 1;
            $blacklist_card->cards_id = $card->id;
            $blacklist_card->card_status = 0;
            $blacklist_card->note = $this->request['note'];

            $blacklist_card->save();

            // Update blacklisted status to 1 in cards table
            $card_blacklisted_status = Card::where('id', $card->id)
            ->update(['blacklisted' => 1]);

            $command_string = $this->commandMaker->getBlackListCommand($blacklist_card);
            $message = hex2bin($command_string);
            $response_from_cas = $this->socketHelper->sendCommandToCas($message);

            $response_from_error = $this->updateCommandInformationHelper->updateCommandInformation($response_from_cas, $blacklist_card);
        }
    }
}
