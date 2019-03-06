<?php
namespace Nonda\Util;

class MileageLog
{
    public $when, $why, $where, $distance, $value, $parking, $tolls;

    function __get($name) {
        if ($name == 'total') {
            return $this->value + $this->parking + $this->tolls;
        }
        return null;
    }

}
