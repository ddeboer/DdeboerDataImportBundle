<?php

namespace Ddeboer\DataImportBundle\Tests\Converter;

use Ddeboer\DataImportBundle\Converter\DateTimeConverter;

class DateTimeConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertWithoutFormat()
    {
        $value = '2011-10-20 13:05';
        $converter = new DateTimeConverter;
        $output = $converter->convert($value);
        $this->assertInstanceOf('\DateTime', $output);
        $this->assertEquals('13', $output->format('H'));
    }

    public function testConvertWithFormat()
    {
        $value = '14/10/2008 09:40:20';
        $converter = new DateTimeConverter('d/m/Y H:i:s');
        $output = $converter->convert($value);
        $this->assertInstanceOf('\DateTime', $output);
        $this->assertEquals('20', $output->format('s'));
    }
}