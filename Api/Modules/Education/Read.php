<?php

namespace Api\Modules\PersonalData;

use Core\Db\Persistence as DB;
use Core\Action;

class Read extends Action
{
    const EDUCATION_STORE = 'personalData';
    
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
        
        $item = $persistence->read($criteria, self::EDUCATION_STORE);
        
        return ['success' => true, 'data' => $items ? $items : self::getDefaultResult(self::DEFAULT_FIELDS)];
    }
}
