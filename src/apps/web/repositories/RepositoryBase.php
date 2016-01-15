<?php


namespace EuroMillions\web\repositories;


use Doctrine\ORM\EntityRepository;
use EuroMillions\web\entities\EntityBase;

class RepositoryBase extends EntityRepository
{

    /**
     * @param EntityBase $entity
     */
    public function add(EntityBase $entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    public function addWithId(EntityBase $entity)
    {
        $this->getEntityManager()->getClassMetadata(get_class($entity))->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        $this->getEntityManager()->persist($entity);
    }


}