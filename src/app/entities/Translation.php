<?php
namespace app\entities;

class Translation
{
    protected $translation_id;
    protected $key;
    protected $used;

    public function getId()
    {
        return $this->translation_id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key= $key;
    }

    public function getUsed()
    {
        return $this->used;
    }

    public function setUsed($used)
    {
        $this->used = $used;
    }
}