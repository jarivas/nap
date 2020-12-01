<?php

namespace Api\Modules\User;

use Core\Db\Persistence;
use Core\Action;

class Update extends Action
{
    const DATA_STORE = 'user';

    public static function process(array $params, Persistence $persistence): array
    {
    }
}
