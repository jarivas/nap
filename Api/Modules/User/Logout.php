<?php

namespace Api\Modules\User;

use Core\Db\Persistence;
use Core\Action;

class Logout extends Action
{
    const DATA_STORE = 'user';

    public static function process(array $params, Persistence $persistence): array
    {
        $user = self::getCurrentUser();

        $criteria = ['user_id' => $user['_id']];


        $user['token'] = null;
        $user['ip'] = null;
        $user['proxy'] = null;
        $user['expire'] = null;

        return ['success' => $persistence->update($criteria, $user, self::DATA_STORE)];
    }
}
