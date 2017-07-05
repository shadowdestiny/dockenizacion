<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\Query\ResultSetMapping;

class ChristmasTicketsRepository extends RepositoryBase
{
    public function getAvailableTickets()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('number', 'number');
        $rsm->addScalarResult('n_fractions', 'n_fractions');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT id, number, n_fractions
                    FROM christmas_tickets
                    WHERE n_fractions > 0', $rsm)->getResult();
    }
}