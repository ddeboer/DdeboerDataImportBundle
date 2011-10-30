<?php

namespace Ddeboer\DataImportBundle\Tests\Source\Filter;

use Ddeboer\DataImportBundle\Source\Filter\Uncompress;

class UncompressTest extends \PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $uncompress = new Uncompress();
        
        $tempFile = tempnam(null, null);
        copy(__DIR__ . '/../../Fixtures/uncompress.txt.Z', $tempFile . '.Z');
        $file = $uncompress->filter(new \SplFileObject($tempFile));

        $this->assertInstanceOf('\SplFileObject', $file);
        $this->assertEquals('This is a test file', file_get_contents($file));
    }
}