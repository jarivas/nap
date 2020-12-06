<?php

declare(strict_types=1);

namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Configuration as CoreConfig;
use Exception;

class Configuration extends CoreConfig {

    public static function processIniConfig(string $iniFile, string $jsonFile): array {
        return parent::processIniConfig($iniFile, $jsonFile);
    }

    public static function processIniModule(array &$config): array {
        return parent::processIniModule($config);
    }

}

class ConfigurationTest extends TestCase {
    
    private $iniFile = ROOT_DIR . 'Test/config/config.ini';
    private $jsonFile = ROOT_DIR . 'Test/config/config.json';
    
    public function testProcessIniModule(): void {
        $config = [];
        
        try {
            Configuration::processIniModule($config);
            $this->assertTrue(false, 'This line should net be reached');
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
            $this->assertSame('Actions not present', $msg, 'Another error was triggered');
        }
        
        $config['actions'] = 1;
        
        try {
            Configuration::processIniModule($config);
            $this->assertTrue(false, 'This line should net be reached');
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
            $this->assertSame('Actions not valid', $msg, 'Another error was triggered');
        }
        
        $config['actions'] = 'testAction1, testAction 2';
        
        $module = Configuration::processIniModule($config);
        
        $this->assertContains('testAction1', $module['actions']);
        
        $this->assertContains('testAction2', $module['actions']);
    }
    
    
    /**
     * @depends testProcessIniModule
     */
    public function testProcessIniConfig(): void {
        $this->assertTrue(file_exists($this->iniFile), 'Ini file does not exists');
        
        if (file_exists($this->jsonFile)) {
            $this->assertTrue(unlink($this->jsonFile), 'Json file exists and can not be deleted');
        }
        
        list($result, $msg) = Configuration::processIniConfig($this->iniFile, $this->jsonFile);
        
        $this->assertTrue($result, $msg);
        
        $this->assertTrue(file_exists($this->jsonFile), 'Json file does not exists');
        
        
        list($data, $modules) = json_decode(file_get_contents($this->jsonFile), true);
        
        $this->assertIsArray($data, 'Data from Json file is not an array');
        $this->assertIsArray($modules, 'Modules from Json file is not an array');
        
        $this->assertIsArray($data['system'], 'Invalid system value on config');
        $this->assertArrayHasKey('debug', $data['system'], 'data system does not have debug');
        
        $this->assertIsArray($modules['user'], 'Invalid user value on config');
        $this->assertArrayHasKey('actions', $modules['user'], 'user does not have actions');
    }

    /**
     * @depends testProcessIniConfig
     */
    public function testGetData(): void {
        list($result, $msg) = Configuration::init($this->iniFile);
        
        $this->assertTrue($result, $msg);
        
        $system = Configuration::getData('system');
        
        $this->assertIsArray($system, 'Invalid system value on get data');
        $this->assertArrayHasKey('debug', $system, 'system has no debug value on get data');
        $this->assertTrue($system['debug'], 'Invalid debug value on get data');
    }

    
    /**
     * @depends testGetData
     */
    public function testValidateModuleAction(): void {
        $result = Configuration::validateModuleAction('user', 'login');
        $this->assertTrue($result, 'Module user, action login should be part of the test config');
    }

    /**
     * @depends testGetData
     */
    public function testShouldAuth(): void {
        $result = Configuration::shouldAuth('user', 'logout');
        $this->assertTrue($result, 'Module user, action logout should be part of the test config');
    }

    /**
     * @depends testGetData
     */
    public function testIsCli(): void {
        $result = Configuration::isCli('user', 'create');
        $this->assertTrue($result, 'Module user, action create should be part of the test config');
    }

}
