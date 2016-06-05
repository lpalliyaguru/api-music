<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\ArtistRepository")
 *
 */
class Artist
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
    protected $artistId;

    /**
     * @ODM\Collection
     */
    protected $genre;

    /**
     * @ODM\String
     */
    protected $about;

    /**
     * @ODM\String
     */
    protected $image;

    /**
     * @ODM\EmbedOne(targetDocument="Meta")
     */
    protected $__meta;

    /**
     * @ODM\ReferenceMany(targetDocument="Song", mappedBy="artist")
     */
    private $songs;

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

    public function setArtistId($artistId)
    {
        $this->artistId = $artistId;
        return $this;
    }

    public function getArtistId()
    {
        return $this->artistId;
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

    public function getAbout()
    {
        return $this->about;
    }

    public function setAbout($about)
    {
        $this->about = $about;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getSongs()
    {
        return $this->image;
    }

    public function setSongs($songs)
    {
        $this->songs = $songs;
        return $this;
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
