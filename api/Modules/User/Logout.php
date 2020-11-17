<?php

namespace Modules\User;

use Core\Db\Persistence;
use Modules\Action;

class Logout extends Action {

    const DATA_STORE = 'user';

    public static function process(array $params, Persistence $persistence): array {

        $item = self::getUser($params, $persistence);
        
        if ($item) {
            $item['token'] = null;
            $item['ip'] = null;
            $item['proxy'] = null;
            $item['expire'] = null;

            return ['success' => $persistence->update([''], $item, self::DATA_STORE)];
        }

        return ['success' => false];
    }

}
