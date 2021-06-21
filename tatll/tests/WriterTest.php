<?php

// use CORE\classes\WebAddress;
use PHPUnit\Framework\TestCase;
require_once '/home/evan/projects/tatll/core/classes/Writer.php';
require_once 'core/config/linksFlatFileConfig.php';
class WriterTest extends TestCase
{
    public function testNothing(): void
    {
        $this->assertTrue(true);
    }


    public function testFileAppendsSomefile() {
        $file = APACHE_FLATFILE;
        $now = date('Y-m-d H:i:s', time());
        $testLine = "phpunit " . $now . "\n";
        $handle = new Writer(APACHE_FLATFILE);
        $fileGrowth = $handle->appendLine($testLine);
        $this->assertGreaterThan(0, $fileGrowth);
        $testWordFoundAt = strpos($handle->getAllLines(), $now);
        // checks for timestamp presence
        $this->assertGreaterThan(0, $testWordFoundAt);
    }
}
