<?php

namespace Api\Modules\Skill;

use Core\Db\Persistence as DB;
use Core\Action;
use Api\Modules\Skill\Read as ReadAction;

class Update extends Action {

    public static function process(array $params, DB $persistence): array {
        $success = false;
        
        if(empty($params['_id'])){
            $success = self::create($params, $persistence);
        } else {
            $success = self::update($params, $persistence);
        }
        
        return ['success' => $success];
    }

    protected static function create(array &$params, DB $persistence): bool {
        $params = self::getDefaultResult(ReadAction::DEFAULT_FIELDS, $params);

        $user = self::getCurrentUser();

        $criteria = self::getUserCriteria();
        
        $params['user_id'] = $user['_id'];
        
        return $persistence->create($params, ReadAction::EDUCATION_STORE);
        
    }

    protected static function update(array &$params, DB $persistence): bool {
        $criteria = ['_id' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $params['_id']]];
        
        $item = self::getDefaultResult(ReadAction::DEFAULT_FIELDS, $params);

        $user = self::getCurrentUser();
        
        $item['_id'] = $params['_id'];
        
        $item['user_id'] = $user['_id'];
        
        return $persistence->update($criteria, $item, ReadAction::EDUCATION_STORE);
    }

}
