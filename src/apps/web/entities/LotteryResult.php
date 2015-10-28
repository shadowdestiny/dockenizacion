<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

abstract class LotteryResult extends EntityBase implements IEntity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}