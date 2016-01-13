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
     */
    public function setSimilarContent($similarContent)
    {
        $this->similarContent = $similarContent;
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
     */
    public function setUser(UserModel $user)
    {
        $this->user = $user;
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
     */
    public function setCount($count)
    {
        $this->count = $count;
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
     */
    public function setFloat($float)
    {
        $this->float = $float;
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
     */
    public function setAny($any)
    {
        $this->any = $any;
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
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     */
    public function setCats($cats)
    {
        $this->cats = $cats;
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
    public function setCreAt($creAt)
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
        $this->active = $active;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}
