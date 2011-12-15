<?php

namespace Ddeboer\DataImportBundle\Source\Filter;

use Ddeboer\DataImportBundle\Source\SourceFilter;

class Unzip implements SourceFilter
{
    private $target;
    private $filename;

    /**
     * Construnct unzip filter
     *
     * @param string $filename  The filename in the zip file to return
     * @param string $target    Target directory
     */
    public function __construct($filename, $target = null)
    {
        $this->filename = $filename;
        $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(\SplFileObject $file)
    {
        $zip = new \ZipArchive();
        $zip->open($file->getPathname());
        $target = $this->target ? $this->target : sys_get_temp_dir();
        $zip->extractTo($target);
        
        return new \SplFileObject($target  . '/' . $this->filename);
    }
}