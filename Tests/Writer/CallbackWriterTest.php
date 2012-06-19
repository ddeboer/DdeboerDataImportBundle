<?php

namespace Ddeboer\DataImportBundle\Tests\Writer;

use Ddeboer\DataImportBundle\Writer\CallbackWriter;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class CallbackWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testPrepare()
    {
        $callable = function(array $item, array $originalItem) {
            return '';
        };

        $writer = new CallbackWriter($callable);
        $this->assertEquals($writer, $writer->prepare());
    }

    public function testWriteItem()
    {
        $string = '';
        $callable = function(array $item, array $originalItem) use (&$string) {
            $string = implode(',', array_values($item));
        };

        $writer = new CallbackWriter($callable);
        $this->assertEquals($writer, $writer->writeItem(array('foo' => 'bar', 'bar' => 'foo')));
        $this->assertEquals('bar,foo', $string);
    }

    public function testFinish()
    {
        $callable = function(array $item, array $originalItem) {
            return '';
        };

        $writer = new CallbackWriter($callable);
        $this->assertEquals($writer, $writer->finish());
    }
}
