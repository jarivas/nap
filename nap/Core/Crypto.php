<?php

namespace Nap;

class Crypto {

    protected static $cost = 12;
    protected static $algo = PASSWORD_DEFAULT;

    public static function hash(string $password): string {
        return password_hash($pwd, self::$algo, ['cost' => self::$cost]);
    }

}
