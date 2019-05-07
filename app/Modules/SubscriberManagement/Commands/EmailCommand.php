<?php
/**
 * Created by PhpStorm.
 * User: saad
 * Date: 5/16/17
 * Time: 8:03 PM
 */

namespace App\Modules\SubscriberManagement\Commands;


use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use Carbon\Carbon;

class EmailCommand implements Command
{

    public function getCommand($data, ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body)
    {
        $expired_time = new Carbon($data->expired_time);
        $configure_command_header->getSessionId($data->id)
            ->getCasVersion()
            ->getCommandType(13);

        $command_body = $configure_command_body->getCardId($data->card->card_id)
            ->getExpiredTime($expired_time->timestamp)
            ->getEmailSenderNameLength($data->sender_name)
            ->getEmailSenderName($data->sender_name)
            ->getEmailContentLength($data->message_content)
            ->getEmailContent($data->message_content)
            ->getCommandBody();
        $command_header = $configure_command_header->getCommandBodyLength($command_body)->getCommandHeader();
        $command_string = $command_header . $command_body;

        return $command_string;
    }
}