<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'authmob',
        'create_customer_process',
        'create_territory_process',	
        'create_sector_process',
        'create_road_process',
        'create_house_process',
        'chart_data',
        'customers/*/delete',
        'users/*/delete',
        'billcollectors/*/delete',
        'sync/*',
        'blacklist_card_process',
        'unblacklist_card_process',
        'pair_customer_process',
        'add_set_top_box_process',
        'add_set_top_box_model_process',
        'delete_territory_process',
        'delete_sector_process',
        'delete_road_process',
        'delete_house_process',
        'fingerprint_card_processs',
        'enable_login_process',
        'disable_login_process',
        'activate_customer_process',
        'deactivate_customer_process',
        'fingerprint_card_process',
        'fingerprint_customer_process',
        'send_email_process'

        
    ];
}
