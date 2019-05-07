<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
class BlackListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCardBlackList(ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body, SocketHelper $socketHelper)
    {
//        $configure_command_header->getSessionId('3')
//            ->getCasVersion()
//            ->getCommandType(11);
//
//        $command_body = $configure_command_body->getCardId('')
//            ->getProductAmount()
//            ->getSendOrNotSend()
//            ->getTapping()
//            ->getProductId($entitle->products_id)
//            ->getStartTime($begin_time)
//            ->getEndTime($finish_time)
//            ->getDescriptionLength()
//            ->getCommandBody();
//
//        $command_header = $configure_command_header->getCommandBodyLength($command_body)
//            ->getCommandHeader();
//        //completed command string
//        $command_string = $command_header . $command_body;
//        //send command to cas
//        $message = hex2bin($command_string);
//        $response_from_cas = $socketHelper->sendCommandToCas($message);
    }

    public function testUnBlackList(ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body, SocketHelper $socketHelper){

    }
}
