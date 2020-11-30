<?php

namespace Api\Modules\User;

use Core\Db\Persistence;
use Core\Action;

class Create extends Action {

    const DATA_STORE = 'user';
    
    /**
     *
     * @var Persistence 
     */
    private static $persistence;

    public static function process(array $params, Persistence $persistence): array {
        self::$persistence = $persistence;
        
        if(self::existUsername($params['username'])) {
            return ["success" => false, 'msg' => "User already exists"];
        }
        
        if($persistence->create($params, self::DATA_STORE)){
            return ["success" => true];
        }
        
        return ["success" => false, 'msg' => "Error on saving"];
    }
    
    protected static function existUsername(string $username): bool {
        $result = self::$persistence->readOne(['username' => $username], self::DATA_STORE);
        
        return ($result && is_array($result));
    }

}
