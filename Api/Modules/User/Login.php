<?php

namespace Api\Modules\User;

use Core\Db\Persistence;
use Modules\Action;

class Login extends Action {

    public static function process(array $params, Persistence $persistence): array {
        $user = self::getCurrentUser();
        
        $criteria = ['user_id' => $user['_id']];
        
        if (password_verify($params['password'], $user['password'])) {

            $user = array_merge($user, [
                'token' => uniqid('', true),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'no-ip',
                'proxy' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'no-proxy',
                'expire' => (new \DateTime())->add(new \DateInterval('P0DT1H'))->getTimestamp()
            ]);

            if ($persistence->update($criteria, $user, self::DATA_STORE)) {
                return ['success' => true, 'token' => $user['token']];
            }
            
        }

        return ['success' => false];
    }

}
