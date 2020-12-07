<?php

namespace Api\Modules\PersonalData;

use Core\Db\Persistence as DB;
use Core\Action;
use Api\Modules\PersonalData\Read as ReadAction;

class Update extends Action {

    public static function process(array $params, DB $persistence): array {
        $params = self::getDefaultResult(ReadAction::DEFAULT_FIELDS, $params);

        $user = self::getCurrentUser();

        $criteria = self::getUserCriteria();

        $item = $persistence->readOne($criteria, ReadAction::PERSONAL_DATA_STORE);
        
        $params['user_id'] = $user['_id'];

        if ($item) {

            $params['_id'] = $item['_id'];
            
            return ['success' => $persistence->update($criteria, $params, ReadAction::PERSONAL_DATA_STORE)];
        }

        return ['success' => $persistence->create($params, self::PERSONAL_DATA_STORE)];
    }

}
