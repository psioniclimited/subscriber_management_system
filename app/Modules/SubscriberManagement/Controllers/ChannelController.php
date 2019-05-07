<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use App\Modules\SubscriberManagement\Models\Channel;

class ChannelController extends Controller {


    /**
     * [allChannelsList -loads all_channels view]
     * @return [view] [description]
     */
    public function allChannels() {
        return view('SubscriberManagement::channel.all_channels');
    }

    public function getChannels(){
        $channels = Channel::all();
        return Datatables::of($channels)
        ->make(true);
    }

    /**
     * [addChannel -loads add_channel view]
     */
    public function addChannel(){
    	return view('SubscriberManagement::channel.add_channel');
    }

    /**
     * [addChannelProcess -add channel to database]
     * @param Request $request [description]
     */
    public function addChannelProcess(Request $request){
    	Channel::create($request->all());
        return redirect('allchannels');
    }

}
