<?php
namespace Nonda\Util;

/**
 * 火星坐标转换的逻辑封装
 *
 * Class ToMars
 *
 */
class ToMars
{
    const earth = 6378245.0;

    const ee = 0.00669342162296594323;

    /**
     * 包含坐标系数据
     * @return array
     */
    public static function includeRectangles()
    {
        $rectangles = [
            [49.220400, 79.446200, 42.889900, 96.330000],
            [54.141500, 109.687200, 39.374200, 135.000200],
            [42.889900, 73.124600, 29.529700, 124.143255],
            [29.529700, 82.968400, 26.718600, 97.035200],
            [29.529700, 97.025300, 20.414096, 124.367395],
            [20.414096, 107.975793, 17.871542, 111.744104],

            // for ShenZhen
            // https://s3-us-west-2.amazonaws.com/zusapp/notes/HongKongArea.jpg
            [22.544849, 113.837827, 22.439631, 113.943594],
            [22.544849, 113.943594, 22.466884, 113.976433],
            [22.544849, 113.976433, 22.502899, 114.071109],
            [22.544849, 114.071109, 22.515874, 114.092259],
            [22.544849, 114.092259, 22.531749, 114.138527],

            // for Zhuhai
            // https://s3-us-west-2.amazonaws.com/zusapp/notes/MacauArea.png
            [22.213929, 113.529066, 22.192364, 113.582676]
        ];

        return $rectangles;
    }

    /**
     * 排除坐标系数据
     * @return array
     */
    public static function excludeRectangles()
    {
        $rectangles = [
            [25.398623, 119.921265, 21.785006, 122.497559],
            [22.284000, 101.865200, 20.098800, 106.665000],
            [21.542200, 106.452500, 20.487800, 108.051000],
            [55.817500, 109.032300, 50.325700, 119.127000],
            [55.817500, 127.456800, 49.557400, 137.022700],
            [44.892200, 131.266200, 42.569200, 137.022700],

            // for Hongkong
            // https://s3-us-west-2.amazonaws.com/zusapp/notes/HongKongArea.jpg
            [22.544849, 113.837827, 22.152146, 114.447569],
            [22.555437, 114.151323, 22.544849, 114.224665],

            // for Macau
            // https://s3-us-west-2.amazonaws.com/zusapp/notes/MacauArea.png
            [22.213929, 113.542652, 22.217047, 113.550939],
            [22.213929, 113.529066, 22.157412, 113.548531],
            [22.213929, 113.548531, 22.106899, 113.607537],
            [22.151226, 113.542332, 22.157412, 113.548531]
        ];

        return $rectangles;
    }

    /**
     * 判断坐标是否包含在经纬度区域内
     *
     * @param $rect
     * @param $latitude
     * @param $longitude
     * @return bool
     *
     */
    public function isInRectangle($rect, $latitude, $longitude)
    {
        return max($rect[0],$rect[2]) >= $latitude
            && min($rect[0], $rect[2]) <= $latitude
            && max($rect[1], $rect[3]) >= $longitude
            && min($rect[1], $rect[3]) <= $longitude;
    }

    /**
     * 判断是否是中国地域
     *
     * @param $latitude
     * @param $longitude
     * @return bool
     */
    public function inChinaRegion($latitude, $longitude)
    {
        $includes = self::includeRectangles();
        $excludes = self::excludeRectangles();

        foreach ($includes as $include) {
            if ($this->isInRectangle($include, $latitude, $longitude)) {
                foreach ($excludes as $exclude) {
                    if ($this->isInRectangle($exclude, $latitude, $longitude)) {
                        return false;
                    }
                }

                return true;
            }
        }

        return false;
    }

    /**
     * 转换成火星坐标系
     *
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function convertEarthToMars($latitude, $longitude)
    {
        // calculate multi
        $coordinateMulti = $latitude * $longitude;

        // calculate abs
        $coordinateAbs = sqrt(abs($latitude));

        // calculate Pi
        $latitudePi = $latitude * M_PI;
        $longitudePi = $longitude * M_PI;

        $finalCoordinate = 20 * (sin(6.0 * $latitudePi) + sin(2.0 * $latitudePi));

        $lat = $finalCoordinate + 20.0 * sin($longitudePi) + 40.0 * sin($longitudePi / 3.0);
        $lng = $finalCoordinate + 20.0 * sin($latitudePi) + 40.0 * sin($latitudePi / 3.0);

        $lat = $lat + 160.0 * sin($longitudePi / 12.0) + 320.0 * sin($longitudePi / 30.0);
        $lng = $lng + 150.0 * sin($latitudePi / 12.0) + 300.0 * sin($latitudePi / 30.0);

        $lat = $lat * 2.0 / 3.0;
        $lng = $lng * 2.0 / 3.0;

        $lat = $lat - 100.0 + 2.0 * $latitude + 3.0 * $longitude + 0.2 * $longitude * $longitude + 0.1 * $coordinateMulti + 0.2 * $coordinateAbs;
        $lng = $lng + 300.0 + $latitude + 2.0 * $longitude + 0.1 * $latitude * $latitude + 0.1 * $coordinateMulti + 0.1 * $coordinateAbs;

        return [
            'lat' => $lat,
            'lng' => $lng
        ];
    }

    /**
     * 转换坐标
     *
     * @param $lat
     * @param $lng
     * @return array
     */
    public function toMars($lat, $lng)
    {
        if (!$this->inChinaRegion($lat, $lng)) {
            return [
                'lat' => $lat,
                'lng' => $lng
            ];
        }

        // 计算后的火星坐标
        $marsCoordinate = $this->convertEarthToMars($lng - 105.0, $lat - 35.0);
        $marsLat = $marsCoordinate['lat'];
        $marsLng = $marsCoordinate['lng'];

        $radLat = $lat / 180.0 * M_PI;
        $magic = sin($radLat);
        $magic = 1 - self::ee * $magic * $magic;
        $magicSqrt = sqrt($magic);

        $marsLat = ($marsLat * 180.0) / ((self::earth * (1 - self::ee)) / ($magic * $magicSqrt) * M_PI);
        $marsLng = ($marsLng * 180.0) / (self::earth / $magicSqrt * cos($radLat) * M_PI);

        return [
            'lat' => $lat + $marsLat,
            'lng' => $lng + $marsLng,
        ];
    }
}