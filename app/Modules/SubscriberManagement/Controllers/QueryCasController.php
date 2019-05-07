<?php

namespace App\Modules\SubscriberManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubscriberManagement\Commands\FingerPrintCommand;
use App\Modules\SubscriberManagement\Helpers\QueryHelper;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\SocketHelper;
use Illuminate\Http\Request;
use App\Http\Requests;
use Datatables;


class QueryCasController extends Controller {
	public function getQuery(){
		return view('SubscriberManagement::query.query_results');
	}

	public function postEntitlements(Request $request){
		$params = array(
			'session_id' => '00000000', 
			'length' => '0000001d', 
			'reserve1' => '80', 
			'reserve2' => 'ff', 
			'reserve3' => '00', 
			'reserve4' => '00', 
			'command_type' => '08', 
			'reserve5' => '000000', 
			'reserve6' => '01', 
			'data_len' => '10000000', 
			'data_body' => '6fd66b0300000000',
			'reserve7' => 'ffffffff', 
			'client_ip' => '0ABEB48C', 
		);
		$command_string = '';
		foreach ($params as $param) {
			$command_string = $command_string . $param;
		}
		
		$q = new QueryHelper;
		$q->queryCas($command_string);
		dd($request->all());
	}

	public function getParse(){
		$result = '00000000000001db0000000080ff00000800000001ca010000190000005c2cf5576fd66b0302000c5567580c556758e32cf5576fd66b0302008c3390588c33905824aafc576fd66b030100d68d6858d68d685828aafc576fd66b030200d68d6858d68d68589d4f13586fd66b030200f8d2f358f8d2f3584d2627586fd66b030200d7d2f358d7d2f358e7752c586fd66b030100d7d2f358d7d2f358f2a02c586fd66b030200f92f9058f92f9058cca72c586fd66b03020060183e5860183e58e8cf2d586fd66b030200f8d93e58f8d93e58eecf2d586fd66b03020080d93e5880d93e5825d02d586fd66b0302005c483f585c483f5899d42d586fd66b030200e04b3f58e04b3f587c4331586fd66b0302001cc53e581cc53e583d4431586fd66b030200f4184058f4184058e6a432586fd66b03020028c83e5828c83e588ea932586fd66b0302009cdb3e589cdb3e58b3af32586fd66b030200f0e13e58f0e13e5896b132586fd66b0302002ce23e582ce23e586e6b34586fd66b0302000ce43e580ce43e58307c34586fd66b030100fc5c3f58fc5c3f584e7c34586fd66b030200fc5c3f58fc5c3f58717c34586fd66b030200385d3f58385d3f580fc53a586fd66b030200f2182658ae47695882c53a586fd66b030200ae487058ae48705825000010';
		
		echo "Session_id =>" . substr($result, 0, 8) . "<br>";
		echo "length =>" . substr($result, 8, 8) . "<br>";
		echo "unknown =>" . substr($result, 16, 8) . "<br>";
		echo "reserve =>" . substr($result, 24, 2) . "<br>";
		echo "reserve =>" . substr($result, 26, 2) . "<br>";
		echo "reserve =>" . substr($result, 28, 2) . "<br>";
		echo "reserve =>" . substr($result, 30, 2) . "<br>";
		echo "command_type =>" . substr($result, 32, 2) . "<br>";
		echo "reserve =>" . substr($result, 34, 6) . "<br>";
		echo "reserve =>" . substr($result, 40, 2) . "<br>";
		echo "data len =>" . substr($result, 42, 8) . "<br>";
		echo "record count =>" . substr($result, 50, 8) . "<br>";

		echo "operating time =>" . substr($result, 58, 8) . "<br>";
	}

	public function getFingerprint(ConfigureCommandHeader $configure_command_header, ConfigureCommandBody $configure_command_body){
        $finger = new FingerPrintCommand;
        $command = $finger->getCommand(1, $configure_command_header, $configure_command_body);

        $socket = new SocketHelper("query");
        $response = $socket->sendCommandToCas($command, 5);
        dd($response);
    }
}
