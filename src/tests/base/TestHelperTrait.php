<?php


namespace tests\base;

use EuroMillions\shared\config\Namespaces;
use Prophecy\Prophecy\ObjectProphecy;

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

    /**
     * @param $interfaceName
     * @return ObjectProphecy
     */
    protected function getInterfaceDouble($interfaceName)
    {
        return $this->prophesize(Namespaces::INTERFACES_NS . $interfaceName);
    }

    protected function getInterfaceWebDouble($interfaceName)
    {
        return $this->prophesize(Namespaces::INTERFACES_WEB_NS . $interfaceName);
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

    protected function getEntitiesToArgument($entityName)
    {
        return Namespaces::ENTITIES_NS . $entityName;
    }

    protected function getVOToArgument($valueObjectName)
    {
        return Namespaces::VALUEOBJECTS_NS . $valueObjectName;
    }

    protected function getResultObject($resultObjectName)
    {
        return Namespaces::RESULTOBJECTS_NS . $resultObjectName;
    }

    protected function getInterfacesToArgument($interfaceName)
    {
        return Namespaces::INTERFACES_WEB_NS . $interfaceName;
    }

    protected function getExceptionToArgument($exceptionName)
    {
        return 'EuroMillions\web\exceptions\\' . $exceptionName;
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