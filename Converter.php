<?php

namespace Ddeboer\DataImportBundle;

/**
 * A converter takes an input value from a reader, and returns a converted value
 *
 * The conversion can consists in mere filtering, but it is also possible to
 * do lookups, or give back specific objects.
 */
interface Converter
{
    /**
     * @return mixed
     */
    function convert($input);
}