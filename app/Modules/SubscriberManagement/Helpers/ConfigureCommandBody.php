<?php

namespace App\Modules\SubscriberManagement\Helpers;


class ConfigureCommandBody
{
    private $command_body;
    private $message_body_and_coverage_rate;

    public function getMessageCommandBody(){
        $this->command_body = $this->command_body . $this->getLength($this->message_body_and_coverage_rate) . $this->message_body_and_coverage_rate;
        return $this->command_body;
    }

    public function getCommandBody(){
//        $this->command_body = $this->command_body;
        return $this->command_body;
    }

    public function getCardId($value){
        $this->command_body = $this->command_body . sprintf('%08x', $value);
        return $this;
    }

    public function getProductAmount($value = 1){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getSendOrNotSend($value = 1){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getTapping($value = 0){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getProductId($value){
        $this->command_body = $this->command_body . sprintf('%04x', $value);
        return $this;
    }

    public function getStartTime($value){
        $this->command_body = $this->command_body . sprintf('%08x', $value);
        return $this;
    }

    public function getEndTime($value){
        $this->command_body = $this->command_body . sprintf('%08x', $value);
        return $this;
    }

    public function getDescriptionLength($value = 0){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getShowTimeLength($value){
        $this->command_body = $this->command_body . sprintf('%04x', $value);
        return $this;
    }

    public function getShowTimes($value){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getShowType($value){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getMessageBody($value){
        $hex_string = unpack('H*', $value);
        $converted_hex_string = array_shift($hex_string);
        $this->message_body_and_coverage_rate = $this->message_body_and_coverage_rate . $converted_hex_string;
        return $this;
    }

    public function getCoverageRate($value = 60){
        $this->message_body_and_coverage_rate = $this->message_body_and_coverage_rate . sprintf('%02x', $value);
        return $this;
    }

    public function getLength($value){
        return sprintf('%02x', strlen($value) / 2);
    }

    public function getExpiredTime($value){
        $this->command_body = $this->command_body . sprintf('%08x', $value);
        return $this;
    }

    public function getCardStatus($value = 1){
        $this->command_body = $this->command_body . sprintf('%02x', $value);
        return $this;
    }

    public function getDuration($value){
        $this->command_body = $this->command_body . sprintf('%04x', $value);
        return $this;
    }

    public function getEmailSenderNameLength($value){
        $hex_string = unpack('H*', $value);
        $converted_hex_string = array_shift($hex_string);
        $this->command_body = $this->command_body . sprintf('%02x', strlen($converted_hex_string) / 2);
        return $this;
    }

    public function getEmailSenderName($value){
        $hex_string = unpack('H*', $value);
        $converted_hex_string = array_shift($hex_string);
        $this->command_body = $this->command_body . $converted_hex_string;
        return $this;
    }

    public function getEmailContentLength($value){
        $hex_string = unpack('H*', $value);
        $converted_hex_string = array_shift($hex_string);
        $this->command_body = $this->command_body . sprintf('%04x', strlen($converted_hex_string) / 2);
        return $this;
    }

    public function getEmailContent($value){
        $hex_string = unpack('H*', $value);
        $converted_hex_string = array_shift($hex_string);
        $this->command_body = $this->command_body . $converted_hex_string;
        return $this;
    }
}