<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\Query\ResultSetMapping;

class ChristmasTicketsRepository extends RepositoryBase
{
    public function getAvailableTickets()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('number', 'number');
        $rsm->addScalarResult('n_fractions', 'n_fractions');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT id, number, n_fractions
                    FROM christmas_tickets
                    WHERE n_fractions > 0', $rsm)->getResult();
    }

    public function insertTicket(array $ticket)
    {
        $this->getEntityManager()->getConnection()->executeQuery("
            INSERT INTO christmas_tickets VALUES (NULL, '" . $ticket[0] . "', '1', " . $ticket[1] . ", " . $ticket[1] . ", 10, 1, 10)
            ");
    }

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