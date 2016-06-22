<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
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
     * @ODM\ReferenceOne(targetDocument="Artist", inversedBy="album")
     */
    protected $artist;

	/**
     * @ODM\Date
	 * @Type("DateTime")
     */
    protected $release;

    /**
     * @ODM\ReferenceMany(targetDocument="Song")
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

    public function setBanner($banner)
    {
        $this->banner = $banner;
        return $this;
    }

    public function getBanner()
    {
        return $this->banner;
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

    public function addSong($song)
    {
        $this->songs[] = $song;
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
