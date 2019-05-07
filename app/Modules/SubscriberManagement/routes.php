<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

	// View Cards List
	Route::get('allcards', 'App\Modules\SubscriberManagement\Controllers\CardController@allCards')->middleware(['permission:cards.read']);
	Route::get('getcards', 'App\Modules\SubscriberManagement\Controllers\CardController@getCards')->middleware(['permission:cards.read']);
	// Create Card
	Route::get('add_card', 'App\Modules\SubscriberManagement\Controllers\CardController@addCard')->middleware(['permission:cards.create']);
	Route::post('add_card_process', 'App\Modules\SubscriberManagement\Controllers\CardController@addCardProcess')->middleware(['permission:cards.create']);
	// Edit Card
	Route::get('cards/{id}/edit', 'App\Modules\SubscriberManagement\Controllers\CardController@editCard')->middleware(['permission:cards.update']);
    Route::put('edit_card_process/{id}', 'App\Modules\SubscriberManagement\Controllers\CardController@editCardProcess')->middleware(['permission:cards.update']);
    Route::put('edit_card_process/{id}', 'App\Modules\SubscriberManagement\Controllers\CardController@editCardProcess')->middleware(['permission:cards.update']);

	// View Channels List
	Route::get('allchannels', 'App\Modules\SubscriberManagement\Controllers\ChannelController@allChannels')->middleware(['permission:channels.read']);
	Route::get('getchannels', 'App\Modules\SubscriberManagement\Controllers\ChannelController@getChannels')->middleware(['permission:channels.read']);
	// Create Channel
	Route::get('add_channel', 'App\Modules\SubscriberManagement\Controllers\ChannelController@addChannel')->middleware(['permission:channels.create']);
	Route::post('add_channel_process', 'App\Modules\SubscriberManagement\Controllers\ChannelController@addChannelProcess')->middleware(['permission:channels.create']);

	// View Products List
	Route::get('allproducts', 'App\Modules\SubscriberManagement\Controllers\ProductController@allProducts')->middleware(['permission:products.read']);
	Route::get('getproducts', 'App\Modules\SubscriberManagement\Controllers\ProductController@getProducts')->middleware(['permission:products.read']);
	// Create Product
	Route::get('add_product', 'App\Modules\SubscriberManagement\Controllers\ProductController@addProduct')->middleware(['permission:products.create']);
	Route::post('add_product_process', 'App\Modules\SubscriberManagement\Controllers\ProductController@addProductProcess')->middleware(['permission:products.create']);
	// Edit Product
	Route::get('products/{id}/edit', 'App\Modules\SubscriberManagement\Controllers\ProductController@editProduct')->middleware(['permission:products.update']);
	Route::put('edit_product_process/{id}', 'App\Modules\SubscriberManagement\Controllers\ProductController@editProductProcess')->middleware(['permission:products.update']);
	
	// Send OSD
	Route::get('sendosd', 'App\Modules\SubscriberManagement\Controllers\MessageController@sendOSD')->middleware(['permission:messages.send']);
	// Send OSD Process
	Route::post('send_osd_process', 'App\Modules\SubscriberManagement\Controllers\MessageController@sendOsdProcess')->middleware(['permission:messages.send']);
	// Send Email
	Route::get('sendemail', 'App\Modules\SubscriberManagement\Controllers\MessageController@sendEmail')->middleware(['permission:messages.send']);
	Route::post('send_email_process', 'App\Modules\SubscriberManagement\Controllers\MessageController@sendEmailProcess')->middleware(['permission:messages.send']);
	// View OSD Messages
	Route::get('allosdmessages', 'App\Modules\SubscriberManagement\Controllers\MessageController@allOsdMessages')->middleware(['permission:messages.read']);
	Route::get('getosdmessages', 'App\Modules\SubscriberManagement\Controllers\MessageController@getOsdMessages')->middleware(['permission:messages.read']);

	/**
	 * To be removed
	 */
	
	// View Card History
	Route::get('cardhistory', 'App\Modules\SubscriberManagement\Controllers\HistoryController@cardHistory');
	// View Message History
	Route::get('messagehistory', 'App\Modules\SubscriberManagement\Controllers\HistoryController@messageHistory');
	
	/**
	 * To be removed
	 */
    
    // Entitle
    Route::get('customers/{id}/entitle', 'App\Modules\SubscriberManagement\Controllers\EntitleController@entitleCard')->middleware(['permission:entitle.create']);
    Route::post('entitle_process', 'App\Modules\SubscriberManagement\Controllers\EntitleController@entitleProcess')->middleware(['permission:entitle.create']);

    Route::post('customer_note_process', 'App\Modules\SubscriberManagement\Controllers\EntitleController@customerNoteProcess');
    Route::get('getcustomernotes', 'App\Modules\SubscriberManagement\Controllers\EntitleController@getCustomerNotes');

    // Entitle History
    Route::get('getentitlementhistory', 'App\Modules\SubscriberManagement\Controllers\EntitleController@getEntitlementHistory')->middleware(['permission:entitle.read']);
    Route::get('customers/{id}/unentitle', 'App\Modules\SubscriberManagement\Controllers\EntitleController@unEntitleCard')->middleware(['permission:entitle.read']);

    // Entitle Card Range
	Route::get('entitle_card_range', 'App\Modules\SubscriberManagement\Controllers\EntitleCardRangeController@entitleCardRange')->middleware(['permission:range.entitle.create']);
	// Entitle Card Range Process
	Route::post('entitle_card_range_process', 'App\Modules\SubscriberManagement\Controllers\EntitleCardRangeController@entitleCardRangeProcess')->middleware(['permission:range.entitle.create']);

	// Unentitle Card Range
	Route::get('unentitle_card_range', 'App\Modules\SubscriberManagement\Controllers\UnentitleCardRangeController@unentitleCardRange')->middleware(['permission:range.unentitle.create']);
	// Unentitle Card Range Process
	Route::post('unentitle_card_range_process', 'App\Modules\SubscriberManagement\Controllers\UnentitleCardRangeController@unentitleCardRangeProcess')->middleware(['permission:range.unentitle.create']);

	// Fingerprint Range
	Route::get('fingerprint_range', 'App\Modules\SubscriberManagement\Controllers\FingerprintRangeController@fingerprintRange')->middleware(['permission:range.fingerprint.create']);
	// Fingerprint Range Process
	Route::post('fingerprint_range_process', 'App\Modules\SubscriberManagement\Controllers\FingerprintRangeController@fingerprintRangeProcess')->middleware(['permission:range.fingerprint.create']);

	// Blacklist Range
	Route::get('blacklist_range', 'App\Modules\SubscriberManagement\Controllers\BlacklistRangeController@blacklistRange')->middleware(['permission:range.blacklist.create']);
	// Blacklist Range Process
	Route::post('blacklist_range_process', 'App\Modules\SubscriberManagement\Controllers\BlacklistRangeController@blacklistRangeProcess')->middleware(['permission:range.blacklist.create']);

	// Unblacklist Range
	Route::get('unblacklist_range', 'App\Modules\SubscriberManagement\Controllers\UnblacklistRangeController@unblacklistRange')->middleware(['permission:range.unblacklist.create']);
	// Unblacklist Range Process
	Route::post('unblacklist_range_process', 'App\Modules\SubscriberManagement\Controllers\UnblacklistRangeController@unblacklistRangeProcess')->middleware(['permission:range.unblacklist.create']);

	// Create Card Range
	Route::get('create_card_range', 'App\Modules\SubscriberManagement\Controllers\CreateCardRangeController@createCardRange')->middleware(['permission:range.card.create']);
	// Create Card Range Process
	Route::post('create_card_range_process', 'App\Modules\SubscriberManagement\Controllers\CreateCardRangeController@createCardRangeProcess')->middleware(['permission:range.card.create']);

	// Reassign Card Range for Admin
	Route::get('reassign_card_range', 'App\Modules\SubscriberManagement\Controllers\ReassignCardRangeController@reassignCardRange')->middleware(['permission:range.reassign.card.access']);
	
	Route::post('reassign_card_range_process', 'App\Modules\SubscriberManagement\Controllers\ReassignCardRangeController@reassignCardRangeProcess')->middleware(['permission:range.reassign.card.access']);
	// Reassign Card Range Process for Admin
	Route::post('reassign_card_range_process_for_admin', 'App\Modules\SubscriberManagement\Controllers\ReassignCardRangeController@reassignCardRangeProcessForAdmin')->middleware(['permission:range.reassign.card.access']);
	// Reassign Card Range Process for Distributor
	Route::post('reassign_card_range_process_for_distributor', 'App\Modules\SubscriberManagement\Controllers\ReassignCardRangeController@reassignCardRangeProcessForDistributor')->middleware(['permission:range.reassign.card.access']);

	// Message Card Range
	Route::get('message_card_range', 'App\Modules\SubscriberManagement\Controllers\MessageCardRangeController@messageCardRange')->middleware(['permission:range.message.send']);
	// Message Card Range Process
	Route::post('message_card_range_process', 'App\Modules\SubscriberManagement\Controllers\MessageCardRangeController@messageCardRangeProcess')->middleware(['permission:range.message.send']);



	Route::get('eagerloading', function(){
		// RoleUser::join('users', 'role_user.user_id', '=', 'users.id')
  //               ->join('roles', 'role_user.role_id', '=', 'roles.id')
  //               ->join('territory', 'users.territory_id', '=', 'territory.id')
  //               ->select(['users.id as id', 'users.name', 'users.email', 'roles.display_name', 'territory.name as territory_name']);
  			
	});

	Route::controller('query', 'App\Modules\SubscriberManagement\Controllers\QueryCasController');


	// View Distributors
	Route::get('alldistributors', 'App\Modules\SubscriberManagement\Controllers\DistributorController@allDistributors')->middleware(['permission:distributors.read']);
    Route::get('getdistributors', 'App\Modules\SubscriberManagement\Controllers\DistributorController@getDistributors')->middleware(['permission:distributors.read']);

    // Create Distributor
    Route::get('create_distributor', 'App\Modules\SubscriberManagement\Controllers\DistributorController@createDistributor')->middleware(['permission:distributors.create']);
	Route::post('create_distributor_process', 'App\Modules\SubscriberManagement\Controllers\DistributorController@createDistributorProcess')->middleware(['permission:distributors.create']);

	// Edit Distributor
    Route::get('distributor/{id}/edit', 'App\Modules\SubscriberManagement\Controllers\DistributorController@editDistributor')->middleware(['permission:distributors.update']);
    Route::put('edit_distributor_process/{id}', 'App\Modules\SubscriberManagement\Controllers\DistributorController@editDistributorProcess')->middleware(['permission:distributors.update']);

    // View sub distributors of a distributor
    Route::get('subdistributorsbydistributor/{id}', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@subDistributorByDistributor')->middleware(['permission:subdistributors.read']);
    Route::get('getsubdistributorsbydistributor', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@getSubDistributorByDistributor')->middleware(['permission:subdistributors.read']);

    // Revoke Distributor
    Route::get('revoke_distributor', 'App\Modules\SubscriberManagement\Controllers\DistributorController@revokeDistributor')->middleware(['permission:distributors.revoke']);
	Route::post('revoke_distributor_process', 'App\Modules\SubscriberManagement\Controllers\DistributorController@revokeDistributorProcess')->middleware(['permission:distributors.revoke']);
	
	// View Sub Distributors
	Route::get('allsubdistributors', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@allSubDistributors')->middleware(['permission:subdistributors.read']);
    Route::get('getsubdistributors', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@getSubDistributors')->middleware(['permission:subdistributors.read']);

	// Create Sub Distributor
    Route::get('create_sub_distributor', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@createSubDistributor')->middleware(['permission:subdistributors.read']);
	Route::post('create_sub_distributor_process', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@createSubDistributorProcess')->middleware(['permission:subdistributors.read']);

	// Edit Sub Distributor
    Route::get('sub_distributor/{id}/edit', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@editSubDistributor')->middleware(['permission:subdistributors.update']);
    Route::put('edit_sub_distributor_process/{id}', 'App\Modules\SubscriberManagement\Controllers\SubDistributorController@editSubDistributorProcess')->middleware(['permission:subdistributors.update']);

    /**
     * Check with Saad bhaiya. Might have to remove it.
     */
    
    // Blacklist
    Route::get('black_list_card', 'App\Modules\SubscriberManagement\Controllers\BlackListController@blackListCard');
    Route::get('unblack_list_card', 'App\Modules\SubscriberManagement\Controllers\BlackListController@unBlackListCard');

    /**
     * Check with Saad bhaiya. Might have to remove it.
     */


    // Blacklist process 
	Route::post('blacklist_card_process', 'App\Modules\SubscriberManagement\Controllers\BlackListController@blackListCardProcess')->middleware(['permission:blacklist.create']);
	// Unblacklist process 
	Route::post('unblacklist_card_process', 'App\Modules\SubscriberManagement\Controllers\BlackListController@unBlackListCardProcess')->middleware(['permission:unblacklist.create']);
	// View Blacklisted cards
	Route::get('allblacklistedcards', 'App\Modules\SubscriberManagement\Controllers\BlackListController@allBlacklistedCards')->middleware(['permission:blacklist.read']);
	Route::get('getblacklistedcards', 'App\Modules\SubscriberManagement\Controllers\BlackListController@getblacklistedcards')->middleware(['permission:blacklist.read']); 

    // For select2 data
    Route::controller('select', 'App\Modules\SubscriberManagement\Controllers\SelectDataController');

    // Add Set top box
    Route::get('add_set_top_box', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@addSetTopBox')->middleware(['permission:settopbox.create']);
	Route::post('add_set_top_box_process', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@addSetTopBoxProcess')->middleware(['permission:settopbox.create']);
	// Add Set top box brand
	Route::post('add_set_top_box_brand_process', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@addSetTopBoxBrandProcess')->middleware(['permission:settopbox.create']);
	// Add Set top box model
	Route::post('add_set_top_box_model_process', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@addSetTopBoxModelProcess')->middleware(['permission:settopbox.create']);
	// View Set top box list
	Route::get('allsettopboxes', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@allSetTopBox')->middleware(['permission:settopbox.read']);
	Route::get('getsettopboxes', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@getSetTopBox')->middleware(['permission:settopbox.read']);
	// Edit Set top box
	Route::get('settopboxes/{id}/edit', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@editSetTopBox')->middleware(['permission:settopbox.update']);
    Route::put('edit_set_top_box_process/{id}', 'App\Modules\SubscriberManagement\Controllers\SetTopBoxController@editSetTopBoxProcess')->middleware(['permission:settopbox.update']);

	// Pair process 
	Route::post('pair_customer_process', 'App\Modules\SubscriberManagement\Controllers\PairController@pairCustomerProcess');
	// Fingerprint customer process 
	Route::post('fingerprint_customer_process', 'App\Modules\SubscriberManagement\Controllers\FingerprintController@fingerprintCustomerProcess')->middleware(['permission:fingerprint.create']);
	// Fingerprint card process 
	Route::post('fingerprint_card_process', 'App\Modules\SubscriberManagement\Controllers\FingerprintController@fingerprintCardProcess')->middleware(['permission:fingerprint.create']);
	// Enable login process 
	Route::post('enable_login_process', 'App\Modules\SubscriberManagement\Controllers\LoginController@enableLoginProcess')->middleware(['permission:login.enable']);  
	// Disable login process 
	Route::post('disable_login_process', 'App\Modules\SubscriberManagement\Controllers\LoginController@disableLoginProcess')->middleware(['permission:login.disable']);

	Route::get('test_email', 'App\Modules\SubscriberManagement\Controllers\EntitleController@emailTest');

	Route::get('test_carbon','App\Modules\SubscriberManagement\Controllers\MessageController@testScheduledMessages');

});



