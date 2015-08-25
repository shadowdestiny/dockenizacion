<?php


namespace tests\base;

use EuroMillions\config\Namespaces;

trait TestHelperTrait
{
    protected function getServiceDouble($serviceName)
    {
        return $this->prophesize(Namespaces::SERVICES_NS . $serviceName);
    }

    protected function getRepositoryDouble($repositoryName)
    {
        return $this->prophesize(Namespaces::REPOSITORIES_NS . $repositoryName);
    }

    protected function getInterfaceDouble($interfaceName)
    {
        return $this->prophesize(Namespaces::INTERFACES_NS . $interfaceName);
    }

    protected function getEntityDouble($entityName)
    {
        return $this->prophesize(Namespaces::ENTITIES_NS . $entityName);
    }

    protected function getComponentDouble($componentName)
    {
        return $this->prophesize(Namespaces::COMPONENTS_NS . $componentName);
    }

    protected function getValueObjectDouble($valueObjectName)
    {
        return $this->prophesize(Namespaces::VALUEOBJECTS_NS . $valueObjectName);
    }

    public function getIdsFromArrayOfObjects(array $objects)
    {
        $result = array();
        foreach ($objects as $object) {
            $result[] = $object->getId();
        }
        return $result;
    }

}