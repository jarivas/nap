<?php

declare(strict_types=1);

namespace Nap\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Nap\Tests\SetUpConfig;

class ConfigurationTest extends TestCase
{
    use SetUpConfig;

    public static function setUpBeforeClass(): void
    {
        self::SetUpConfig();
    }

    public static function tearDownAfterClass(): void
    {
        if (file_exists(self::$jsonFile)) {
            unlink(self::$jsonFile);
        }
    }
    
    public function testProcessIniModule(): void
    {
        $config = $moduleInfo = [];

        $result = Configuration::processIniModule($config, $moduleInfo);

        $this->assertIsArray($result, 'problem, is empty config is working');
        
        $config['actions'] = 1;

        $result = Configuration::processIniModule($config, $moduleInfo);

        $this->assertIsArray($result, 'problem, invalid config is working');

        $config['actions'] = 'testAction1,testAction2';

        $result = Configuration::processIniModule($config, $moduleInfo);

        $this->assertNull($result, 'processIniModule is failing');
        
        $this->assertContains('testAction1', $moduleInfo['actions']);
        
        $this->assertContains('testAction2', $moduleInfo['actions']);
    }
    
    /**
     * @depends testProcessIniModule
     */
    public function testProcessIniConfig(): void
    {
        $this->assertTrue(file_exists(self::$iniFile), 'Ini file does not exists');
        
        if (file_exists(self::$jsonFile)) {
            $this->assertTrue(unlink(self::$jsonFile), 'Json file exists and can not be deleted');
        }

        $result = Configuration::processIniConfig(self::$iniFile, self::$jsonFile);

        $this->assertNull($result, 'error on processIniConfig');
        
        $this->assertTrue(file_exists(self::$jsonFile), 'Json file does not exists');
        
        list($data, $modules) = json_decode(file_get_contents(self::$jsonFile), true);
        
        $this->assertIsArray($data, 'Data from Json file is not an array');
        $this->assertIsArray($modules, 'Modules from Json file is not an array');

        $this->assertArrayHasKey('system', $data, 'data does not have system');
        $this->assertIsArray($data['system'], 'Invalid system value on config');
        $this->assertArrayHasKey('debug', $data['system'], 'data system does not have debug');

        $this->assertArrayHasKey('cors', $data, 'data does not have cors');
        $this->assertIsArray($data['cors'], 'Invalid cors value on config');
        $this->assertArrayHasKey('allowed-origin', $data['cors'], 'cors has not allowed-origin');
        $this->assertArrayHasKey('allowed-headers', $data['cors'], 'cors has not allowed-headers');
        $this->assertArrayHasKey('allowed-methods', $data['cors'], 'cors has not allowed-methods');

        $this->assertArrayHasKey('authentication', $data, 'data does not have authentication');
        $this->assertIsArray($data['authentication'], 'Invalid authentication value on config');
        $this->assertArrayHasKey('class', $data['authentication'], 'authentication has not class');
        $this->assertArrayHasKey('user', $data['authentication'], 'authentication has not user');
        $this->assertArrayHasKey('password', $data['authentication'], 'authentication has not password');

        $this->assertArrayHasKey('logger', $data, 'data does not have logger');
        $this->assertIsArray($data['logger'], 'Invalid authentication value on config');
        $this->assertArrayHasKey('class', $data['authentication'], 'logger has not class');

        $this->assertArrayHasKey('sanitize', $data, 'data does not have sanitize');
        $this->assertIsArray($data['sanitize'], 'Invalid sanitize value on config');
        $this->assertArrayHasKey('dummy_update_string', $data['sanitize'], 'sanitize has not dummy_update_string');
        $this->assertArrayHasKey('dummy_update_default', $data['sanitize'], 'sanitize has not dummy_update_default');
        $this->assertArrayHasKey('dummy_update_date', $data['sanitize'], 'sanitize has not dummy_update_date');

        $this->assertArrayHasKey('dummy', $modules, 'data does not have dummy');
        $this->assertIsArray($modules['dummy'], 'Invalid dummy module value on config');
        $this->assertArrayHasKey('actions', $modules['dummy'], 'dummy module has not actions');
        $this->assertArrayHasKey('auth', $modules['dummy'], 'dummy module has not auth');
    }

    /**
     * @depends testProcessIniConfig
     */
    public function testGetData(): void
    {
        $result = Configuration::initByIni(self::$iniFile);

        $this->assertNull($result, 'initByIni is failing');

        $shouldNull = Configuration::getData('xxx');
        $this->assertNull($shouldNull, 'Configuration::getData is returning a value for xxx');

        $system = Configuration::getData('system');
        
        $this->assertIsArray($system, 'Invalid system value on get data');
        $this->assertArrayHasKey('debug', $system, 'system has no debug value on get data');
        $this->assertTrue($system['debug'], 'Invalid debug value on get data');
    }

    /**
     * @depends testGetData
     */
    public function testValidateModuleAction(): void
    {
        $result = Configuration::validateModuleAction('dummy', 'xxx');
        $this->assertFalse($result, 'Module dummy, action xxx should not be part of the tests config');

        $result = Configuration::validateModuleAction('dummy', 'read');
        $this->assertTrue($result, 'Module dummy, action read should be part of the tests config');
    }

    /**
     * @depends testGetData
     */
    public function testShouldAuth(): void
    {
        $result = Configuration::shouldAuth('dummy', 'xxx');
        $this->assertFalse($result, 'Module dummy, action xxx should not be in auth part of the tests config');

        $result = Configuration::shouldAuth('dummy', 'read');
        $this->assertFalse($result, 'Module dummy, action read should not be in auth part of the tests config');

        $result = Configuration::shouldAuth('dummy', 'update');
        $this->assertTrue($result, 'Module dummy, action update should be part of the tests config');
    }
}
