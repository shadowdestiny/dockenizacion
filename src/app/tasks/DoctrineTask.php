<?php
namespace app\tasks;
use Phalcon\CLI\Task;

class DoctrineTask extends Task
{
    public function getEntityManagerAction()
    {
        return $this->getDI()->get('entityManager');
    }
}