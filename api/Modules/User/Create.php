<?php

namespace Modules\User;

use Core\Db\Persistence;
use Modules\Action;

class Create extends Action {

    const USER_STORE = 'user';
    
    private static $persistence;

    public static function process(array $params, Persistence $persistence): array {
        self::$persistence = $persistence;
        
        if(self::existUsername($params['username'])) {
            return ["success" => false, 'msg' => "User already exists"];
        }
        
        if($persistence->create($params, self::USER_STORE)){
            return ["success" => true];
        }
        
        return ["success" => false, 'msg' => "Error on saving"];
    }
    
    protected static function existUsername($username): bool {
        $result = self::$persistence->readOne(['username' => $username], self::USER_STORE);
        
        return ($result && is_array($result));
    }

}
