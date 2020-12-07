<?php

namespace Api\Modules\Skill;

use Core\Db\Persistence as DB;
use Core\Action;

class Read extends Action
{
    const EDUCATION_STORE = 'skill';
    
    const DEFAULT_FIELDS =  [
        'name'
    ];
    
    public static function process(array $params, DB $persistence): array
    {
        $criteria = self::getUserCriteria();
        
        $items = $persistence->read($criteria, self::EDUCATION_STORE);
        
        return ['success' => true, 'data' => $items ? $items : self::getDefaultResult(self::DEFAULT_FIELDS)];
    }
}
