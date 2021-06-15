<?php

namespace Nap;


use Nap\Configuration\ConfigHelper;

abstract class Action
{
    use ConfigHelper;

    abstract public static function process(array $params): array;
}
