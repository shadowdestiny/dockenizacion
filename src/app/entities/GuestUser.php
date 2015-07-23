<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;
use EuroMillions\interfaces\IUser;
use EuroMillions\vo\UserId;
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