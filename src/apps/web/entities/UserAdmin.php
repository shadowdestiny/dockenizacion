<?php

namespace EuroMillions\web\entities;

class UserAdmin extends EntityBase
{
    protected $id;
    protected $name;
    protected $surname;
    protected $email;
    protected $password;
    protected $useraccess;

    public function __construct(array $userAdminData)
    {
        $this->name = $userAdminData['name'];
        $this->surname = $userAdminData['surname'];
        $this->email = $userAdminData['email'];
        $this->password = $userAdminData['password'];
        $this->useraccess = $userAdminData['useraccess'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUseraccess()
    {
        return $this->useraccess;
    }

    /**
     * @param mixed $useraccess
     */
    public function setUseraccess($useraccess)
    {
        $this->useraccess = $useraccess;
    }
}