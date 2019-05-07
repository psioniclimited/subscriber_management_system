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
use App\Modules\SubscriberManagement\Models\BlacklistHistory;
use Carbon\Carbon;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class UnblacklistRangeJob extends Job implements ShouldQueue
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
            $unblacklist_card = new BlacklistHistory();

            $unblacklist_card->send_or_not_send = 1;
            $unblacklist_card->cards_id = $card->id;
            $unblacklist_card->card_status = 1;
            $unblacklist_card->expired_time = Carbon::tomorrow();

            $unblacklist_card->save();

            // Update blacklisted status to 0 in cards table
            $card_blacklisted_status = Card::where('id', $card->id)
            ->update(['blacklisted' => 0]);

            $command_string = $this->commandMaker->getBlackListCommand($unblacklist_card);
            $message = hex2bin($command_string);
            $response_from_cas = $this->socketHelper->sendCommandToCas($message);

            $response_from_error = $this->updateCommandInformationHelper->updateCommandInformation($response_from_cas, $unblacklist_card);
        }
    }
}
