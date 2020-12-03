<?php

declare(strict_types=1);

namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Configuration as CoreConfig;

class Configuration extends CoreConfig {

    public static function processIniConfig(string $iniFile, string $jsonFile): array {
        return parent::processIniConfig($iniFile, $jsonFile);
    }

    public static function processIniModule(array &$config): array {
        return parent::processIniModule($config);
    }

}

class ConfigurationTest extends TestCase {

    public function testProcessIniConfig(): void {
        
    }

    public function testProcessIniModule(): void {
        
    }

    public function testGetData(): void {
        
    }

    public function testValidateModuleAction(): void {
        
    }

    public function testShouldAuth(): void {
        
    }

    public function testIsCli(): void {
        
    }

}
