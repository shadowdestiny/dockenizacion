<?php
namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityRepository;

class LotteryRepository extends EntityRepository
{
    public function getLotteryByName($lotteryName)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT l'
                .' FROM '.$this->getEntityName().' l '
                .' WHERE l.name = :name')
            ->setMaxResults(1)
            ->setParameters(['name' => $lotteryName])
            ->useResultCache(true)
            ->getResult();
        return !empty($result) ? $result[0] : null;
    }

}