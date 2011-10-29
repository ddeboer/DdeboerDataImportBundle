<?php

namespace Ddeboer\DataImportBundle\Converter;

use Ddeboer\DataImportBundle\Converter;

/**
 * Convert an input value to a PHP \DateTime object
 *
 */
class DateTimeConverter implements Converter
{
    /**
     * Date time format
     *
     * @var string
     * @see http://php.net/manual/en/datetime.createfromformat.php
     */
    protected $format;

    /**
     * Construct a DateTime converter
     *
     * @param string $format    Optional
     */
    public function __construct($format = null)
    {
        $this->format = $format;
    }

    /**
     * Convert string to date time object
     *
     * @param string $input
     * @return \DateTime
     */
    public function convert($input)
    {
        if (!$input) {
            return;
        }

        if ($this->format) {
            return \DateTime::createFromFormat($this->format, $input);
        }
        return new \DateTime($input);
    }
}
