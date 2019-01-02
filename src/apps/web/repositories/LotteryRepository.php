<?php

namespace EuroMillions\web\repositories;

use EuroMillions\web\entities\Lottery;

class LotteryRepository extends RepositoryBase
{
    /**
     * @param $lotteryName
     * @return null|Lottery
     */
    public function getLotteryByName($lotteryName)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT l'
                . ' FROM ' . $this->getEntityName() . ' l '
                . ' WHERE l.name = :name')
            ->setMaxResults(1)
            ->setParameters(['name' => $lotteryName])
            ->useResultCache($this->isCacheEnabled(), 3600)
            ->getResult();
        return !empty($result) ? $result[0] : null;
    }

}