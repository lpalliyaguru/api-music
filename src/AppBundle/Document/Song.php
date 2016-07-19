<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;

use AppBundle\Document\User;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\SongRepository")
 * @ODM\HasLifecycleCallbacks
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
    protected $songId;

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
    protected $description;

    /**
     * @ODM\String
     */
    protected $type;

    /**
     * @ODM\String
     */
    protected $isrc;

    /**
     * @ODM\ReferenceMany(targetDocument="Artist")
     */
    protected $artists;

    /**
     * @ODM\Collection
     */
    protected $tags;

    /**
     * @ODM\Collection
     */
    protected $genre;

    /**
     * @ODM\Int
     */
    protected $numOfPlayed;

    /**
     * @ODM\Boolean
     */
    protected $isPublished;

    /**
     * @ODM\String
     */
    protected $composer;

    /**
     * @ODM\String
     */
    protected $publisher;

    /**
     * @ODM\String
     */
    protected $release;

    /**
     * @ODM\String
     */
    protected $buyLink;

    /**
     * Dummy variable for hold artist ids
     * @Exclude()
     */
    protected $artistIds;

    /**
     * @Exclude()
     * @ODM\Boolean
     */
    protected $deleted;

    /**
     * @ODM\EmbedOne(targetDocument="Meta")
     */
    protected $__meta;

    public function __construct()
    {
        $this->tags = array();
    }

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

    public function setArtist($artists)
    {
        $this->artists = $artists;
        return $this;
    }

    public function addArtist($artist)
    {
        $this->artists[] = $artist;
        return $this;
    }

    public function getArtists()
    {
        return $this->artists;
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

    public function setNumOfPlayed($number)
    {
        $this->numOfPlayed = $number;
        return $this;
    }

    public function getNumOfPlayed()
    {
        return $this->numOfPlayed;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function setComposer($composer)
    {
        $this->composer = $composer;
        return $this;
    }

    public function getComposer()
    {
        return $this->composer;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
        return $this;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function setPublished($flag)
    {
        $this->isPublished = $flag;
        return $this;
    }

    public function getPublished()
    {
        return $this->isPublished;
    }

    public function setIsrc($number)
    {
        $this->isrc = $number;
        return $this;
    }

    public function getIsrc()
    {
        return $this->isrc;
    }

    public function setRelease($release)
    {
        $this->release = $release;
        return $this;
    }

    public function getRelease()
    {
        return $this->release;
    }

    public function setBuylink($link)
    {
        $this->buyLink = $link;
        return $this;
    }

    public function getBuylink()
    {
        return $this->buyLink;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function addGenre($genre)
    {
        $this->genre[] = $genre;
        return $this;
    }

    public function setDeleted($flag)
    {
        $this->deleted = $flag;
        return $this;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @ODM\PrePersist
     */
    public function prePersist()
    {
        if(\is_null($this->image)) {
            $this->image = 'https://s3-ap-southeast-1.amazonaws.com/musicapplkassets/assets/images/song-default.jpg';
        }
    }

    /**
     * @ODM\PreUpdate
     */
    public function preUpdate()
    {
        if(\is_null($this->image)) {
            $this->image = 'https://s3-ap-southeast-1.amazonaws.com/musicapplkassets/assets/images/song-default.jpg';
        }
    }
}
