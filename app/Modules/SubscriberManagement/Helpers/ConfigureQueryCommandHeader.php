<?php
namespace App\Modules\SubscriberManagement\Helpers;


class ConfigureQueryCommandHeader
{
    private $command_header;
    //keep track of the length without session_id
    //total data length in BYTES (without session_id and data_length itself)
    const TOTAL_DATA_LENGTH = 21;

    public function getCommandHeader(){
        return $this->command_header;
    }

    public function getSessionId($value){
        $_GET['session_id'] = sprintf('%08x', $value);
        $this->command_header = $this->command_header . sprintf('%08x', $value);
        return $this;
    }

    /**
     * @param $value: command body to find length
     */
    public function getLength($value){
        $total_length = (strlen($value) / 2) + self::TOTAL_DATA_LENGTH;
        $this->command_header = $this->command_header . sprintf('%08x', $total_length);
        $_GET['length'] = sprintf('%08x', $total_length);
        return $this;
    }

    public function getReserveOneByte($value){
        $this->command_header = $this->command_header . sprintf('%02x', $value);
        $_GET[str_random(2) . 'h_reserve_1' ] = sprintf('%02x', $value);
        return $this;
    }

    public function getReserveThreeBytes($value){
        $_GET['h_reserve_3'] = sprintf('%06x', $value);
        $this->command_header = $this->command_header . sprintf('%06x', $value);
        return $this;
    }

    public function getReserveFourBytes($value){
        $_GET['h_reserve_4'] = sprintf('%08x', $value);
        $this->command_header = $this->command_header . sprintf('%08x', $value);
        return $this;
    }

    public function getCommandType($value){
        $_GET['command_type'] = sprintf('%02x', $value);
        $this->command_header = $this->command_header . sprintf('%02x', $value);
        return $this;
    }

    public function getCommandBodyLength($value){
        $this->command_header = $this->command_header . sprintf('%08x', strlen($value) / 2);
        $_GET['command_body_length'] = sprintf('%08x', strlen($value) / 2);
        return $this;
    }

//    public function getClientIp($value){
//        $ip_split = explode('.', $value);
//        $hex_ip = sprintf('%02x%02x%02x%02x', $ip_split[0], $ip_split[1], $ip_split[2], $ip_split[3]);
//        $this->command_header = $this->command_header . $hex_ip;
//        $_GET['client ip'] = sprintf('%08x', $hex_ip);
//        return $this;
//    }

    public function getClientIp($value = '10.190.180.140'){
        $ip_split = explode('.', $value);
        $hex_ip = sprintf('%02x%02x%02x%02x', $ip_split[0], $ip_split[1], $ip_split[2], $ip_split[3]);
        $this->command_header = $this->command_header . $hex_ip;
        $_GET['ip'] = $hex_ip;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function addDataBody($value){
        $this->command_header = $this->command_header . $value;
        return $this;
    }
}