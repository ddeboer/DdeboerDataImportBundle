<?php

namespace Ddeboer\DataImportBundle\Source;

/**
 * Filters, e.g., uncompresses, a source file
 *
 * @author David de Boer <david@ddeboer.nl>
 */
interface SourceFilter
{
    /**
     * Filter the source file
     *
     * @param \SplFileObject $file Original source file
     * @return \SplFileObject      Filtered source file
     */
    function filter(\SplFileObject $file);
}