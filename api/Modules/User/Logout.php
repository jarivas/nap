<?php


namespace Modules\User;


use Core\Db\Persistence;
use Modules\Action;

class Logout extends Action
{
    const USER_STORE = 'user';
    
    public static function process(array $params, Persistence $persistence): array
    {
        $criteria = ['token' => $params['token']];
        
        $user = $persistence->readOne($criteria, self::USER_STORE);
        
        if($user) {
            unset($user['token']);

            return ['success' => $persistence->update($criteria, $user, self::USER_STORE)];
        }
        
        return ['success' => false];
    }
}
