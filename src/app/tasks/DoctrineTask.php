<?php
namespace EuroMillions\tasks;
use Doctrine\ORM\EntityManager;
use Phalcon\CLI\Task;

class DoctrineTask extends Task
{
    /** @var  EntityManager */
    protected $entityManager;

    public function initialize()
    {
        $this->entityManager = $this->getDI()->get('entityManager');
    }

    public function getEntityManagerAction()
    {
        global $entityManager;
        $entityManager = $this->getDI()->get('entityManager');
    }
}
