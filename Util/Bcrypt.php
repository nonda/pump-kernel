<?php

namespace Nonda\Util;

class Bcrypt {
    public static function hash($password) {
        $options = [
            'cost' => 10,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public static function verify($password, $encrypted) {
        return password_verify($password, $encrypted);
    }

    public static function rand($length = 100) {
        return openssl_random_pseudo_bytes($length);
    }

}