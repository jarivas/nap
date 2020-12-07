<?php

namespace Core;

use \Core\Db\Persistence as DB;

abstract class Action {

    const USER_STORE = 'user';
    const MAX_ROWS = 50;

    abstract public static function process(array $params, DB $persistence): array;

    protected static function getCurrentUser(): ?array {
        return empty($GLOBALS['user']) ? null : $GLOBALS['user'];
    }

    protected static function getUserCriteria(): array {

        $user = self::getCurrentUser();

        return ['user_id' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $user['_id']]];
    }

    protected static function getDefaultResult(array $defaultFields, ?array $valueFields = null) {
        $result = [];

        foreach ($defaultFields as $key) {
            $result[$key] = ($valueFields && !empty($valueFields[$key])) ? $valueFields[$key] : '';
        }

        return $result;
    }
    
    protected static function getLimit(array &$params): int {
        if (empty($params['max'])) {
            return self::MAX_ROWS;
        }
        
        $max = intval($params['max']);
        
        if ($max < 1 || $max > 50) {
            return self::MAX_ROWS;
        }
        
        return $max;
    }
    
    protected static function getSkip(array &$params):int {
        $page = 0;
        
        if (isset($params['page']) && intval($params['page']) > $page) {
            $page = intval($params['page']);
        }
        
        return $page * self::getLimit($params);
    }

}
