<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\vo\UserId;
use Rhumsaa\Uuid\Uuid;

class GuestUser extends EntityBase implements IEntity,IUser
{
    /** @var  Uuid */
    protected $id;

    public function setId(UserId $id)
    {
        $this->id = $id;
    }

    /**
     * @return UserId
     */
    public function getId()
    {
        return $this->id;
    }
}