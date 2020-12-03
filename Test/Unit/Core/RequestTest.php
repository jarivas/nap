<?php declare(strict_types=1);


namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Request as CoreRequest;

class Request extends CoreRequest {
    
    public static function setRequestData(string $body)
    {
        parent::setRequestData($body);
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
        
    }
    
    /**
     * @depends testSetRequestData
     */
    public function testGetModuleAction(): void
    {
        
    }
    
    /**
     * @depends testSetRequestData
     */
    public function testGetParameters(): void
    {
        
    }
    
    /**
     * @depends testSetRequestData
     */
    public function testGetResponse(): void
    {
        
    }
}
