<?php

namespace Ddeboer\DataImportBundle\Converter;

use Ddeboer\DataImportBundle\Converter;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class CallbackConverter implements Converter
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
    public function convert($input)
    {
        return call_user_func($this->callback, $input);
    }


}
