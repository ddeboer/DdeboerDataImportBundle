<?php
namespace Ddeboer\DataImportBundle\Reader;

use Doctrine\DBAL\Connection;
use Ddeboer\DataImportBundle\Reader;

abstract class DbalReader implements Reader
{
    protected $connection;
    protected $schemaManager;
    protected $table;
    protected $data;
    
    public function __construct(Connection $connection, $table) 
    {
        $this->connection = $connection;
        $this->schemaManager = $connection->getSchemaManager();
        $this->table = $table;
        $this->loadData();
    }
    
    abstract function loadData();

    public function getFields()
    {
        $fields = array();
        
        foreach ($this->schemaManager->listTableColumns($this->table) as $column) {
            $fields[] = $column->getName();
        }
        
        return $fields;
    }
    
    public function current()
    {
        return current($this->data);
    }
    
    public function next()
    {
        next($this->data);
    }
    
    public function key()
    {
        return key($this->data);
    }
    
    public function valid()
    {
        $key = key($this->data);
        return ($key !== null && $key !== false);
    }
    
    public function rewind()
    {
        reset($this->data);
    }    
}
