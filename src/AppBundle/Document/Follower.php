<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\FollowerRepository")
 *
 */
class Follower
{
    const FOLLOWER_ALBUM = 0;

    const FOLLOWER_ARTIST = 1;

    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\Int
     */
    protected $type;

    /**
     * @ODM\String
     */
    protected $who;

    /**
     * @ODM\String
     */
    protected $whom;

    /**
     * @ODM\Date
     */
    protected $when;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getWho()
    {
        return $this->who;
    }

    public function setWho($who)
    {
        $this->who = $who;
        return $this;
    }

    public function getWhom()
    {
        return $this->whom;
    }

    public function setWhom($whom)
    {
        $this->whom = $whom;
        return $this;
    }

    public function getWhen()
    {
        return $this->when;
    }

    public function setWhen($when)
    {
        $this->when = $when;
        return $this;
    }
}
