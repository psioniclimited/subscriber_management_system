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



Route::group(['middleware' => ['api']], function () {
    Route::post('authmob', 'App\Modules\CableManagement\Controllers\MobileAuthenticationController@authenticate');
    Route::controller('sync', 'App\Modules\CableManagement\Controllers\MobileDataController');
});

Route::group(['middleware' => ['web']], function () {
    // View Customers
    Route::controller('auto', 'App\Modules\CableManagement\Controllers\AutoCompleteController');
    Route::get('allcustomers', 'App\Modules\CableManagement\Controllers\CustomerController@allCustomers')->middleware(['permission:subscribers.read']);
    Route::get('getcustomers', 'App\Modules\CableManagement\Controllers\CustomerController@getCustomers')->middleware(['permission:subscribers.read']);
    // Create Customer
    Route::get('create_customer', 'App\Modules\CableManagement\Controllers\CustomerController@createCustomer')->middleware(['permission:subscribers.create']);
    Route::post('create_customer_process', 'App\Modules\CableManagement\Controllers\CustomerController@createCustomerProcess')->middleware(['permission:subscribers.create']);
	// Edit Customer
	Route::get('customers/{id}/edit', 'App\Modules\CableManagement\Controllers\CustomerController@editCustomers')->middleware(['permission:subscribers.update']);
    Route::put('edit_customers_process/{id}', 'App\Modules\CableManagement\Controllers\CustomerController@editCustomersProcess')->middleware(['permission:subscribers.update']);
    
    // Delete Customer
    Route::post('customers/{id}/delete', ['as'=> 'customers_delete', 
    	'uses'=> 'App\Modules\CableManagement\Controllers\CustomerController@deleteCustomer'])->middleware(['permission:subscribers.delete']);

    
	
	// Create Territory
    Route::post('create_territory_process', 'App\Modules\CableManagement\Controllers\CustomerController@createTerritoryProcess');
    // Delete Territory
	Route::post('delete_territory_process', 'App\Modules\CableManagement\Controllers\CustomerController@deleteTerritoryProcess');
   	// Create Sector 
	Route::post('create_sector_process', 'App\Modules\CableManagement\Controllers\CustomerController@createSectorProcess');
    // Delete Sector
    Route::post('delete_sector_process', 'App\Modules\CableManagement\Controllers\CustomerController@deleteSectorProcess'); 
	// Create Road 
	Route::post('create_road_process', 'App\Modules\CableManagement\Controllers\CustomerController@createRoadProcess');
    // Delete Road
    Route::post('delete_road_process', 'App\Modules\CableManagement\Controllers\CustomerController@deleteRoadProcess'); 
	// Create House 
	Route::post('create_house_process', 'App\Modules\CableManagement\Controllers\CustomerController@createHouseProcess'); 
	// Delete House
    Route::post('delete_house_process', 'App\Modules\CableManagement\Controllers\CustomerController@deleteHouseProcess'); 

	// Chart view 
	Route::get('chart_data_view', 'App\Modules\CableManagement\Controllers\CustomerController@chart_data_view');   
	Route::post('chart_data', 'App\Modules\CableManagement\Controllers\CustomerController@chart_data');

	// View Bill Collectors
	Route::get('allbillcollectors', 'App\Modules\CableManagement\Controllers\BillCollectorController@allBillCollectors')->middleware(['permission:users.read']);
    Route::get('getbillcollectors', 'App\Modules\CableManagement\Controllers\BillCollectorController@getBillCollectors')->middleware(['permission:users.read']);
    // Create Bill Collector
    Route::get('create_bill_collector', 'App\Modules\CableManagement\Controllers\BillCollectorController@createBillCollector')->middleware(['permission:users.create']);
	Route::post('create_bill_collector_process', 'App\Modules\CableManagement\Controllers\BillCollectorController@createBillCollectorProcess')->middleware(['permission:users.create']);
	// Delete Bill Collectors
    Route::post('billcollectors/{id}/delete', ['as'=> 'billcollectors_delete', 
    	'uses'=> 'App\Modules\CableManagement\Controllers\BillCollectorController@deleteBillCollectors'])->middleware(['permission:users.delete']);


	// View Collection List
	Route::get('allcollectionlist', 'App\Modules\CableManagement\Controllers\CollectionController@allCollectionList')->middleware(['permission:read.collectionlist']);
    Route::get('getcollectionlist', 'App\Modules\CableManagement\Controllers\CollectionController@getCollectionList')->middleware(['permission:read.collectionlist']);
    Route::get('getfilteredcollectiondata', 'App\Modules\CableManagement\Controllers\CollectionController@getFilteredCollectionData')->middleware(['permission:read.collectionlist']);

    // View Due List
    Route::get('allduelist', 'App\Modules\CableManagement\Controllers\CollectionController@allDueList')->middleware(['permission:read.collectionlist']);
    Route::get('getduelist', 'App\Modules\CableManagement\Controllers\CollectionController@getDueList')->middleware(['permission:read.collectionlist']);
    Route::get('getfilteredduedata', 'App\Modules\CableManagement\Controllers\CollectionController@getFilteredDueData')->middleware(['permission:read.collectionlist']);


    Route::get('map', function(){
    	Mapper::map(53.381128999999990000, -1.470085000000040000);
    	return view('CableManagement::map');

    });

    // Route::post('map_view_process', 'App\Modules\CableManagement\Controllers\MapController@mapView');
    // Route::get('mapreport', 'App\Modules\CableManagement\Controllers\MapController@getMapReport');
    // Route::get('mapdata', 'App\Modules\CableManagement\Controllers\MapController@getMapData');
    
    Route::get('mapreport','App\Modules\CableManagement\Controllers\MapController@getMapReport');
    Route::get('mapdata', 'App\Modules\CableManagement\Controllers\MapController@getMapData');

    Route::get('smstest', function(){
        $xml = '<?xml version=\"1.0\";encoding=\"UTF-8\"?>'.
        '<request>'.
        '<authUser>palace</authUser>'.
        '<authAccess>p@lace123</authAccess>'.
        '<destination>8801705151914</destination>'.
        '<text>Hi There</text>'.
        '<requestId>450</requestId>'.
        '</request>';
        $url = "http://bmp.issl.com.bd/api/curl/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        //Debug to see the Output (Uncomment the lines)
        
        // echo ‘<pre>’;
        print_r($output);
        // echo ‘</pre>’;
        echo "success";
        
        
    });

    //Temporary route for populating data
    Route::get('popdata', 'App\Modules\CableManagement\Controllers\PopulateDataController@populateFromExcel');
    Route::get('popcarddata', 'App\Modules\CableManagement\Controllers\PopulateDataController@populateCardData');

    // Activate customer process 
    Route::post('activate_customer_process', 'App\Modules\CableManagement\Controllers\CustomerController@activateCustomerProcess');  
    // Deactivate customer process 
    Route::post('deactivate_customer_process', 'App\Modules\CableManagement\Controllers\CustomerController@deactivateCustomerProcess');

    // Test customer list for card id search
    Route::get('testcustomerlist', 'App\Modules\CableManagement\Controllers\CustomerController@viewCustomerList');

});



