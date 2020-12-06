<?php

namespace Api\Modules\Position;

use Core\Db\Persistence as DB;
use Core\Action;

class Read extends Action
{
    const POSITION_STORE = 'position';
    
    const DEFAULT_FIELDS =  [
        'company',
        'name',
        'location',
        'description',
        'startDate',
        'endDate'
    ];
    
    public static function process(array $params, DB $persistence): array
    {
        $criteria = self::getUserCriteria();
        
        $items = $persistence->read($criteria, self::POSITION_STORE);
        
        return ['success' => true, 'data' => $items ? $items : self::getDefaultResult(self::DEFAULT_FIELDS)];
    }
}
