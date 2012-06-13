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
     * Array of filters that will be checked after data conversion
     *
     * @var Filter[]
     */
    protected $afterConversionFilters = array();

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
     *
     * @return Workflow
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Add after conversion filter
     *
     * @param Filter $filter
     *
     * @return $this
     */
    public function addFilterAfterConversion(Filter $filter)
    {
        $this->afterConversionFilters[] = $filter;

        return $this;
    }

    /**
     * Add a filter closure to the workflow
     *
     * A filter decides whether an item is accepted into the import process.
     *
     * @param \Closure $closure
     *
     * @return $this
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
     *
     * @return $this
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
     *
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
     * @param string $field     Field
     * @param type   $converter Converter
     *
     * @return $this
     */
    public function addConverter($field, Converter $converter)
    {
        $this->converters[$field][] = $converter;

        return $this;
    }

    /**
     * Add a converter closure to the workflow
     *
     * @param string   $field   Field
     * @param \Closure $closure Closure
     *
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
     * @param string $fromField Field to map from
     * @param string $toField   Field to map to
     *
     * @return $this
     * @throws \InvalidArgumentException
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
     *
     * @return int Number of items processed
     */
    public function process()
    {
        $count = 0;

        // Prepare writers
        foreach ($this->writers as $writer) {
            if ($writer instanceof Writer) {
                $writer->prepare();
            }
        }

        // Read all items
        foreach ($this->reader as $item) {

            // Apply filters before conversion
            $item = $this->filterItem($item, $this->filters);
            if (!is_array($item)) {
                continue;
            }

            $convertedItem = $this->convertItem($item);

            // Apply filters after conversion
            $convertedItem = $this->filterItem($convertedItem, $this->afterConversionFilters);
            if (!is_array($convertedItem) {
                continue;
            }

            $mappedItem = $this->mapItem($convertedItem);

            foreach ($this->writers as $writer) {
                if ($writer instanceof Writer) {
                    $writer->writeItem($mappedItem, $item);
                } else {
                    $writer($mappedItem, $item);
                }
            }

            $count++;
        }

        // Finish writers
        foreach ($this->writers as $writer) {
            if ($writer instanceof Writer) {
                $writer->finish();
            }
        }

        return $count;
    }

    /**
     * Apply the filter chain to the input; if at least one filter fails, the
     * chain fails
     *
     * @param array $item    Item
     * @param array $filters Array of filters
     *
     * @return boolean
     */
    protected function filterItem(array $item, array $filters)
    {
        foreach ($filters as $filter) {
            $result = null;
            if ($filter instanceof Filter) {
                $result = $filter->filter($item);
            } else if (is_callable($filter)) {
                $result = $filter($item);
            }
            if (false === $result) {
                return false;
            } else if (is_array($result)){
                $item = $result;
            }
        }

        // Return the item if no filters failed
        return $item;
    }

    /**
     * Convert the item
     *
     * @param string $item Original item values
     *
     * @return array Converted item values
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
     * @param array $item Item values
     *
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
