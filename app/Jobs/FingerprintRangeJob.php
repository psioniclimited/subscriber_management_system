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
use App\Modules\SubscriberManagement\Models\FingerprintHistory;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class FingerprintRangeJob extends Job implements ShouldQueue
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
            $fingerprint_history = new FingerprintHistory();
            $fingerprint_history->duration = $this->request['duration'];
            $fingerprint_history->expired_time = $merged_expire_date_time;
            $fingerprint_history->cards_id = $card->id;
            $fingerprint_history->save();
            $command_string = $this->commandMaker->getFingerPrintCommand($fingerprint_history);
            $message = hex2bin($command_string);
            $response_from_cas = $this->socketHelper->sendCommandToCas($message);

            $response_from_error = $this->updateCommandInformationHelper->updateCommandInformation($response_from_cas, $fingerprint_history);
        }
    }
}
