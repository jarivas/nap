<?php

namespace Api\Modules\Language;

use Core\Db\Persistence as DB;
use Core\Action;

class Read extends Action
{
    const EDUCATION_STORE = 'language';
    
    const DEFAULT_FIELDS =  [
        'name',
        'level'
    ];
    
    public static function process(array $params, DB $persistence): array
    {
        $criteria = self::getUserCriteria();
        
        $items = $persistence->read($criteria, self::EDUCATION_STORE);
        
        return ['success' => true, 'data' => $items ? $items : self::getDefaultResult(self::DEFAULT_FIELDS)];
    }
}
