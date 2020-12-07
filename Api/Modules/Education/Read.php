<?php

namespace Api\Modules\Education;

use Core\Db\Persistence as DB;
use Core\Action;

class Read extends Action
{
    const EDUCATION_STORE = 'education';
    
    const DEFAULT_FIELDS =  [
        'degree',
        'notes',
        'schoolName',
        'startDate',
        'endDate'
    ];
    
    public static function process(array $params, DB $persistence): array
    {
        $criteria = self::getUserCriteria();
        
        $items = $persistence->read($criteria, self::EDUCATION_STORE);
        
        return ['success' => true, 'data' => $items ? $items : self::getDefaultResult(self::DEFAULT_FIELDS)];
    }
}
