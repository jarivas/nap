<?php

declare(strict_types=1);

namespace Test\Unit\Core\Db;

use PHPUnit\Framework\TestCase;
use Core\Configuration as CoreConfig;
use Core\Db\Persistence as DB;

final class PersitenceTest extends TestCase {

    private static $storeName = 'user';
    private $item = ['name' => 'Jose', 'age' => 37];
    private $iniFile = ROOT_DIR . 'Test/config/config.ini';

    public function testConfiguration(): void {
        list($result, $message) = CoreConfig::init($this->iniFile);

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
    public function testGetPersitence(): void {
        $persistence = DB::getPersistence();

        $this->assertIsObject($persistence, "Error getting persistence");
    }

    /**
     * @depends testGetPersitence
     */
    public function testDeleteAll(): void {
        $persistence = DB::getPersistence();
        $criteria = [
            '_id' => [DB::CRITERIA_AND, DB::CRITERIA_NOT_EQUAL, 0]
        ];

        $result = $persistence->delete($criteria, self::$storeName);

        $this->assertTrue($result, 'Error on deleteAll');
    }

    /**
     * @depends testDeleteAll
     */
    public function testReadAll(): void {
        $persistence = DB::getPersistence();
        $criteria = [
            '_id' => [DB::CRITERIA_AND, DB::CRITERIA_NOT_EQUAL, 0]
        ];

        $result = $persistence->read($criteria, self::$storeName);

        $this->assertNull($result, print_r($result, true));
    }

    /**
     * @depends testReadAll
     */
    public function testCreate(): void {
        $persistence = DB::getPersistence();

        $result = $persistence->create($this->item, self::$storeName);

        $this->assertTrue($result, 'Error on create');

        for ($i = 0; $i < 10; ++$i) {
            $item = $this->item;
            $item['name'] .= strval($i);

            $result = $persistence->create($item, self::$storeName);

            $this->assertTrue($result, 'Error on create multiple');
        }
    }

    /**
     * @depends testCreate
     */
    public function testRead(): void {
        $persistence = DB::getPersistence();
        $criteria = [
            'name' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $this->item['name']]
        ];

        $result = $persistence->readOne($criteria, self::$storeName);

        $this->assertIsArray($result, 'Error on read');
        $this->assertArrayHasKey('name', $result, 'Error on readOne, invalid result');
        $this->assertSame($this->item['name'], $result['name'], 'Error on read, item returned is different');
    }

    public function testUpdate(): void {
        $persistence = DB::getPersistence();
        $criteria = [
            'name' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $this->item['name']]
        ];

        $updatedItem = $this->item;
        $updatedItem['name'] = 'Paolo';

        $result = $persistence->update($criteria, $updatedItem, self::$storeName);

        $this->assertTrue($result, 'Error on update');

        $criteria = [
            'name' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $updatedItem['name']]
        ];

        $result = $persistence->readOne($criteria, self::$storeName);

        $this->assertIsArray($result, 'Error on update, reading after update problem');
        $this->assertArrayHasKey('name', $result, 'Error on update, invalid result');
        $this->assertSame($updatedItem['name'], $result['name'], 'Error on update, item was never updated');
    }

    public static function tearDownAfterClass(): void {        
        $persistence = DB::getPersistence();
        $criteria = [
            '_id' => [DB::CRITERIA_AND, DB::CRITERIA_NOT_EQUAL, 0]
        ];

        $result = $persistence->delete($criteria, 'user');
    }

}
