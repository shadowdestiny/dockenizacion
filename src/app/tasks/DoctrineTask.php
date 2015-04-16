<?php
namespace app\tasks;
use Phalcon\CLI\Task;

class DoctrineTask extends Task
{
    public function getEntityManagerAction()
    {
        global $entityManager;
        $entityManager = $this->getDI()->get('entityManager');
    }
}