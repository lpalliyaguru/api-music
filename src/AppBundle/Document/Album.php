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
class Album
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
    protected $albumId;

    /**
     * @ODM\String
     */
    protected $about;

    /**
     * @ODM\Collection
     */
    protected $genre;

    /**
     * @ODM\ReferenceOne(targetDocument="Artist", inversedBy="songs")
     */
    protected $artist;

    /**
     * @ODM\ReferenceMany(targetDocument="Song", mappedBy="album")
     */
    private $songs;

    /**
     * @ODM\String
     */
    protected $image;

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

    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;
        return $this;
    }

    public function getAlbumId()
    {
        return $this->albumId;
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

    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setAbout($about)
    {
        $this->about = $about;
        return $this;
    }

    public function getAbout()
    {
        return $this->about;
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
