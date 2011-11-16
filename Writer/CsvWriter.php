<?php

namespace Ddeboer\DataImportBundle\Writer;

use Ddeboer\DataImportBundle\Writer;

/**
 * Writes to a CSV file
 *
 */
class CsvWriter extends Writer
{
    private $delimiter = ';';
    private $enclosure = '"';

    public function __construct(\SplFileObject $file)
    {
        $this->fp = fopen($file->getPathname(), 'w');
    }

    /**
     * Write a row to the CSV file
     *
     * Every cell value is encoded in an Excel-compatible way
     *
     * @param array $cells    Array of column values or header names
     */
    public function writeItem(array $item, array $originalItem = array())
    {
//        foreach ($cells as &$cell) {
//            $cell = iconv('UTF-8', 'Windows-1252', $cell);
//        }
        fputcsv($this->fp, $item, $this->delimiter, $this->enclosure);
    }

    public function __destruct()
    {
        fclose($this->fp);
    }
}