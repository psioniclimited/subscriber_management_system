<?php

namespace App\Modules\SubscriberManagement\Helpers;
use App\Modules\SubscriberManagement\Helpers\FormatEndian;

class ConfigureFingerPrintBody
{
    protected $fingerPrintBody;


    public function getFingerPrintBody(){
        return $this->fingerPrintBody;
    }

    public function getServiceCount($value = 1){
        $_GET['service_count'] = FormatEndian::getBigEndian($value, 8);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 8);
        return $this;
    }

    public function getNetworkId($value = 2){
        $_GET['network_id'] = FormatEndian::getBigEndian($value, 4);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 4);
        return $this;
    }

    public function getTsId($value = 1){
        $_GET['ts_id'] = FormatEndian::getBigEndian($value, 4);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 4);
        return $this;
    }

    public function getServiceId($value = 1){
        $_GET['service_id'] = FormatEndian::getBigEndian($value, 4);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 4);
        return $this;
    }

    public function getReserve($value = 0){
        $_GET['reserve_1'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getFingerPrintType($value){
        $_GET['finger_type'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getShowType($value){
        $_GET['show_type'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getEncryptOrNot($value){
        $_GET['encrypt_or_not'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getLocationFromTop($value){
        $_GET['location_from_top'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getLocationFromLeft($value){
        $_GET['location_from_left'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getSize($value){
        $_GET['size'] = FormatEndian::getBigEndian($value, 2);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 2);
        return $this;
    }

    public function getDurationTime($value){
        $_GET['duration_time'] = FormatEndian::getBigEndian($value, 4);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 4);
        return $this;
    }

    public function getIntervalTime($value){
        $_GET['interval_time'] = FormatEndian::getBigEndian($value, 4);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 4);
        return $this;
    }

    public function getBackColor($value){
        $_GET['back_color'] = FormatEndian::getBigEndian($value, 8);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 8);
        return $this;
    }

    public function getFontColor($value){
        $_GET['font_color'] = FormatEndian::getBigEndian($value, 8);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 8);
        return $this;
    }

    public function getBeginTime($value){
        $_GET['begin_time'] = FormatEndian::getBigEndian($value, 8);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 8);
        return $this;
    }

    public function getEndTime($value){
        $_GET['end_time'] = FormatEndian::getBigEndian($value, 8);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 8);
        return $this;
    }

    public function getReserveFourBytes($value = 0){
        $_GET[str_random(2) . 'reserve_4'] = FormatEndian::getBigEndian($value, 8);
        $this->fingerPrintBody = $this->fingerPrintBody . FormatEndian::getBigEndian($value, 8);
        return $this;
    }

    public function getCardStatus($value = 1){

    }

    public function getClientIp($value = '10.190.180.140'){
        $ip_split = explode('.', $value);
        $hex_ip = sprintf('%02x%02x%02x%02x', $ip_split[0], $ip_split[1], $ip_split[2], $ip_split[3]);
        $this->fingerPrintBody = $this->fingerPrintBody . $hex_ip;
        $_GET['ip'] = $hex_ip;
        return $this;
    }
}