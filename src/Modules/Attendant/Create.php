<?php


namespace Modules\Attendant;


use Core\Db\Persistence;
use Modules\Action;

class Create extends Action
{
    public static function process(array $params, Persistence $persistence): array
    {
        return ['success' => true];
    }
}
