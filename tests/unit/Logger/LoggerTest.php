<?php declare(strict_types=1);


namespace Nap\Tests\Logger;

use Nap\Configuration\Configuration;
use PHPUnit\Framework\TestCase;
use Nap\Tests\SetUpConfig;

class LoggerTest extends TestCase
{
    use SetUpConfig;

    public static function setUpBeforeClass(): void
    {
        self::SetUpConfig();
    }

    public function testInit(): void
    {
        $result = Configuration::initByIni(self::$iniFile);
        $this->assertNull($result, 'initByIni is failing');

        $config = Configuration::getData('logger');

        $this->assertIsArray($config, 'the config from logger is not an array');
        $this->assertArrayHasKey('class', $config, 'config logger does not have class');

        FileLogger::setConfig($config);

        $dir = ROOT_DIR . 'tests' . DIRECTORY_SEPARATOR . 'log';
        
        $this->assertTrue(FileLogger::init($dir), 'Problem with log permission');
    }
    
    /**
     * @depends testInit
     */
    public function testLog(): void
    {
        $time = time();
        
        sleep(1); //otherwise will be executed in the same sec
        
        $result = FileLogger::debug('This is a test');
        
        $this->assertTrue($result, 'Problem with log write permission');
    }
}
