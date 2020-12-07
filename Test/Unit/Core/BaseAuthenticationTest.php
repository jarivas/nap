<?php declare(strict_types=1);


namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Configuration as CoreConfig;
use Core\BaseAuthentication as Auth;
use Core\Db\Persistence as DB;


class BaseAuthenticationTest extends TestCase {
    private static $storeName = 'user';
    
    private $item = ['name' => 'Jose', 'age' => 37];
    
    private $token = "t1235";
    
    private $iniFile = ROOT_DIR . 'Test/config/config.ini';
    
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
        
        list($result, $message) = CoreConfig::init($this->iniFile);
        
        $this->assertTrue($result, "Error on config init: $message");
        
        $persistence = DB::getPersistence();
        
        $criteria = [
            '_id' => [DB::CRITERIA_AND, DB::CRITERIA_NOT_EQUAL, 0]
        ];
        
        $result = $persistence->delete($criteria, self::$storeName);
        
        $this->assertTrue($result, 'Error on cleaning db');
        
        $auth = ['token' => $this->token];
        
        $result = Auth::isValid($auth);
        
        $this->assertFalse($result, 'This should not be true');   
    }
    
    
    /**
     * @depends testIsValidRandomToken
     */ 
    public function testIsValidRightToken(): void
    {
        
        $item = $this->item;
        $item['token'] = $this->token;
        $auth = ['token' => $this->token];
        
        $persistence = DB::getPersistence();
         
        $result = $persistence->create($item, self::$storeName);
        
        $this->assertTrue($result, 'Error creating the dummy user');
        
        
        $result = Auth::isValid($auth);
        $this->assertTrue($result, 'Error validating the user');
    }

    public static function tearDownAfterClass(): void {        
        $persistence = DB::getPersistence();
        $criteria = [
            '_id' => [DB::CRITERIA_AND, DB::CRITERIA_NOT_EQUAL, 0]
        ];

        $persistence->delete($criteria, 'user');
    }
}
