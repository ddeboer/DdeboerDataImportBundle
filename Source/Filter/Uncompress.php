<?php

namespace Ddeboer\DataImportBundle\Source\Filter;

use Ddeboer\DataImportBundle\Source\SourceFilter;

/**
 * Provide uncompression for LZW-compressed files (.Z files)
 *
 * .Z files cannot be uncompressed using native PHP tools, so we’ll have to
 * resort to the Linux command line.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class Uncompress implements SourceFilter
{
    private $target;
    private $filename;
    private $zcatBinaryPath = 'zcat';

    public function __construct($target = null)
    {
        $this->target = $target;
    }

    public function getZcatBinaryPath()
    {
        return $this->zcatBinaryPath;
    }

    public function setZcatBinaryPath($zcatBinaryPath)
    {
        $this->zcatBinaryPath = $zcatBinaryPath;
    }

    /**
     *
     * @param \SplFileObject $file
     * @return \SplFileObject
     */
    public function filter(\SplFileObject $file)
    {
        $target = $this->target ? $this->target : tempnam(null, null);

        // Add -f flag to skip confirmation
        $cmd = exec($this->zcatBinaryPath . ' -f '
             . escapeshellarg($file->getPathname()) . ' > '
             . escapeshellarg($target), $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Error occurred: ' . implode(', ', $output));
        }

        return new \SplFileObject($target);
    }
}