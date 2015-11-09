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

}