<?php


namespace EuroMillions\web\repositories;


class TcActionsRepository extends RepositoryBase
{
    public function cloneActionsByLastTCAndNewTC($lastTcId, $newTcId)
    {
        $rsm = new ResultSetMapping();
        $this->getEntityManager()
            ->createNativeQuery(
                'INSERT INTO tc_actions (name, conditions, function_name, relationship_table, trackingCode_id)
                SELECT ta.name, ta.conditions, ta.function_name, ta.relationship_table, ' . $newTcId . '
                FROM tc_attributes ta
                WHERE ta.trackingCode_id = ' . $lastTcId, $rsm)->getResult();
    }
}