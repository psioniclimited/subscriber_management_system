<?php

namespace App\Modules\CableManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\CableManagement\Models\CustomerDetails;
use Datatables;
use Mapper;


class MapController extends Controller {

   
    public function getMapReport(){
        // $custLat = [23.901721, 23.902749, 23.902373];
        // $custLong = [90.391389, 90.391128, 90.388338];
        
        return view('CableManagement::map.map_report_new');
    }

    public function getMapData(Request $request){
        $billCollections = CustomerDetails::select('lat','lon')->get();
        // dd($billCollections->toArray());
        return response()->json($billCollections);  
        
    }

}
