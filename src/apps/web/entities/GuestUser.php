<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IUser;

class GuestUser extends EntityBase implements IEntity,IUser
{
    protected $id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}