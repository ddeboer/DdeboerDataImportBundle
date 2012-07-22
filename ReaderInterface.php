<?php

namespace Ddeboer\DataImportBundle;

/**
 * Iterator that reads data to be imported
 *
 * @author David de Boer <david@ddeboer.nl>
 */
interface ReaderInterface extends \Iterator
{
    /**
     * @return array
     */
    function getFields();
}