<?php declare(strict_types=1);


namespace Test\Unit\Core;

use PHPUnit\Framework\TestCase;
use Core\Logger;

class LoggerTest extends TestCase{
    
    public function testCanLog(): void
    {
        
        $result = Logger::canLog();
        
        $this->assertTrue($result, 'Problem with log permission');
    }
    
    /**
     * @depends testCanLog
     */ 
    public function testLog(): void
    {
        $time = time();
        
        sleep(1); //otherwise will be executed in the same sec
        
        $result = Logger::debug('This is a test');
        
        $this->assertTrue($result, 'Problem with log write permission');
        
        $logFile = ROOT_DIR . 'log/' . Logger::DEBUG . '.log';
        
        $fileTime = filemtime($logFile);
        
        $this->assertGreaterThan($time, $fileTime, 'File was not modified');
    }
}
