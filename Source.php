<?php

namespace Ddeboer\DataImportBundle;

interface Source
{
    /**
     * @return \SplFileObject
     */
    function getFile();
}