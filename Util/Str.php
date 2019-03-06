<?php

namespace Nonda\Util;

use Nonda\Exception\BaseException;
use Ramsey\Uuid\Uuid;

class Str
{
    protected static $studlyCache;
    protected static $studlyClassCache;

    public static function strtoupper($str)
    {
        $tmp = str_split($str, 1);
        $result = '';

        foreach ($tmp as $char) {
            if ('' == $char) {
                continue;
            }

            $ord = ord($char);

            if ($ord >= 97 AND $ord <= 122) {
                $ord -= 32;
            }

            $result .= chr($ord);
        }

        return $result;
    }

    public static function strtolower($str)
    {


        $tmp = str_split($str, 1);
        $result = '';

        foreach ($tmp as $char) {
            if ('' == $char) {
                continue;
            }

            $ord = ord($char);

            if ($ord >= 65 AND $ord <= 90) {
                $ord += 32;
            }

            $result .= chr($ord);
        }

        return $result;
    }

    public static function ucfirst($str)
    {
        if ($str AND ord($str[0]) >= 97 AND ord($str[0]) <= 122) {
            $str[0] = chr(ord($str[0]) - 32);
        }

        return $str;
    }

    public static function lcfirst($str)
    {
        if ($str AND ord($str[0]) >= 65 AND ord($str[0]) <= 90) {
            $str[0] = chr(ord($str[0]) + 32);
        }

        return $str;
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * Convert a value to studly caps class name.
     *
     * @param  string  $value
     * @return string
     */
    public static function studlyClass($value)
    {
        $key = $value;

        if (isset(static::$studlyClassCache[$key])) {
            return static::$studlyClassCache[$key];
        }

        $value = ucwords(preg_replace('/(\W|_)/', ' ', $value));

        return static::$studlyClassCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    public static function randNumberWordStr($length = 10, $characters = null)
    {
        $characters = $characters ?: '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function randPassword($length = 16)
    {
        return self::randNumberWordStr($length, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?');
    }

    public static function generateToken($length = 32)
    {
        if ($length <= 0) {
            throw new BaseException('Zero-length randomHexString is useless.', BaseException::INVALID_ARGUMENT);
        }

        if (($length % 2) !== 0) {
            throw new BaseException('randomHexString size must be divisible by 2.', BaseException::INVALID_ARGUMENT);
        }

        

        return bin2hex(random_bytes($length/2));
    }

    /**
     * 掩盖字符串一部分
     *
     * @param string $string
     * @param int    $length
     * @param int    $start
     * @param string $coverBy
     *
     * @return mixed
     */
    public static function coverString($string, $length = 0, $start = 0, $coverBy = '*')
    {
        if ((mb_strlen($string) - $start) < $length) {
            $length = mb_strlen($string) - $start;
        }

        $coverBy = str_repeat($coverBy, $length);

        return self::mbSubstrReplace($string, $coverBy, $start, $start + $length - 1);
    }

    /**
     * 掩盖邮箱
     *
     * sensen@nonda.us => s***en@n***a.us
     *
     * @param string $email
     * @param string $coverBy
     *
     * @return string
     */
    public static function coverEmail($email, $coverBy = '*', $coverDomain = false)
    {
        $atPos = mb_stripos($email, '@');

        if (!$atPos) {
            return $email;
        }

        $firstPart = mb_substr($email, 0, $atPos);
        $secondPart = mb_substr($email, $atPos);

        if ($coverDomain) {
            $length = floor(mb_strlen($secondPart) / 3);
            $start = floor($length / 2) + 1;
            $secondPart = self::coverString($secondPart, $length, $start, $coverBy);
        }

        if (mb_strlen($firstPart) == 1) {
            return $email;
        }

        switch (mb_strlen($firstPart)) {
            case 1:
                return $email;
                break;

            case 2:
                return self::coverString($firstPart, 1, 0, $coverBy).$secondPart;
                break;

            case 3:
            case 4:
                return self::coverString($firstPart, 2, 0, $coverBy).$secondPart;
                break;

            default:
                //$length = floor(mb_strlen($firstPart)/2);
                $length = 3;
                $start = floor(mb_strlen($firstPart)/4);

                return self::coverString($firstPart, $length, $start, $coverBy).$secondPart;
        }
    }

    /**
     * utf8 安全的字符串替换
     *
     * 注意：这里的开始和结束替换位置是左右包含的
     *
     * @param string $string
     * @param string $replace
     * @param int    $posOpen
     * @param int    $posClose
     *
     * @return string
     */
    public static function mbSubstrReplace($string, $replace, $posOpen, $posClose) {
        return mb_substr($string, 0, $posOpen).$replace.mb_substr($string, $posClose+1);
    }

    /**
     * 将 parse_url() 返回的数组重新还原回字符串的url
     *
     * @param array $parsedUrl
     *
     * @return string
     */
    public static function unparseUrl($parsedUrl)
    {
        $scheme   = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host     = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $port     = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user     = isset($parsedUrl['user']) ? $parsedUrl['user'] : '';
        $pass     = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $query    = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * 拼接一个给mongo用的时间格式
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function makeMongoIsoDate(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $string = $date->format('Y-m-d\TH:i:s.');
        $string .= str_pad(round($date->format('u')/1000), 3, "0", STR_PAD_LEFT) . 'Z';

        return $string;
    }

    /**
     * 生成一个Content-ID(aka: RFC2822#msg-id)
     * @see https://www.ietf.org/rfc/rfc2822.txt
     *
     * @param string $left
     * @param string $right
     *
     * @return string
     */
    public static function makeCID($left = null, $right = null)
    {
        if (!$left) {
            $left = (string)Uuid::uuid4();
        }

        if (!$right) {
            $right = 'nonda.us';
        }

        return "{$left}@{$right}";
    }
}
