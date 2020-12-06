<?php

namespace Api\Modules\User;

use Core\Db\Persistence as DB;
use Core\Action;

class Create extends Action
{
    
    protected static function existsUsername(string $username, DB $persistence): bool
    {
        $criteria = ['username' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $username]];
        
        $user = $persistence->readOne($criteria, self::USER_STORE);        
        
        return ($user && is_array($user));
    }

    public static function process(array $params, DB $persistence): array {
        
        if (self::existsUsername($params['username'], $persistence)) {
            return ["success" => false, 'msg' => "User already exists"];
        }
        
        if ($persistence->create($params, self::USER_STORE)) {
            return ["success" => true];
        }
        
        return ["success" => false, 'msg' => "Error on saving"];
    }

}
