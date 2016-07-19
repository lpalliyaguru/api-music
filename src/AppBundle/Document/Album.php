<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\AlbumRepository")
 * @ODM\HasLifecycleCallbacks
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
     * @ODM\ReferenceMany(targetDocument="Artist")
     */
    protected $artists;

    /**
     * @Exclude
     */
    protected $artistsIds;

	/**
     * @ODM\String
	 *
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

    /**
     * @ODM\Boolean
     */
    protected $active;

    /**
     * @Exclude
     */
    private $imageFile;

    /**
     * @Exclude
     */
    private $bannerFile;

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

    public function setArtists($artists)
    {
        $this->artists = $artists;
        return $this;
    }

    public function getArtists()
    {
        return $this->artists;
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

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    public function getBannerFile()
    {
        return $this->imageFile;
    }

    public function setBannerFile($banner)
    {
        $this->bannerFile = $banner;
    }

    public function getArtistsIds()
    {
        return $this->artistsIds;
    }

    public function setArtistsIds($ids)
    {
        $this->artistsIds = $ids;
    }

    public function addArtist($artist)
    {
        $this->artists[] = $artist;
    }

    /**
     * @ODM\PrePersist
     */
    public function prePersist()
    {
        if(\is_null($this->image)) {
            $this->image = 'https://s3-ap-southeast-1.amazonaws.com/musicapplkassets/assets/images/album-default.jpg';
        }
    }

    /**
     * @ODM\PreUpdate
     */
    public function preUpdate()
    {
        if(\is_null($this->image)) {
            $this->image = 'https://s3-ap-southeast-1.amazonaws.com/musicapplkassets/assets/images/album-default.jpg';
        }
    }
}
