<?php

namespace App\Modules\SubscriberManagement\Commands;


use App\Modules\SubscriberManagement\Helpers\ConfigureFingerPrintBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureQueryCommandHeader;

class FingerPrintCommand implements Command
{

    public function getCommand($data, $configure_command_header, $configure_command_body)
    {
        $configure_query_command_header = new ConfigureQueryCommandHeader();
        $configure_command_body = new ConfigureFingerPrintBody();

        $configure_query_command_header->getSessionId()
        ->getReserveOneByte(128)
        ->getReserveOneByte(255)
        ->getReserveOneByte(0)
        ->getReserveOneByte(0)
        ->getCommandType(3)
        ->getReserveThreeBytes(0)
        ->getReserveOneByte(1)
        ;
    }
}