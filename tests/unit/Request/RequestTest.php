<?php declare(strict_types=1);


namespace Nap\Tests\Request;

use Nap\Tests\Configuration\Configuration;
use Nap\Tests\SetUpConfig;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    use SetUpConfig;

    protected function setUp(): void
    {
        $this->SetUpConfig();
    }

    public function testInit(): void {
        $result = Configuration::initByIni($this->iniFile);
        $this->assertNull($result, 'initByIni is failing');

        $_SERVER['REQUEST_URI'] = '/dummy/read';
    }

    /**
     * @depends testInit
     */
    public function testSetModuleAction(): void
    {
        $authConfig = Configuration::getData('authentication');

        $result = Request::setModuleAction($authConfig['class'], $authConfig);

        $this->assertNull($result, 'setModuleAction is failing');

        $this->assertSame(Request::getModule(), 'Dummy');

        $this->assertSame(Request::getAction(), 'Read');
    }

    /**
     * @depends testInit
     */
    public function testSetRequestByJson() :void
    {
        $body = '';
        $result = Request::setRequestByJson($body);
        $this->assertIsArray($result, 'empty string is valid json body');

        $body = '[';
        $result = Request::setRequestByJson($body);
        $this->assertIsArray($result, '[ is valid json body');

        $body = <<<JSON
{
    "string": "user",
    "default": null,
    "date": "2021-01-01 23:59:59"
}
JSON;
        $result = Request::setRequestByJson($body);
        $this->assertNull($result, 'A valid json creates a problem');

    }

    /**
     * @depends testInit
     */
    public function testSetRequestByQueryString(): void{
        $getParams = ['param1' => 'value1'];

        Request::setRequestByQueryString($getParams);

        $this->assertSame($getParams, Request::getRequest(), 'Problem setting query string');
    }

    /**
     * @depends testInit
     */
    public function testSetRequestHelper() :void
    {
        $result = Request::setRequestHelper('asd');
        $this->assertIsArray($result, 'an invalid method works');

        $result = Request::setRequestHelper('get');
        $this->assertNull($result, 'get should not be invalid');

        $result = Request::setRequestHelper('post');
        $this->assertNull($result, 'post should not be invalid');

        $result = Request::setRequestHelper('put');
        $this->assertNull($result, 'put should not be invalid');

        $result = Request::setRequestHelper('delete');
        $this->assertNull($result, 'delete should not be invalid');
    }

    /**
     * @depends testInit
     */
    public function testSetRequest() :void
    {
        $result = Request::setRequest('asd');
        $this->assertIsArray($result, 'an invalid method works');

        $result = Request::setRequest('get');
        $this->assertNull($result, 'get should not be invalid');

        $result = Request::setRequest('post');
        $this->assertNull($result, 'post should not be invalid');

        $result = Request::setRequest('put');
        $this->assertNull($result, 'put should not be invalid');

        $result = Request::setRequest('delete');
        $this->assertNull($result, 'delete should not be invalid');
    }

    /**
     * @depends testInit
     */
    public function testInitDateTime(): void
    {
        $result = Request::initDateTime();
        $this->assertNull($result, 'problem on initDateTime');
    }

    /**
     * @depends testInit
     */
    public function testSanitize(): void
    {
        $result = Request::sanitize();
        $this->assertNull($result, 'problem on sanitize');
    }

    /**
     * @depends testSetModuleAction
     */
    public function testGetResponseClassName(): void
    {
        $className = Request::getResponseClassName();

        $this->assertTrue($className === 'Action\\Dummy\\Read', 'problem on response classname');
    }
}
