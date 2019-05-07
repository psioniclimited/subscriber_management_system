<?php
/**
 * Created by PhpStorm.
 * User: saad
 * Date: 12/20/16
 * Time: 5:56 PM
 */

namespace App\Modules\SubscriberManagement\Commands;


use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;

class UnPairCommand implements Command
{

    public function getCommand($data, ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body)
    {
        $expired_time = new Carbon($data->expired_time);
        $configure_command_header->getSessionId($data->id)
            ->getCasVersion()
            ->getCommandType(4);

        $command_body = $configure_command_body->getCardId($data->card->card_id)
            ->getExpiredTime($expired_time->timestamp)
            ->getCommandBody();

        $command_header = $configure_command_header->getCommandBodyLength($command_body)
            ->getCommandHeader();

        $command_string = $command_header . $command_body;
    }
}