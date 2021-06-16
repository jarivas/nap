<?php

namespace Nap;


use Nap\Configuration\ConfigHelper;

abstract class Action
{
    use ConfigHelper;

    abstract public static function process(array $params): array;

    protected static function responseOk(array $result): array
    {
        return ['ok', $result];
    }

    protected static function responseOkEmpty(int $result): array
    {
        return ['okEmpty', $result];
    }

    protected static function responseWarning(string $message, int $warningType): array
    {
        return ['warning', $message, $warningType];
    }
}
