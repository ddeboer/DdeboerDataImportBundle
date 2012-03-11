<?php
namespace Ddeboer\DataImportBundle\Mapping;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class MetadataListener
{
    const DDEBOER_NAMESPACE_URI = 'http://gediminasm.org/schemas/orm/doctrine-extensions-mapping';
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $meta = $eventArgs->getClassMetadata();
        $em = $eventArgs->getEntityManager();
        $configuration = $em->getConfiguration();
        $drivers = $configuration->getMetadataDriverImpl();

        foreach ($drivers->getDrivers() as $driver) {
            
            if ($driver instanceof XmlDriver) {
                
                if ($meta->name === 'Dubture\PierrepetitBundle\Entity\Watch') {
                    $element = $driver->getElement($meta->name);                    
                }
            }
        }
    }
}
