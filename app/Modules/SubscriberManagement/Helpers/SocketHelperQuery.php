<?php

namespace App\Modules\SubscriberManagement\Helpers;


class SocketHelperQuery extends SocketHelper
{
    public function sendCommandToCas($message, $response_length = 10){
        $buffer = $this->connect()->send($message)->receive($response_length);
        return bin2hex($buffer);
    }

    /**
     * Connect to cas using socket
     * @return $this
     */
    public function connect(){
        if(!socket_connect($this->sock , config('cas.cas_ip'), config('cas.cas_query_port')))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            die("Could not connect: [$errorcode] $errormsg \n");
        }
        return $this;
    }
}