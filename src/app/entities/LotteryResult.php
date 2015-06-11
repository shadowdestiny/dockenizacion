<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;

abstract class LotteryResult extends EntityBase implements IEntity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}