<?php

namespace App\Modules\SubscriberManagement\Helpers;


class FormatEndian
{
    public static function getBigEndian($value, $length){
        $value = intval($value, 16);
        if($length == 8)
            $value = self::formatFourBytes($value);
        elseif ($length == 4)
            $value = self::formatTwoBytes($value);
        elseif ($length == 2)
            $value = self::formatOneByte($value);

        return sprintf("%'0{$length}x", $value[1]);
    }

    /**
     * @param $value
     * @return array|string
     */
    private static function formatFourBytes($value)
    {
        $value = pack('L', $value);
        $value = unpack('N', $value);
        return $value;
    }

    /**
     * @param $value
     * @return array|string
     */
    private static function formatTwoBytes($value)
    {
        $value = pack('S', $value);
        $value = unpack('n', $value);
        return $value;
    }

    /**
     * @param $value
     * @return array|string
     */
    private static function formatOneByte($value)
    {
        $value = pack('C', $value);
        $value = unpack('c', $value);
        return $value;
    }
}