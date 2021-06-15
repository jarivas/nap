<?php

declare(strict_types=1);

namespace Nap\Tests\Authentication;

use Nap\Configuration\Configuration;
use PHPUnit\Framework\TestCase;
use Nap\Tests\SetUpConfig;

class AuthenticationTest extends TestCase {

    use SetUpConfig;

    protected function setUp(): void
    {
        $this->SetUpConfig();
    }

    public function testInit(): void {
        $result = Configuration::initByIni($this->iniFile);
        $this->assertNull($result, 'initByIni is failing');

        $config = Configuration::getData('authentication');

        $this->assertIsArray($config, 'the config from authentication is not an array');
        $this->assertArrayHasKey('class', $config, 'config authentication does not have class');

        Authentication::setConfig($config);
    }

    /**
     * @depends testInit
     */
    public function testAuthentication(): void {
        $config = Configuration::getData('authentication');

        $this->assertFalse(Authentication::isValid([]), 'Authentication failed to fail');

        $this->assertTrue(Authentication::isValid($config), 'Authentication failed');
    }
}
