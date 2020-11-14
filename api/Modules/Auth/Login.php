<?php


namespace Modules\Auth;


use Core\Db\Persistence;
use Modules\Action;

class Login extends Action
{
    const USER_STORE = 'user';
    
    public static function process(array $params, Persistence $persistence): array
    {
        $criteria = ['username' => $params['username']];
        
        $user = $persistence->readOne($criteria, self::USER_STORE);
        
        if(password_verify($params['username'].$params['password'], $user['hash'])) {
            $user = array_merge($user, [
                'token' => uniqid('', true),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'no-ip',
                'proxy' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'no-proxy',
                'expire' => (new DateTime())->add(new DateInterval('P0DT1H'))->getTimestamp()
            ]);
            
            $persistence->update($criteria, $user, self::USER_STORE);

            return ['success' => true, 'token' => $user['token']];
        }
        
        return ['success' => false];
    }
}
