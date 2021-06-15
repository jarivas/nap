<?php declare(strict_types=1);


namespace Nap\Tests;

use Nap\DTHelper;
use Nap\Tests\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

class DTHelperTest extends TestCase
{
    use SetUpConfig;

    protected function setUp(): void
    {
        $this->SetUpConfig();
    }

    public function testInit(): void {

        $result = Configuration::initByIni($this->iniFile);
        $this->assertNull($result, 'initByIni is failing');

        $config = Configuration::getData('dthelper');

        $this->assertIsArray($config, 'the config from dthelper is not an array');
        $this->assertArrayHasKey('date_time_format', $config, 'dthelper has no date_time_format value on get data');
        $this->assertArrayHasKey('date_time_zone', $config, 'dthelper has no date_time_zone value on get data');

        $result = DTHelper::setConfig($config);
        $this->assertTrue($result, 'error on setConfig');

        $result = DTHelper::init();
        $this->assertNull($result, 'error on init');
    }

    /**
     * @depends testInit
     */
    public function testSetDateTimeFormat(): void
    {
        $format = '';
        $result = DTHelper::setDateTimeFormat($format);
        $this->assertFalse($result, 'Empty string is accepted as DTFormat');

        $format = 'Y-m-d';
        $result = DTHelper::setDateTimeFormat($format);
        $this->assertTrue($result, 'Y-m-d is not accepted as DTFormat');
    }

    /**
     * @depends testInit
     */
    public function testSetDateTimeZone(): void
    {
        $zone = '';
        $result = DTHelper::setDateTimeZone($zone);
        $this->assertFalse($result, 'Empty string is accepted as DTZone');

        $zone = 'USA/LosAngeles';
        $result = DTHelper::setDateTimeZone($zone);
        $this->assertFalse($result, 'USA/LosAngeles is accepted as DTZone');

        $zone = 'America/Los_Angeles';
        $result = DTHelper::setDateTimeZone($zone);
        $this->assertTrue($result, 'America/Los_Angeles is not accepted as DTZone');
    }

    /**
     * @depends testInit
     */
    public function testGetDate(): void
    {
        $dt = '';
        $result = DTHelper::getDate($dt);
        $this->assertNull($result, 'Empty string is not accepted as value for getDate');

        $dt = '15-Feb-2009';
        $result = DTHelper::getDate($dt);
        $this->assertNull($result, '15-Feb-2009 is not accepted as value for getDate');

        $dt = 'now';
        $result = DTHelper::getDate($dt);
        $this->assertNull($result, 'now is not accepted as value for getDate');

        $dt = '2021-01-01';
        $result = DTHelper::getDate($dt);
        $this->assertisObject($result, '2021-01-01 is not accepted as value for getDate');
    }
}
