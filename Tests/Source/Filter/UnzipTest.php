<?php

namespace Ddeboer\DataImportBundle\Tests\Source\Filter;

use Ddeboer\DataImportBundle\Source\Filter\Unzip;

class UnzipTest extends \PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $file = new \SplFileObject(__DIR__.'/../../Fixtures/unzip.zip');
        $filter = new Unzip('file');
        $output = $filter->filter($file);
        $this->assertEquals('test', file_get_contents($output->getPathname()));
    }
}