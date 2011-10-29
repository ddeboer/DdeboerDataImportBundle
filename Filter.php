<?php

namespace Ddeboer\DataImportBundle;

interface Filter
{
    /**
     * @return boolean
     */
    function filter(array $item);
}