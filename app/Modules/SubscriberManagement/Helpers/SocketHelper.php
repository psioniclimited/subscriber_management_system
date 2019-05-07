<?php
namespace App\Modules\SubscriberManagement\Helpers;
use Config;
use App\Modules\SubscriberManagement\Helpers\ParseResponseFromCas;
/**
 * This class performs socket operations on the cas
 */
class SocketHelper{
  protected $sock;
  protected $port;

  function __construct($type = "command") {
      if($type == "command"){
          $this->port = config('cas.cas_port');
      }
      elseif ($type == "query"){
          $this->port = config('cas_query_port');
      }
  }

    /**
     * Connect send and receive command to cas and return the output
     * @param $message
     * @param $response_length
     */
  public function sendCommandToCas($message, $response_length = 10){
      $buffer = $this->create()->connect()->send($message)->receive($response_length);
      $response_code = (new ParseResponseFromCas)->parseResponseFromCas(bin2hex($buffer));
      return $response_code;
//      return bin2hex($buffer);
  }

  public function sendHeartBeat($message = '0101021e00044ad20001', $response_length = 10){
      $buffer = $this->connect()->send($message)->receive($response_length);
      return bin2hex($buffer);
  }

  public function create(){
      if(!($this->sock = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"))))
      {
          $error_message = socket_last_error();
          $error_message = socket_strerror($error_message);

          die("Couldn't create socket: [$error_message] $error_message \n");
      }

      return $this;
  }

    /**
     * Connect to cas using socket
     * @return $this
     */
  public function connect(){
    if(!socket_connect($this->sock , config('cas.cas_ip'), config('cas.cas_port')))
    {
      $errorcode = socket_last_error();
      $errormsg = socket_strerror($errorcode);

      die("Could not connect: [$errorcode] $errormsg \n");
    }
    return $this;
  }

    /**
     * Send the $command_string to connected socket
     * @param $command_string
     * @return $this
     */
  public function send($command_string){
    if( ! socket_send ( $this->sock , $command_string , strlen($command_string) , 0)){
      $errorcode = socket_last_error();
      $errormsg = socket_strerror($errorcode);

      die("Could not send data: [$errorcode] $errormsg \n");
    }
    return $this;
  }

    /**
     * Recieve response from cas
     * @param int $response_length
     * @return $buffer hex output from CAS
     */
  public function receive($response_length = 10){
    if(socket_recv ( $this->sock , $buffer , $response_length , MSG_WAITALL ) === FALSE){
      $errorcode = socket_last_error();
      $errormsg = socket_strerror($errorcode);

      die("Could not receive data: [$errorcode] $errormsg \n");
    }
    return $buffer;
  }
}