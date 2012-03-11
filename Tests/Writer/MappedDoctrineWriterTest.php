<?php
namespace Ddeboer\DataImportBundle\Tests\Writer;

use Ddeboer\DataImportBundle\Writer\DoctrineWriter;
use Ddeboer\DataImportBundle\Tests\Fixtures\TestEntity;

class MappedDoctrineWriterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testWriteItem()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(array('getRepository', 'getClassMetadata', 'persist'))
            ->disableOriginalConstructor()
            ->getMock();
    
        $repo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
    
        $metadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->setMethods(array('getName', 'getFieldNames', 'setFieldValue'))
            ->disableOriginalConstructor()
            ->getMock();
    }
}
