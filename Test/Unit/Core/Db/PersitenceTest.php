<?php declare(strict_types=1);

namespace Test\Unit\Core\Db;

use PHPUnit\Framework\TestCase;
use Core\Configuration as CoreConfig;
use Core\Db\Persistence as CoreDb;

final class PersitenceTest extends TestCase {
    
    /**
     *
     * @var Persistence 
     */
    private $persistence;
    
    private $storeName = 'test';
    
    private $item = ['name' => 'Jose', 'age' => 37];


    public function testConfiguration(): void
    {
        list($result, $message) = CoreConfig::init();
        
        $this->assertTrue($result, "Error on config init: $message");
        
        $db = CoreConfig::getData('db');
        
        $this->assertIsArray($db, 'DB config is not an array');
        
        $this->assertArrayHasKey('type', $db);
        
        $db = CoreConfig::getData($db['type']);
        
        $this->assertIsArray($db, 'DB_TYPE config is not an array');
    }
    
    /**
     * @depends testConfiguration
     */
    public function testGetPersitence(): void
    {
        $persistence = CoreDb::getPersistence();
        
        $this->assertIsObject($persistence, "Error getting persistence");
        
        $this->persistence = $persistence;
    }
    
    /**
     * @depends testGetPersitence
     */
    public function testDeleteAll(): void
    {
        $result = $this->persistence->delete([], $this->storeName);
        
        $this->assertTrue($result, 'Error on deleteAll');
    }
    
    
    /**
     * @depends testDeleteAll
     */
    public function testCreate(): void
    {
        $result = $this->persistence->create($this->item, $this->storeName);
        
        $this->assertTrue($result, 'Error on create');
    }
    
    
    /**
     * @depends testCreate
     */
    public function testReadOne(): void
    {
        $criteria = ['name' => $this->item['name']];
        
        $result = $this->persistence->readOne($criteria, $this->storeName);
        
        $this->assertIsArray($result, 'Error on readOne');
        $this->assertArrayHasKey('name', $result, 'Error on readOne, invalid result');
    }
}
