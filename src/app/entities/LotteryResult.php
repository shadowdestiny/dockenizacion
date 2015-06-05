<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;

abstract class LotteryResult extends EntityBase implements IEntity
{
    protected $id;
    protected $draw;

    public function getDraw()
    {
        return $this->draw;
    }

    public function getId()
    {
        return $this->id;
    }
}