<?php
namespace App\Modules\SubscriberManagement\Helpers;
use Config;
/**
 * This class process and sends command to CAS for entitlement and unentitlement and OSD message 
 */
class QueryHelper{
	/**
     * Send network command to CAS
     * @return string success/failure
     */
	public function queryCas($command_string){

		if(!($sock = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"))))
		{
			$errorcode = socket_last_error();
			$errormsg = socket_strerror($errorcode);

			die("Couldn't create socket: [$errorcode] $errormsg \n");
		}

		echo "Socket created \n";

        //Connect socket to remote server
		// if(!socket_connect($sock , '10.190.180.150' , 8000))
		if(!socket_connect($sock , config('cas.cas_ip'), 65408))
		{
			$errorcode = socket_last_error();
			$errormsg = socket_strerror($errorcode);

			die("Could not connect: [$errorcode] $errormsg \n");
		}

		echo "Connection established \n";

		$message = hex2bin($command_string);

        //Send the message to the server
		if( ! socket_send ( $sock , $message , strlen($message) , 0))
		{
			$errorcode = socket_last_error();
			$errormsg = socket_strerror($errorcode);

			die("Could not send data: [$errorcode] $errormsg \n");
		}

		echo "Message send successfully \n";

        //Now receive reply from server
		if(socket_recv ( $sock , $buf , 1024 , MSG_WAITALL ) === FALSE)
		{
			$errorcode = socket_last_error();
			$errormsg = socket_strerror($errorcode);

			die("Could not receive data: [$errorcode] $errormsg \n");
		}
		$output = bin2hex($buf);
    	dd($output);
    	//Parse output to determine successfull entitlement
	}

     /**
     * Construct the query command
     * @return string entitlement command
     */
     public static function processQueryCommand($params){

     }

     public function parseQueryResult(){
      
     }
 }