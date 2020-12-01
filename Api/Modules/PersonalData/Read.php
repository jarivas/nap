<?php

namespace Api\Modules\PersonalData;

use Core\Db\Persistence;
use Core\Action;

class Read extends Action
{
    const DATA_STORE = 'personalData';

    public static function process(array $params, Persistence $persistence): array
    {
        $user = self::getCurrentUser();
        
        $criteria = ['user_id' => $user['_id']];
        
        return $persistence->read($criteria, self::DATA_STORE);
    }
}
