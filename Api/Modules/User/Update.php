<?php

namespace Api\Modules\User;

use Core\Db\Persistence as DB;
use Core\Action;

class update extends Action
{
    const DATA_STORE = 'user';

    public static function process(array $params, DB $persistence): array
    {
        
        $user = self::getCurrentUser();
        
        $criteria = ['user_id' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $user['_id']]];
        
        if (!empty($params['username'])) {
            $user['username'] = $params['username'];
        }
        
        if (!empty($params['password'])) {
            $user['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        }
        
        return ['success' => $persistence->update($criteria, $user)];
    }
}
