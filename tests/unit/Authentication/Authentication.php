<?php


namespace Nap\Tests\Authentication;


class Authentication extends \Nap\Authentication
{

    public static function isValid(array $headers): bool
    {
        if (empty($headers)) {
            return false;
        }

        return ($headers['user'] === self::$config['user'] && $headers['password'] === self::$config['password']);
    }
}