<?php


namespace Nap\Configuration;


trait ConfigHelper
{
    protected static array $config;

    public static function setConfig(array $config):bool
    {
        if (empty($config)) {
            return false;
        }

        self::$config = $config;

        return true;
    }
}