<?php

namespace Ddeboer\DataImportBundle\Tests;

use Ddeboer\DataImportBundle\Workflow;
use Ddeboer\DataImportBundle\Filter\CallbackFilter;
use Ddeboer\DataImportBundle\ValueConverter\CallbackValueConverter;
use Ddeboer\DataImportBundle\ItemConverter\CallbackItemConverter;
use Ddeboer\DataImportBundle\Writer\CallbackWriter;

class WorkflowTest extends \PHPUnit_Framework_TestCase
{
    public function testAddCallbackFilter()
    {
        $this->getWorkflow()->addFilter(new CallbackFilter(function($input) {
            return true;
        }));
    }

    public function testAddCallbackValueConverter()
    {
        $this->getWorkflow()->addValueConverter('someField', new CallbackValueConverter(function($input) {
            return str_replace('-', '', $input);
        }));
    }


    public function testAddCallbackItemConverter()
    {
        $this->getWorkflow()->addItemConverter(new CallbackItemConverter(function(array $input) {
            foreach ($input as $k=>$v) {
                if (!$v) {
                    unset($input[$k]);
                }
            }
            return $input;
        }));
    }    

    public function testAddCallbackWriter()
    {
        $this->getWorkflow()->addWriter(new CallbackWriter(function($item) {
            var_dump($item);
        }));
    }

    protected function getWorkflow()
    {
        $reader = $this->getMockBuilder('\Ddeboer\DataImportBundle\Reader\CsvReader')
            ->disableOriginalConstructor()
            ->getMock();

        return new Workflow($reader);
    }
}