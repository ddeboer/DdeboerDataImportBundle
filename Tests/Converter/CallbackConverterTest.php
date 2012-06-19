<?php

namespace Ddeboer\DataImportBundle\Tests\Converter;

use Ddeboer\DataImportBundle\Converter\CallbackConverter;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class CallbackConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $callable = function($item) {
            return implode(',', $item);
        };

        $converter = new CallbackConverter($callable);
        $this->assertEquals('foo,bar', $converter->convert(array('foo', 'bar')));
    }
}
