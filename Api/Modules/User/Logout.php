<?php

namespace Api\Modules\User;

use Core\Db\Persistence as DB;
use Core\Action;

class Logout extends Action
{
    public static function process(array $params, DB $persistence): array
    {
        $user = self::getCurrentUser();

        $criteria = ['_id' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $user['_id']]];

        $user['token'] = null;
        $user['ip'] = null;
        $user['proxy'] = null;
        $user['expire'] = null;

        return ['success' => $persistence->update($criteria, $user, self::USER_STORE)];
    }
}
