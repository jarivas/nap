<?php


namespace Modules;


use Core\Db\Persistence;

abstract class Action
{
    abstract public static function process(array $params, Persistence $persistence): array;
}
