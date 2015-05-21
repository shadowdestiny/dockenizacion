<?php
namespace EuroMillions\tasks;
use EuroMillions\entities\Language;
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

    public function InsertTestAction($params)
    {
        $billing_provider = new Language();
        $billing_provider->setName($params[0]);

        /** @var $em EntityManager */
        $em = $this->getDI()->get('entityManager');
        $em->persist($billing_provider);
        $em->flush();

        echo "created billing provider with ID ".$billing_provider->getId()."\n";
    }

    public function ListTestAction()
    {
        $billing_provider_repository = $this->entityManager->getRepository('\EuroMillions\entities\BillingProvider');
        $billing_providers = $billing_provider_repository->findAll();
        foreach ($billing_providers as $bp) {
            echo sprintf("-%s\n", $bp->getName());
        }
    }
}