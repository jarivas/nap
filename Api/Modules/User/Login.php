<?php

namespace Api\Modules\User;

use Core\Db\Persistence as DB;
use Core\Action;

class Login extends Action
{
    
    public static function process(array $params, DB $persistence): array
    {        
        $criteria = ['username' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $params['username']]];
        
        $user = $persistence->readOne($criteria, self::USER_STORE);
        
        if (password_verify($params['password'], $user['password'])) {
            $user = array_merge($user, [
                'token' => uniqid('', true),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'no-ip',
                'proxy' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'no-proxy',
                'expire' => (new \DateTime())->add(new \DateInterval('P0DT1H'))->getTimestamp()
            ]);

            if ($persistence->update($criteria, $user, self::USER_STORE)) {
                return ['success' => true, 'token' => $user['token']];
            }
        }

        return ['success' => false];
    }
}
