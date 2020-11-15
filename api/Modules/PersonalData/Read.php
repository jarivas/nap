<?php

namespace Modules\PersonalData;

use Core\Db\Persistence;
use Modules\Action;

class Read extends Action {

    const DATA_STORE = 'personalData';

    public static function process(array $params, Persistence $persistence): array {
        
        $criteria = ['user_id' => self::getUserId($params, $persistence)];
        
        return $persistence->read($criteria, self::DATA_STORE);
    }

}
