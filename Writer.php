<?php

namespace Ddeboer\DataImportBundle;

/**
 * Persists data in a storage medium, such as a database, XML file, etc.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
abstract class Writer
{
    /**
     * Prepare the writer before writing the items
     *
     * This template method can be overridden in concrete writer
     * implementations.
     *
     * @return Writer
     */
    public function prepare() {}
    
    /**
     * Write one data item
     *
     * @param array $item         The data item with converted values
     * @param array $originalItem The data item with its original values
     *
     * @return Writer
     */
    abstract public function writeItem(array $item, array $originalItem = array());
    
    /**
     * Wrap up the writer after all items have been written
     *
     * This template method can be overridden in concrete writer
     * implementations.
     *
     * @return Writer
     */
    public function finish() {}
}
