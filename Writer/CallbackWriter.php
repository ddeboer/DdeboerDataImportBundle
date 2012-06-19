<?php

namespace Ddeboer\DataImportBundle\Writer;

use Ddeboer\DataImportBundle\Writer;

/**
 * Writes using a callback or closure
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class CallbackWriter implements Writer
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
    public function prepare()
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function writeItem(array $item, array $originalItem = array())
    {
        call_user_func($this->callback, $item, $originalItem);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function finish()
    {
        return $this;
    }
}
