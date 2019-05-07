<?php

namespace App\Modules\SubscriberManagement\Commands;

use App\Modules\SubscriberManagement\Helpers\ConfigureFingerPrintBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureQueryCommandHeader;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use Carbon\Carbon;

use App\Modules\SubscriberManagement\Helpers\ConfigureFingerPrintBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureQueryCommandHeader;

class FingerPrintCommand implements Command
{

    /**
     * @param $data
     * @param ConfigureCommandHeader $configure_command_header
     * @param ConfigureCommandBody $configure_command_body
     * @return string
     */
    public function getCommand($data, ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body)
    {
<<<<<<< Updated upstream
        $begin_time = Carbon::now()->timestamp;
        $end_time = Carbon::tomorrow()->timestamp;

        $configure_command_body = new ConfigureFingerPrintBody();
        $command_body = $configure_command_body->getServiceCount(1)
            ->getNetworkId(1)
            ->getTsId(1)
            ->getServiceId(1)
            ->getReserveFourBytes()
            ->getFingerPrintType(4)
            ->getShowType(0)
            ->getEncryptOrNot(1)
            ->getLocationFromTop(10)
            ->getLocationFromLeft(10)
            ->getSize(50)
            ->getReserve()
            ->getDurationTime(90)
            ->getIntervalTime(90)
            ->getBackColor(4294967040)
            ->getFontColor(65535)
            ->getBeginTime($begin_time)
            ->getEndTime($end_time)
            ->getReserveFourBytes(0)
            ->getFingerPrintBody();

        $body = '';

        $configure_query_command_header = new ConfigureQueryCommandHeader();
        $command_header = $configure_query_command_header->getSessionId(1)
            ->getLength($command_body)
            ->getReserveOneByte(128)
            ->getReserveOneByte(255)
            ->getReserveOneByte(0)
            ->getReserveOneByte(0)
            ->getCommandType(3)
            ->getReserveThreeBytes(0)
            ->getReserveOneByte(1)
            ->getCommandBodyLength($command_body)
            ->addDataBody($command_body)
            ->getReserveFourBytes(4294967295)
            ->getClientIp("10.190.180.140")
            ->getCommandHeader();

        return $command_header;
=======
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
>>>>>>> Stashed changes
    }
}