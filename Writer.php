<?php

namespace Ddeboer\DataImportBundle;

/**
 * Persists data in a storage medium, such as a database, XML file, etc.
 * 
 */
interface Writer
{
    /**
     * Prepare the writer 
     * 
     */
    public function prepare();
    
    /**
     * Write one data item
     *
     * @param array $item         The data item with converted values
     * @param array $originalItem The data item with its original values
     */
    public function writeItem(array $item, array $originalItem = array());
    
    /**
     * Wrap up the writer
     * 
     */
    public function finish();
}