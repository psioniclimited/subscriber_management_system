<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Models\Product;
use App\Modules\SubscriberManagement\Models\Card;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;
use App\Modules\SubscriberManagement\Helpers\CasCommandHelper;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;

class EntitleRangeJob extends Job implements ShouldQueue
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
        // dd($this->request);
        $start_date = $this->request['datepicker_start_date'];
        $start_time = $this->request['timepicker_start_time'];
        $merged_start_date_time = $start_date.' '.$start_time;

        $end_date = $this->request['datepicker_end_date'];
        $end_time = $this->request['timepicker_end_time'];
        $merged_end_date_time = $end_date.' '.$end_time;

        $between = [intval($this->request['from_card_number']), intval($this->request['to_card_number'])];

        $cards = Card::whereBetween('card_id', $between)->get();

        foreach ($cards as $card){
            $entitle = new CardEntitlementHistory();
            $entitle->products_id = $this->request['product'];
            $entitle->start_time = $merged_start_date_time;
            $entitle->end_time = $merged_end_date_time;
            $entitle->operations_id = 1;
            $entitle->cards_id = $card->id;
            $entitle->save();
            $command_string = $this->commandMaker->getEntitleCommand($entitle);
            $message = hex2bin($command_string);
            $response_from_cas = $this->socketHelper->sendCommandToCas($message);
            $response_from_cas = $this->socketHelper->sendCommandToCas($message);

            $response_from_error = $this->updateCommandInformationHelper->updateCommandInformation($response_from_cas, $entitle);
            // echo $response_from_cas;
        }
        // return response()->json($cards);
    }
}
