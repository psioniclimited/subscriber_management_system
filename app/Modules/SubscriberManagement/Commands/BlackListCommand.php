<?php

namespace App\Modules\SubscriberManagement\Commands;


use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use Carbon\Carbon;
class BlackListCommand implements Command
{

    public function getCommand($data, ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body)
    {
        $expired_time = new Carbon($data->expired_time);

        $configure_command_header->getSessionId($data->id)
            ->getCasVersion()
            ->getCommandType(11);

        $command_body = $configure_command_body->getCardId($data->card->card_id)
            ->getSendOrNotSend()
            ->getCardStatus($data->card_status)
            ->getExpiredTime($expired_time->timestamp)
            ->getCommandBody();

        $command_header = $configure_command_header->getCommandBodyLength($command_body)
            ->getCommandHeader();
        //completed command string
        $command_string = $command_header . $command_body;
        return $command_string;
    }
}