<?php declare(strict_types=1);


namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Configuration as CoreConfig;
use Core\BaseAuthentication as Auth;
use Core\Db\Persistence as DB;


class BaseAuthenticationTest extends TestCase {
    private $storeName = 'user';
    
    private $item = ['name' => 'Jose', 'age' => 37];
    
    private $token = "t1235";
    
    public function testIsValidNoToken(): void
    {
        
        $result = Auth::isValid([]);
        
        $this->assertFalse($result, 'This should not be true');
    }
    
    /**
     * @depends testIsValidNoToken
     */ 
    public function testIsValidRandomToken(): void
    {
        
        list($result, $message) = CoreConfig::init();
        
        $this->assertTrue($result, "Error on config init: $message");
        
        $persistence = DB::getPersistence();
        
        $criteria = [
            '_id' => [DB::CRITERIA_AND, DB::CRITERIA_NOT_EQUAL, 0]
        ];
        
        $result = $persistence->delete($criteria, $this->storeName);
        
        $this->assertTrue($result, 'Error on cleaning db');
        
        $params = ['token' => $this->token];
        
        $result = Auth::isValid($params);
        
        $this->assertFalse($result, 'This should not be true');   
    }
    
    
    /**
     * @depends testIsValidRandomToken
     */ 
    public function testIsValidRightToken(): void
    {
        
        $item = $this->item;
        $item['token'] = $this->token;
        $params = ['token' => $this->token];
        
        $persistence = DB::getPersistence();
         
        $result = $persistence->create($item, $this->storeName);
        
        $this->assertTrue($result, 'Error creating the dummy user');
        
        
        $result = Auth::isValid($params);
        $this->assertTrue($result, 'Error validating the user');
    }
}
