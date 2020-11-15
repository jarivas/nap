<?php

namespace Modules\User;

use Core\Db\Persistence;
use Modules\Action;

class Logout extends Action {

    const USER_STORE = 'user';

    public static function process(array $params, Persistence $persistence): array {
        $criteria = ['token' => $params['token']];

        $item = $persistence->readOne($criteria, self::USER_STORE);
        
        if ($item) {
            $item['token'] = null;
            $item['ip'] = null;
            $item['proxy'] = null;
            $item['expire'] = null;

            return ['success' => $persistence->update($criteria, $item, self::USER_STORE)];
        }

        return ['success' => false];
    }

}
