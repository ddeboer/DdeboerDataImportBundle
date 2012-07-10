<?php

namespace Ddeboer\DataImportBundle\Tests\ValueConverter;

use Ddeboer\DataImportBundle\ValueConverter\CallbackValueConverter;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class CallbackValueConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $callable = function($item) {
            return implode(',', $item);
        };

        $converter = new CallbackValueConverter($callable);
        $this->assertEquals('foo,bar', $converter->convert(array('foo', 'bar')));
    }
}
