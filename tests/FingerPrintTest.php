<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Modules\SubscriberManagement\Helpers\ConfigureFingerPrintBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureQueryCommandHeader;

class FingerPrintTest extends TestCase
{
    public function testFingerPrint()
    {
//        $configure_query_command_header = new ConfigureQueryCommandHeader();
//        $configure_query_command_header->getSessionId()
//        ->getReserveOneByte(128)
//        ->getReserveOneByte(255)
//        ->getReserveOneByte(0)
//        ->getReserveOneByte(0)
//        ->getCommandType(3)
//        ->getReserveThreeBytes(0)
//        ->getReserveOneByte(1)
//        ;
//
//        $configure_finger_body = new ConfigureFingerPrintBody();
    }
}
