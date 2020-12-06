<?php declare(strict_types=1);


namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Request as CoreRequest;

class Request extends CoreRequest {
    
    public static function setRequestData(string $body)
    {
        parent::setRequestData($body);
    }
    
    public static function getData(): array {
        return self::$data;
    }

    public static function getModuleAction(): string
    {
        return parent::getModuleAction();
    }

    public static function getParameters(): array
    {
        return parent::getParameters();
    }
}

class RequestTest extends TestCase
{
    
    public function testSetRequestData(): void
    {
        $body = <<<JSON
{
    "module": "user",
    "action": "login",
    "parameters": {
        "username": "jose",
        "password": "hola"
    }
}
JSON;
        
        Request::setRequestData($body);
        
        $data = Request::getData();
        
        $this->assertArrayHasKey('module', $data);
        $this->assertSame('user', $data['module']);
        
        $this->assertArrayHasKey('action', $data);
        $this->assertSame('login', $data['action']);
        
        $this->assertArrayHasKey('parameters', $data);
        $this->assertArrayHasKey('username', $data['parameters']);
        $this->assertArrayHasKey('password', $data['parameters']);
    }
    
    /**
     * @depends testSetRequestData
     */
    public function testGetModuleAction(): void
    {
        $expected = "Api\\Modules\\User\\Login::process";
        
        $actual = Request::getModuleAction();
        
        $this->assertSame($expected, $actual);
    }
    
    /**
     * @depends testSetRequestData
     */
    public function testGetParameters(): void
    {
        $actual = Request::getParameters();
        
        $this->assertIsArray($actual);
        $this->assertArrayHasKey('username', $actual);
        $this->assertArrayHasKey('password', $actual);
    }
}
