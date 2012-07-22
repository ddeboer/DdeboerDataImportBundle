<?php

namespace Ddeboer\DataImportBundle;

interface SourceInterface
{
    /**
     * @return \SplFileObject
     */
    function getFile();
}