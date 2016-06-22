<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;

use AppBundle\Document\User;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\SongRepository")
 * @ODM\Indexes({
 *   @ODM\Index(keys={"displayName"="text"})
 * })
 *
 */
class Song
{
    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $image;

    /**
     * @ODM\String
	 * @SerializedName("src")
     */
    protected $url;

    /**
     * @ODM\String
     */
    protected $displayName;

    /**
     * @ODM\String
     */
    protected $type;

    /**
     * @ODM\ReferenceOne(targetDocument="Artist", inversedBy="songs")
     */
    protected $artist;

    /**
     * @ODM\ReferenceOne(targetDocument="Album", inversedBy="albums")
     */
    protected $album;

    /**
     * @ODM\Collection
     */
    protected $tags;

    /**
     * @ODM\Int
     */
    protected $numOfPlayed;

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

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }


    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setAlbum($album)
    {
        $this->album = $album;
        return $this;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function setNumOfPlayed($number)
    {
        $this->numOfPlayed = $number;
        return $this;
    }

    public function getNumOfPlayed()
    {
        return $this->numOfPlayed;
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
}
