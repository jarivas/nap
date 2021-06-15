<?php

namespace Nap;


use Nap\Configuration\ConfigHelper;

abstract class Authentication
{

    use ConfigHelper;

    public static function getProcessor(): ?string
    {
        if (empty(self::$config) || empty(self::$config['class'])) {
            return null;
        }

        return self::$config['class'];
    }

    abstract public static function isValid(array $headers): bool;
}
