<?php

namespace Ddeboer\DataImportBundle;

/**
 * A filter decides whether an item is accepted into the import workflow
 */
interface Filter
{
    /**
     * @return boolean
     */
    function filter(array $item);
}