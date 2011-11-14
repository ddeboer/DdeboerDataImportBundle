<?php

namespace Ddeboer\DataImportBundle;

/**
 * A mediator between a reader and one or more writers and converters
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class Workflow
{
    /**
     * Reader
     *
     * @var Reader
     */
    protected $reader;

    /**
     * Array of writers
     *
     * @var Writer[]
     */
    protected $writers = array();

    /**
     * Array of converters
     *
     * @var Converter[]
     */
    protected $converters = array();

    /**
     * Array of filters
     *
     * @var Filter[]
     */
    protected $filters = array();

    /**
     * Array of mappings
     *
     * @var array
     */
    protected $mappings = array();

    /**
     * Construct a workflow
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Add a filter to the workflow
     *
     * A filter decides whether an item is accepted into the import process.
     *
     * @param Filter $filter
     * @return Workflow
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Add a filter closure to the workflow
     *
     * A filter decides whether an item is accepted into the import process.
     */
    public function addFilterClosure(\Closure $closure)
    {
        $this->filters[] = $closure;
        return $this;
    }

    /**
     * Add a writer to the workflow
     *
     * A writer takes a filtered and converted item, and writes that to, e.g.,
     * a database or CSV file.
     * 
     * @param Writer $writer
     * @return Workflow
     */
    public function addWriter(Writer $writer)
    {
        $this->writers[] = $writer;
        return $this;
    }

    /**
     * Add a writer closure to the workflow
     * 
     * @param \Closure $closure
     * @return Workflow
     */
    public function addWriterClosure(\Closure $closure)
    {
        $this->writers[] = $closure;
        return $this;
    }

    /**
     * Add a converter to the workflow
     *
     * @param string $field
     * @param type $converter
     * @return Workflow
     */
    public function addConverter($field, Converter $converter)
    {
        $this->converters[$field][] = $converter;
        return $this;
    }

    /**
     * Add a converter closure to the workflow
     *
     * @param string $field
     * @param \Closure $closure
     * @return Workflow
     */
    public function addConverterClosure($field, \Closure $closure)
    {
        $this->converters[$field][] = $closure;
        return $this;
    }

    /**
     * Add a mapping to the workflow
     *
     * If we can get the field names from the reader, they are just to check the
     * $fromField against.
     *
     * @param string $fromField
     * @param string $toField
     * @return Workflow
     */
    public function addMapping($fromField, $toField)
    {
        if (count($this->reader->getFields()) > 0) {
            if (!in_array($fromField, $this->reader->getFields())) {
                throw new \InvalidArgumentException("$fromField is an invalid field");
            }
        }

        $this->mappings[$fromField] = $toField;
        return $this;
    }

    /**
     * Process the whole import workflow
     *
     * 1. Prepare the added writers.
     * 2. Ask the reader for one item at a time.
     * 3. Filter each item.
     * 4. If the filter succeeds, convert the item’s values using the added
     *    converters.
     * 5. Write the item to each of the writers.
     */
    public function process()
    {
        // Prepare writers
        foreach ($this->writers as $writer) {
            if ($writer instanceof Writer) {
                $writer->prepare();
            }
        }

        // Read all items
        foreach ($this->reader as $item) {

            // Filter each item
            if (!$this->filterItem($item)) {
                continue;
            }

            $convertedItem = $this->convertItem($item);
            $mappedItem = $this->mapItem($convertedItem);

            foreach ($this->writers as $writer) {
                if ($writer instanceof Writer) {
                    $writer->writeItem($mappedItem, $item);
                } else {
                    $writer($mappedItem, $item);
                }
            }
        }
    }

    /**
     * Apply the filter chain to the input; if at least one filter fails, the
     * chain fails
     *
     * @param array $item
     * @return boolean
     */
    protected function filterItem(array $item)
    {
        foreach ($this->filters as $filter) {
            if ($filter instanceof Filter) {
                if (false == $filter->filter($item)) {
                    return false;
                }
            } elseif (is_callable($filter)) {
                if (false == $filter($item)) {
                    return false;
                }
            } 
        }

        // Return true if no filters failed
        return true;
    }

    /**
     * Convert the item
     * 
     * @param string $item
     * @return array
     */
    protected function convertItem(array $item)
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

    /**
     * Map an item
     * 
     * @param array $item
     * @return array
     */
    protected function mapItem(array $item)
    {
        foreach ($item as $key => $value) {
            if (isset($this->mappings[$key])) {
                $item[$this->mappings[$key]] = $value;
                unset($item[$key]);
            }
        }
        return $item;
    }
}
