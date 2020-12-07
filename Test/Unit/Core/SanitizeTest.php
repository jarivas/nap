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

class SanitizeTest extends TestCase {

    private $iniFile = ROOT_DIR . 'Test/config/config.ini';

    public function testConfig(): void {

        list($result, $msg) = Configuration::init($this->iniFile);

        $this->assertTrue($result, $msg);
    }

    /**
     * @depends testConfig
     */
    public function testGetRules(): void {
        $module = 'user';
        $action = 'login';
        $preKey = "{$module}_{$action}_";

        $rules = Sanitize::getRules($preKey);

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('user_login_username', $rules);
        $this->assertArrayHasKey('user_login_password', $rules);

        $this->assertSame('REQUIRED+FILTER_SANITIZE_STRING-FILTER_FLAG_STRIP_HIGH', $rules['user_login_username']);
        $this->assertSame('REQUIRED+FILTER_SANITIZE_STRING', $rules['user_login_password']);
    }

    private function ApplyFilterDefault(): void {
        $filter = 'DEFAULT_test';
        $filtersError = [];
        $value = '';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertSame('test', $value);
    }

    private function ApplyFilterRequired(): void {
        $filter = 'REQUIRED';
        $filtersError = [];
        $value = '';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertCount(1, $filtersError);

        $value = 'test';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertCount(1, $filtersError);
    }

    private function ApplyFilterDateTime(): void {
        $filter = 'DATETIME';
        $filtersError = [];
        $value = '10-10-1992';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertCount(1, $filtersError);

        $value = '1992-10-10 23:59:29';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertCount(1, $filtersError);
    }

    private function ApplyFilterJSON(): void {
        $filter = 'JSON';
        $filtersError = [];
        $value = <<<JSON
{
  "array": [
    1,
    2,
    3
  ],
  "boolean": true,
  "null": null,
  "number": 123,
  "object": {
    "a": "b",
    "c": "d",
    "e": "f"
  },
  "string": "Hello World"
}
JSON;

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertCount(0, $filtersError);
    }

    private function ApplyFilterFilter(): void {
        $filter = 'FILTER_SANITIZE_STRING-FILTER_FLAG_STRIP_HIGH';
        $filtersError = [];
        $value = '<h1>Hello WorldÆØÅ!</h1>';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertSame('Hello World!', $value);
    }
    
    private function GetFilterFlag(): void {
        $filter = 'FILTER_SANITIZE_STRING-FILTER_FLAG_STRIP_HIGH';
        
        $flag = Sanitize::getFilterFlag($filter);
        
        $this->assertSame('FILTER_SANITIZE_STRING', $flag[0]);
        $this->assertSame('FILTER_FLAG_STRIP_HIGH', $flag[1]);
    }
    
    public function testApplyFilter(): void {
        $this->ApplyFilterDefault();
        $this->ApplyFilterRequired();
        $this->ApplyFilterDateTime();
        $this->ApplyFilterJSON();
        $this->GetFilterFlag();
        $this->ApplyFilterFilter();
    }

    /**
     * @depends testApplyFilter
     */
    public function testApplyFilters(): void {
        $parameters = ['username' => '', 'password' => ''];
        $errors = [];
        
        Sanitize::applyFilters('DEFAULT_jose+REQUIRED+FILTER_SANITIZE_STRING', 'username', $parameters, $errors);
        $this->assertCount(0, $errors);        
        
        Sanitize::applyFilters('REQUIRED+FILTER_SANITIZE_STRING+DEFAULT_jose', 'username', $parameters, $errors);
        $this->assertCount(0, $errors);
        
        Sanitize::applyFilters('REQUIRED+FILTER_SANITIZE_STRING', 'password', $parameters, $errors);
        $this->assertCount(1, $errors);
    }
}
