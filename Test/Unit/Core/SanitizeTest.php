<?php

declare(strict_types=1);

namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Sanitize as CoreSanitize;

class Sanitize extends CoreSanitize {

    public static function getRules(string $preKey) {
        return parent::getRules($preKey);
    }

    public static function applyFilters(string $filters, string $parameterName, array &$parameters, array &$errors) {
        parent::applyFilters($filters, $parameterName, $parameters, $errors);
    }

    public static function applyFilter(string $filter, array &$filtersError, $value) {
        parent::applyFilter($filter, $filtersError, $value);
    }

    public static function getErrorMsg(array &$error): string {
        return parent::getErrorMsg($error);
    }

}

class SanitizeTest extends TestCase {

    public function testConfig(): void {
        
    }

    /**
     * @depends testConfig
     */
    public function testGetRules(): void {
        
    }

    public function testApplyFilters(): void {
        
    }

    public function testApplyFilter(): void {
        
    }

    public function testGetErrorMsg(): void {
        
    }

}
