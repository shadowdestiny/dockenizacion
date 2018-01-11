<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\Query\ResultSetMapping;

class ChristmasAwardsRepository extends RepositoryBase
{
    public function getTicketAwardedPrize($ticketNumber)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('number', 'number');
        $rsm->addScalarResult('prize', 'prize');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT id, number, prize
                    FROM christmas_awards
                    WHERE number = '.$ticketNumber, $rsm)->getResult();
    }
}