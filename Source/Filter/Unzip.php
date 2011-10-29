<?php

namespace Ddeboer\DataImportBundle\Source\Filter;

class Unzip
{
    private $target;
    private $filename;
    
    public function __construct($filename, $target = null)
    {
        $this->filename = $filename;
    }

    public function filter(\SplFileObject $file)
    {
        $zip = new \ZipArchive();
        $zip->open($file->getPathname());
        $target = $this->target ? $this->target : sys_get_temp_dir();
        $zip->extractTo($target);
        
        return new \SplFileObject($target  . '/' . $this->filename);
    }
}