<?php

namespace Ddeboer\DataImportBundle;

/**
 * A mediator between a reader and one or more writers
 * 
 */
class Workflow
{
    protected $reader;
    protected $writers = array();
    protected $converters = array();
    
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function addWriter(Writer $writer)
    {
        $this->writers[] = $writer;
    }

    public function addWriterClosure(\Closure $closure)
    {
        $this->writers[] = $closure;
    }

    public function addConverter($field, $converter)
    {
        $this->converters[$field][] = $converter;
        return $this;
    }

    public function addConverterClosure($field, \Closure $closure)
    {
        $this->converters[$field][] = $closure;
        return $this;
    }

    public function process()
    {
        foreach ($this->writers as $writer) {
            $writer->prepare();
        }

        foreach ($this->reader as $item) {
            $item = $this->convertItem($item);

            foreach ($this->writers as $writer) {
                if ($writer instanceof Writer) {
                    $writer->writeLine($item);
                } else {
                    $writer($item);
                }
            }
        }
    }

    protected function convertItem($item)
    {
        foreach ($this->converters as $property => $converters) {
            if (isset($item[$property])) {
                foreach ($converters as $converter) {
                    if ($converter instanceof Converter) {
                        $item[$property] = $converter->convert($item[$property]);
                    } else {
                        $item[$property] = $converter($item[$property]);
                    }
                }
            }
        }

        return $item;
    }
}