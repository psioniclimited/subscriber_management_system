<?php

namespace App\Modules\SubscriberManagement\Helpers;


class ConfigureCommandHeader
{
    private $command_header;
    public function getCommandHeader(){
        return $this->command_header;
    }

    public function getSessionId($value){
        $this->command_header = $this->command_header . sprintf('%04x', $value);
        return $this;
    }

    public function getCasVersion($value = 2){
        $this->command_header = $this->command_header . sprintf('%02x', $value);
        return $this;
    }

    public function getCommandType($value){
        $this->command_header = $this->command_header . sprintf('%02x', $value);
        return $this;
    }

    public function getCommandBodyLength($value){
        $this->command_header = $this->command_header . sprintf('%04x', strlen($value) / 2);
        return $this;
    }

}