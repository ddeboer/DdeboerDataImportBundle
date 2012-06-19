<?php

namespace Ddeboer\DataImportBundle\Tests;

use Ddeboer\DataImportBundle\Workflow;
use Ddeboer\DataImportBundle\Filter\CallbackFilter;
use Ddeboer\DataImportBundle\Converter\CallbackConverter;
use Ddeboer\DataImportBundle\Writer\CallbackWriter;

class WorkflowTest extends \PHPUnit_Framework_TestCase
{
    public function testAddCallbackFilter()
    {
        $this->getWorkflow()->addFilter(new CallbackFilter(function($input) {
            return true;
        }));
    }

    public function testAddCallbackConverter()
    {
        $this->getWorkflow()->addConverter('someField', new CallbackConverter(function($input) {
            return str_replace('-', '', $input);
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