<?php
// kaifei@nonda.us

// 转换器合集，目前集成了：
// 1. 各种距离单位的转换 - distance
// 2. php内部各种time格式的转换 - time

namespace Nonda\Util;

use MongoDB\BSON\UTCDateTime;

class Converter {
    /**
     * @param double $dist
     * @param string $fromUnit cm, m, km, mile, mi(mi == mile)中的一个, 以后还有别的也可以加。
     * @param string $toUnit 同上
     * @return double 不round了，就是实数，要几位自己切。
     */
    public static function distance($dist, $fromUnit, $toUnit) {
        $fromUnit = mb_strtolower($fromUnit);
        $toUnit = mb_strtolower($toUnit);
        
        if ($fromUnit == $toUnit) return $dist;
        
        // 为了不写n^2个converter，还是找一个中间单位（暂时设计为米），写2n个好了。
        // 从$fromUnit换成米
        switch ($fromUnit) {
            case 'cm':
                $dist /= 100;
                break;
            case 'km':
                $dist *= 1000;
                break;
            case 'mile':
            case 'mi':
                $dist *= 1609.344;
                break;
            default:
        }
        
        // 从米换成$toUnit
        switch ($toUnit) {
            case 'cm':
                $dist *= 100;
                break;
            case 'km':
                $dist /= 1000;
                break;
            case 'mile':
            case 'mi':
                $dist /= 1609.344;
                break;
            default;
        }
        
        return $dist;
    }
    
    /**
     * @param mixed $time 暂时支持10位timestamp、13位timestamp、DateTime、UTCDateTime
     * @param string $fromType 暂时可选4个常量： ts10, ts13, dt, utcdt
     * @param string $toType 同上
     * @param \DateTimeZone | null $timezone 如果toType是dt，那需要额外指定$timezone，默认timezone是Etc/GMT
     * @return mixed
     */
    public static function time($time, $fromType, $toType, $timezone = null) {
        $fromType = mb_strtolower($fromType);
        $toType = mb_strtolower($toType);
        
        if ($fromType == $toType) return $time;
        
        // 先全转成ts13
        switch ($fromType) {
            case 'ts10':
                $time *= 1000;
                break;
            case 'dt':
            case 'utcdt':
                $time = $time->getTimestamp() * 1000;
                break;
            default:
        }
        
        // 再转成$toType
        switch ($toType) {
            case 'ts10':
                $time = intval($time / 1000);
                break;
            case 'dt':
                $backupTimezone = date_default_timezone_get();
                date_default_timezone_set($timezone->getName());
                $time = new \DateTime(date('Y-m-d H:i:s', $time / 1000));
                date_default_timezone_set($backupTimezone);
                break;
            case 'utcdt':
                $time = new UTCDateTime($time);
                break;
        }
        
        return $time;
    }
    
    /**
     * 切换一个DateTime的时区
     * @param \DateTime $time
     * @param \DateTimeZone $timezone toTimezone
     * @return \DateTime
     */
    public static function timezone(\DateTime $time, \DateTimeZone $timezone) {
        if ($timezone->getName() == $time->getTimezone()->getName()) return $time;
        
        $timeOffset = $timezone->getOffset($time);
        $time->modify($timeOffset . 'seconds');
        $time->setTimezone($timezone);
        return $time;
    }
}