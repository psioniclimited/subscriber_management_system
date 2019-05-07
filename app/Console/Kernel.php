<?php

namespace App\Console;

use App\Modules\SubscriberManagement\Commands\CommandMaker;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use App\Modules\SubscriberManagement\Helpers\UpdateCommandInformationHelper;
use App\Modules\SubscriberManagement\Models\EmailHistory;
use App\Modules\SubscriberManagement\Models\MessageHistory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\ScheduledMessagesJob;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (CommandMaker $commandMaker, SocketHelper $socketHelper, UpdateCommandInformationHelper $updateCommandInformationHelper) {

            $certain_date = Carbon::now()
                ->toDateTimeString();
            $next_certain_date = Carbon::now()
                ->addDays(3)
                ->toDateTimeString();
            $card_entitle_history = CardEntitlementHistory::whereBetween('end_time', [$certain_date, $next_certain_date])
                ->where('unentitled', 0)
                ->get();

//            $expired_time = Carbon::now()->addDays(1)->format('d/m/Y g:i A');

            $message = [
                "text" => "Your subscription is about to expire please pay your bills at your earliest convenience",
                "message_type" => "2",
                "expired_time" => Carbon::now()->addDays(1)->format('d/m/Y g:i A'),
                "show_time_length" => "3600",
                "show_times" => "255",
                "coverage_rate" => "0"
            ];
            $email = [
                "message_content" => "Your subscription is about to expire please pay your bills at your earliest convenience",
                "expired_time" => Carbon::now()->addDays(1)->toDateTimeString(),
                "sender_name" => "Digi21"
            ];

            foreach ($card_entitle_history as $each_history){

                $message["cards_id"] = $each_history->cards_id;
                $message_history = MessageHistory::create($message);

                $email["cards_id"] = $each_history->cards_id;
                $email_history = EmailHistory::create($email);

                $command_string = $commandMaker->getMessageCommand($message_history);
                $message = hex2bin($command_string);
                $response_from_cas = $socketHelper->sendCommandToCas($message);

                $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $message_history);

                $command_string = $commandMaker->getEmailCommand($email_history);

                $message = hex2bin($command_string);
                $response_from_cas = $socketHelper->sendCommandToCas($message);

                $response_from_error = $updateCommandInformationHelper->updateCommandInformation($response_from_cas, $message_history);
            }

        })->dailyAt('20:00');
    }
}
