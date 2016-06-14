<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;
/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\ArtistRepository")
 *
 */
class PlayList
{
    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $name;

    /**
     * @ODM\String
     */
    protected $playlistId;

    /**
     * @ODM\ReferenceMany(targetDocument="Song")
     * @MaxDepth(2)
     */
    private $songs;

    /**
     * @ODM\String
     */
    protected $image;

    /**
     * @ODM\String
     */
    protected $banner;

    /**
     * @ODM\ReferenceOne(targetDocument="User")
     */
    private $owner;

    /**
     * @ODM\EmbedOne(targetDocument="Meta")
     */
    protected $__meta;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setPlayListId($id)
    {
        $this->playlistId = $id;
        return $this;
    }

    public function getPlayListId()
    {
        return $this->playlistId;
    }

    public function setSongs($songs)
    {
        $this->songs = $songs;
        return $this;
    }

    public function getSongs()
    {
        return $this->songs;
    }

    public function addSong($song)
    {
        $this->songs[] = $song;
    }

    public function setMeta($meta)
    {
        $this->__meta = $meta;
        return $this;
    }

    public function getMeta()
    {
        return $this->__meta;
    }

    public function setBanner($banner)
    {
        $this->banner = $banner;
        return $this;
    }

    public function getBanner()
    {
        return $this->banner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }
}
