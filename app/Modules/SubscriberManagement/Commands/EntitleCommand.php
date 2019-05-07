<?php
namespace App\Modules\SubscriberManagement\Commands;


use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;
use Carbon\Carbon;
class EntitleCommand implements Command
{

    /**
     * @param CardEntitlementHistory $data
     * @param ConfigureCommandHeader $configure_command_header
     * @param ConfigureCommandBody $configure_command_body
     * @return string
     */
    public function getCommand($data, ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body)
    {
        $begin_time = new Carbon($data->start_time);
        $end_time = new Carbon($data->end_time);

        $configure_command_header->getSessionId($data->id)
            ->getCasVersion()
            ->getCommandType(1);

        $command_body = $configure_command_body->getCardId($data->card->card_id)
            ->getProductAmount()
            ->getSendOrNotSend()
            ->getTapping()
            ->getProductId($data->product->product_id)
            ->getStartTime($begin_time->timestamp)
            ->getEndTime($end_time->timestamp)
            ->getDescriptionLength()
            ->getCommandBody();

        $command_header = $configure_command_header->getCommandBodyLength($command_body)
            ->getCommandHeader();
        //completed command string
        $command_string = $command_header . $command_body;
        return $command_string;
    }
}