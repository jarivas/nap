<?php

namespace Core;

use \Core\Db\Persistence as DB;

abstract class Action {

    const USER_STORE = 'user';

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

}
