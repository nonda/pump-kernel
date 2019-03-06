<?php
//zhaojun@nonda.us

namespace Nonda\Util;

class EncoderHelper {
    const HASH_MAP = "fcHdFGqmTMLW1jt3YZ-2DNOXkuvBx9aboClS5e8UVwp0PQR7IJghrsyz_A4nEK6i";
    const CODE_MAP = "9MCQTA24SKR3N6PV7XHJLUD5BZ8WEYGF";

    public static function code2dec($code, $offset = 0) {
        return base_convert(self::code2hex($code, $offset), 16, 10);
    }

    public static function dec2code($dec, $offset = 0) {
        return self::hex2code(base_convert($dec, 10, 16), $offset);
    }

    public static function code2hex($code, $offset = 0) {
        $map = self::code_map($offset);

        $hex = '';
        while (strlen($code) > 0) {
            $_sub_str = $sub_str = substr($code, -4);

            $ord = '';
            for ($i = 0; $i < 4; $i++) {
                if (strlen($_sub_str)) {
                    $char = substr($_sub_str, -1);
                    $_sub_str = substr($_sub_str, 0, -1);
                    $ord = str_pad(base_convert(strpos($map, $char), 10, 2), 5, "0", STR_PAD_LEFT) . $ord;
                } else {
                    $ord = '00000' . $ord;
                }
            }

            $hex = base_convert(substr($ord, 0, 4), 2, 16)
                . base_convert(substr($ord, 4, 4), 2, 16)
                . base_convert(substr($ord, 8, 4), 2, 16)
                . base_convert(substr($ord, 12, 4), 2, 16)
                . base_convert(substr($ord, 16, 4), 2, 16)
                . $hex;
            $code = substr($code, 0, strlen($code) - strlen($sub_str));
        }

        return ltrim($hex, '0');
    }

    public static function hex2code($hex, $offset = 0) {
        $map = self::code_map($offset);

        $code = '';
        while (strlen($hex) > 0) {
            $sub_str = substr($hex, -5);
            $ord = str_pad(base_convert($sub_str, 16, 2), 20, "0", STR_PAD_LEFT);
            $code = substr($map, base_convert(substr($ord, 0, 5), 2, 10), 1)
                . substr($map, base_convert(substr($ord, 5, 5), 2, 10), 1)
                . substr($map, base_convert(substr($ord, 10, 5), 2, 10), 1)
                . substr($map, base_convert(substr($ord, 15, 5), 2, 10), 1) . $code;
            $hex = substr($hex, 0, strlen($hex) - strlen($sub_str));
        }

        return $code;
    }

    public static function hash2dec($hash, $offset = 0) {
        return base_convert(self::hash2hex($hash, $offset), 16, 10);
    }

    public static function dec2hash($dec, $offset = 0) {
        return self::hex2hash(base_convert($dec, 10, 16), $offset);
    }

    public static function hash2hex($hash, $offset = 0) {
        $map = self::hash_map($offset);

        $hex = '';
        while (strlen($hash) > 0) {
            $sub_str = substr($hash, -2);
            $sub_str0 = $sub_str1 = '';
            if (strlen($sub_str) == 2) {
                $sub_str0 = substr($sub_str, 0, 1);
                $sub_str1 = substr($sub_str, 1, 1);
            } else {
                $sub_str1 = $sub_str;
            }

            $ord = str_pad(base_convert(strpos($map, $sub_str0), 10, 2), 6, "0", STR_PAD_LEFT)
                . str_pad(base_convert(strpos($map, $sub_str1), 10, 2), 6, "0", STR_PAD_LEFT);

            $hex = base_convert(substr($ord, 0, 4), 2, 16)
                . base_convert(substr($ord, 4, 4), 2, 16)
                . base_convert(substr($ord, 8, 4), 2, 16)
                . $hex;
            $hash = substr($hash, 0, strlen($hash) - strlen($sub_str));
        }

        return ltrim($hex, '0');
    }

    public static function hex2hash($hex, $offset = 0) {
        $map = self::hash_map($offset);

        $hash = '';
        while (strlen($hex) > 0) {
            $sub_str = substr($hex, -3);
            $ord = str_pad(base_convert($sub_str, 16, 2), 12, "0", STR_PAD_LEFT);
            $hash = substr($map, base_convert(substr($ord, 0, 6), 2, 10), 1)
                . substr($map, base_convert(substr($ord, 6, 6), 2, 10), 1) . $hash;
            $hex = substr($hex, 0, strlen($hex) - strlen($sub_str));
        }

        return $hash;
    }

    public static function code_map($offset = 0) {
        $map = self::CODE_MAP;
        if ($offset) {
            $map = substr($map, $offset) . substr($map, 0, $offset);
        }
        return $map;
    }

    public static function hash_map($offset = 0) {
        $map = self::HASH_MAP;
        if ($offset) {
            $map = substr($map, $offset) . substr($map, 0, $offset);
        }
        return $map;
    }
}
