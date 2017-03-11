<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

class UserModel
{
    protected $id;
    /** @var string */
    protected $name;
    /** @var string */
    protected $login;
    /** @var \DateTime */
    protected $creAt;
    /** @var bool */
    protected $active;

    public function __construct()
    {
        $this->creAt = new \DateTime;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /** @var string */
    protected $email;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return \DateTime
     */
    public function getCreAt()
    {
        return $this->creAt;
    }

    /**
     * @param \DateTime $creAt
     */
    public function setCreAt(\DateTime $creAt)
    {
        $this->creAt = $creAt;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = (bool)$active;
    }
}
