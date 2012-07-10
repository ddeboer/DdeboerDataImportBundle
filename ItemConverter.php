<?php

namespace Ddeboer\DataImportBundle;

/**
 * An item converter takes an input item from a reader, and returns a modified item.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
interface ItemConverter
{
    /**
     * Convert an input
     *
     * @param array $input Input
     *
     * @return array|null the modified input or null to remove it
     */
    function convert(array $input);
}
