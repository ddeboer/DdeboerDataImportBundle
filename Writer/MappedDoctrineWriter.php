<?php
namespace Ddeboer\DataImportBundle\Writer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Ddeboer\DataImportBundle\Writer\DoctrineWriter;

class MappedDoctrineWriter extends DoctrineWriter
{
    const DDEBOER_NAMESPACE_URI = 'https://github.com/ddeboer/DdeboerDataImportBundle';
    
    protected $mapping = array();

    public function __construct(EntityManager $entityManager, $entityName, $index = null)
    { 
        parent::__construct($entityManager, $entityName, $index);
        $this->initMetadata();
    }

    protected function initMetadata()
    {
        $em = $this->entityManager;
        $configuration = $em->getConfiguration();

        $meta = $em->getClassMetadata($this->entityName);
        $drivers = $configuration->getMetadataDriverImpl();
        $mapping = array();

        foreach ($drivers->getDrivers() as $driver) {
            if ($driver instanceof XmlDriver && $meta->name === $this->entityName) {
                $xml = $driver->getElement($meta->name);
                $xmlDoctrine = $xml;
                $xml = $xml->children(self::DDEBOER_NAMESPACE_URI);
                if ($xmlDoctrine->getName() == 'entity' 
                        || $xmlDoctrine->getName() == 'mapped-superclass') {
                    if (isset($xmlDoctrine->field)) {
                        foreach ($xmlDoctrine->field as $element) {
                            $children = $element->children(self::DDEBOER_NAMESPACE_URI);
                            if (count($children) == 1 && $this->_isAttributeSet($children[0], 'name')) {
                                $field = $this->_getAttribute($children[0], 'name');
                                $this->mapping[$this->_getAttribute($element, 'name')] = $field;
                            }
                        }
                    }
                }
            }
        }
    }
    
    protected function updateItem($entity, $item)
    {
        foreach ($item as $key => $value) {
            
            if (in_array($key, $this->mapping)) {
                
                $field = array_search($key, $this->mapping);
                $setter = 'set' . ucfirst($field);
                if (method_exists($entity, $setter)) {
                    $entity->{$setter}($value);
                }
            }
        }
    }    

    protected function _isAttributeSet(\SimpleXmlElement $node, $attributeName)
    {
        $attributes = $node->attributes();
        return isset($attributes[$attributeName]);
    }

    protected function _getAttribute(\SimpleXmlElement $node, $attributeName)
    {
        $attributes = $node->attributes();
        return (string) $attributes[$attributeName];
    }
}
