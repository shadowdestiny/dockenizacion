<?php


namespace EuroMillions\web\repositories;

use Doctrine\ORM\AbstractQuery;
use Phalcon\Mvc\Model\Query;

class MatcherRepository extends RepositoryBase
{


    public function fetchRaffleMillionByDrawDate(\DateTime $date)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT m '
                . ' FROM ' . $this->getEntityName() . ' m'
                . ' WHERE m.drawDate = :date')
            ->setParameters([
                'date' => $date
            ])
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
        return $result;
    }



}