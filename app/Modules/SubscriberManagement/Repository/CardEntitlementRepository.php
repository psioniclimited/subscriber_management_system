<?php
/**
 * Created by PhpStorm.
 * User: saad
 * Date: 7/17/17
 * Time: 1:20 PM
 */

namespace App\Modules\SubscriberManagement\Repository;
use App\Modules\SubscriberManagement\Models\CardEntitlementHistory;

class CardEntitlementRepository
{
    public function getLatestCardEntitlementHistory($card){
        return CardEntitlementHistory::where('cards_id', $card->id)
            ->where('unentitled', 0)
            ->where('execution_status', 1)
            ->orderBy('end_time', 'desc')->first();
    }
}