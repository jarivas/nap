<?php

declare(strict_types=1);

namespace Nap\Tests\Sanitize;

use PHPUnit\Framework\TestCase;
use Nap\Tests\SetUpConfig;
use Nap\Configuration\Configuration;

class SanitizeTest extends TestCase {

    use SetUpConfig;

    protected function setUp(): void
    {
        $this->SetUpConfig();
    }

    public function testInit(): void
    {
        $result = Configuration::initByIni($this->iniFile);
        $this->assertNull($result, 'initByIni is failing');

        $config = Configuration::getData('sanitize');

        $this->assertIsArray($config, 'the config from sanitize is not an array');

        $result = Sanitize::setConfig($config);
        $this->assertTrue($result, 'error on init');
    }

    /**
     * @depends testInit
     */
    public function testGetRules(): void {
        $module = 'dummy';
        $action = 'update';
        $preKey = "{$module}_{$action}_";

        $rules = Sanitize::getRules($preKey);

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('dummy_update_string', $rules);
        $this->assertArrayHasKey('dummy_update_default', $rules);
        $this->assertArrayHasKey('dummy_update_date', $rules);

        $this->assertSame('REQUIRED+FILTER_SANITIZE_STRING-FILTER_FLAG_STRIP_HIGH', $rules['dummy_update_string']);
        $this->assertSame('REQUIRED+DEFAULT_10', $rules['dummy_update_default']);
        $this->assertSame('REQUIRED+DATETIME', $rules['dummy_update_date']);
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

        $value = 'tests';

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

        $this->assertCount(2, $filtersError);

        $value = '1992-10-10';

        Sanitize::applyFilter($filter, $filtersError, $value);

        $this->assertCount(2, $filtersError);
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
