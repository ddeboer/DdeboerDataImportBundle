<?php

namespace Ddeboer\DataImportBundle\Filter;

use Ddeboer\DataImportBundle\Filter;

/**
 * Filters using a callback
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class CallbackFilter implements Filter
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * Constructor
     *
     * @param callable $callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \RuntimeException('$callback must be callable');
        }

        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function filter(array $item)
    {
        return call_user_func($this->callback, $item);
    }
}