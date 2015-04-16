<?php
namespace tests\base;

class TestBaseHelper
{
    public function getIdsFromArrayOfObjects(array $objects)
    {
        $result = array();
        foreach ($objects as $object) {
            $result[] = $object->getId();
        }
        return $result;
    }
}