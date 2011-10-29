<?php

namespace Ddeboer\DataImportBundle\Source;
use Ddeboer\DataImportBundle\Source;

class Http implements Source
{
    protected $filters = array();

    public function __construct($url, $username = null, $password = null)
    {
        $this->url = $url;
        if ($username && $password) {
            $this->setAuthentication($username, $password);
        }
    }

    public function setAuthentication($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     *
     * @return \SplFileObject 
     */
    public function getFile()
    {
        $temp = tempnam('/tmp', 'data_import');
        file_put_contents($temp, file_get_contents($this->url));

        $file = new \SplFileObject($temp);
        foreach ($this->filters as $filter) {
            $file = $filter->filter($file);
        }

        return $file;
    }

    public function addFilter($filter)
    {
        $this->filters[] = $filter;
    }
}