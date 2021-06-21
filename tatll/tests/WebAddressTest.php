<?php

// use CORE\classes\WebAddress;
use PHPUnit\Framework\TestCase;
require_once '/home/evan/projects/tatll/core/classes/WebAddress.php';

class LongUrlTest extends TestCase
{
    public function test(): void
    {
        $this->assertTrue(true);
    }


    public function testMakesALink() {
        $testurl = 'https://example.com/' 
        . '/' . (string) random_int(100000, 999999)
        . '/' . (string) random_int(100000, 999999);
        $wA = new WebAddress($testurl);
        $this->assertIsString($wA->getLong());
        $wA->shortify();
        $this->assertEquals(8, strlen($wA->getShort()));
    }

    public function testForLinkCollisions(){
        
        $repetitions = 10000;
        $i = $repetitions;
        $shorties = []; 
        $testUrl = '';
        while ($i > 0){
            $testUrl = 'https://example.com/' 
            . '/' . (string) random_int(100000, 999999)
            . '/' . (string) random_int(100000, 999999);
            $wA = new WebAddress($testUrl);
            $wA->shortify();
            $shortUrl = $wA->getShort();
            $shorties[] = $shortUrl;
            $stoppedAt = 'Stopped at ' . $i. ' and last testUrl was ' . $testUrl;
            echo "testurl: " . $shortUrl . "one of the shorties: " . $shorties[0] . "\n";
            $this->assertNotContains($testUrl , $shorties, $stoppedAt);
            $i--;
        }
    } 
}
