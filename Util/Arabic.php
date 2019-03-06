<?php
namespace Nonda\Util;

class Arabic
{
    protected static $mapping = [
        '٠' => 0,
        '١' => 1,
        '٢' => 2,
        '٣' => 3,
        '٤' => 4,
        '٥' => 5,
        '٦' => 6,
        '٧' => 7,
        '٨' => 8,
        '٩' => 9,
        '۰' => 0,
        '۱' => 1,
        '۲' => 2,
        '۳' => 3,
        '۴' => 4,
        '۵' => 5,
        '۶' => 6,
        '۷' => 7,
        '۸' => 8,
        '۹' => 9,
    ];

    public static function toNumber($string)
    {
        return str_replace(
            array_keys(self::$mapping), array_values(self::$mapping), $string
        );
    }
}
