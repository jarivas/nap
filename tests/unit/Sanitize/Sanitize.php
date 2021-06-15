<?php


namespace Nap\Tests\Sanitize;

use Nap\Sanitize as SanitizeNap;

class Sanitize extends SanitizeNap {

    public static function getRules(string $preKey): array {
        return parent::getRules($preKey);
    }

    public static function applyFilters(string $filters, string $parameterName, array &$parameters, array &$errors) {
        parent::applyFilters($filters, $parameterName, $parameters, $errors);
    }

    public static function applyFilter(string $filter, array &$filtersError, &$value) {
        parent::applyFilter($filter, $filtersError, $value);
    }

    public static function getFilterFlag(string $filter): array {
        return parent::getFilterFlag($filter);
    }

    public static function getErrorMsg(array &$error): string {
        return parent::getErrorMsg($error);
    }

}