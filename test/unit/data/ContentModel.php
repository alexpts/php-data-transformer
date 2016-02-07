<?php
namespace PTS\DataTransformer;

class ContentModel implements ModelInterface
{
    protected $id;
    /** @var string */
    protected $title;
    /** @var array */
    protected $cats;
    /** @var \DateTime */
    protected $creAt;
    /** @var bool */
    protected $active;
    /** @var string */
    protected $any;
    /** @var float */
    protected $float;
    /** @var int */
    protected $count;
    /** @var UserModel */
    protected $user;
    /** @var UserModel[] */
    protected $prevUsers;
    /** @var ContentModel[] */
    protected $similarContent = [];

    /**
     * @return ContentModel[]
     */
    public function getSimilarContent()
    {
        return $this->similarContent;
    }

    /**
     * @param ContentModel[] $similarContent
     * @return $this
     */
    public function setSimilarContent($similarContent)
    {
        $this->similarContent = $similarContent;
        return $this;
    }


    /**
     * @return UserModel
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserModel $user
     * @return $this
     */
    public function setUser(UserModel $user)
    {
        $this->user = $user;
        return $this;
    }


    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return float
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * @param float $float
     * @return $this
     */
    public function setFloat($float)
    {
        $this->float = $float;
        return $this;
    }

    /**
     * @return string
     */
    public function getAny()
    {
        return $this->any;
    }

    /**
     * @param string $any
     * @return $this
     */
    public function setAny($any)
    {
        $this->any = $any;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getCats()
    {
        return $this->cats;
    }

    /**
     * @param array $cats
     * @return $this
     */
    public function setCats($cats)
    {
        $this->cats = $cats;
        return $this;
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
     * @return $this;
     */
    public function setCreAt($creAt)
    {
        $this->creAt = $creAt;
        return $this;
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
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @param UserModel[] $users
     * @return $this
     */
    public function setPrevUsers(array $users = [])
    {
        $this->prevUsers = $users;
        return $this;
    }

    /**
     * @return UserModel[]
     */
    public function getPrevUsers()
    {
        return $this->prevUsers;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
