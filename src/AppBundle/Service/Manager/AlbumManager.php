<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class AlbumManager
{
    protected $documentManager;
    protected $repository;

    public function __construct(ManagerRegistry $registryManager, $songManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Album');
        $this->songManager      = $songManager;
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function getOneByAlbumId($albumId)
    {
        return $this->repository->findOneByAlbumId($albumId);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function addSongsByIDs($album, $songsIds)
    {
        $songs = array();
        foreach ($songsIds as $songId) {
            $song = $this->songManager->getOne($songId);
            $album->addSong($song);
            $songs[] = $song;
        }

        $this->documentManager->persist($album);
        $this->documentManager->flush();
        return $songs;

    }

    public function removeSongById($album, $songId) {
        return $this->repository->removeSong($album, $songId);
    }

    public function update($album)
    {
        $this->documentManager->persist($album);
        $this->documentManager->flush();
        return $album;
    }

    public function getAlbumsByArtists($artists)
    {
        $artistIds = array();
        foreach ($artists as $artist) {
            $artistIds[] = new \MongoId($artist->getId());
        }
        return $this->repository->findByArtists($artistIds);

    }

    public function map($albums)
    {
        $mapped = array();
        foreach($albums as $album) {
            $mapped[] = array(
                'albumId' => $album->getAlbumId(),
                'about' => $album->getAbout(),
                'release' => $album->getRelease(),
                'active' => $album->getActive(),
                'name' => $album->getName()
            );
        }
        return $mapped;
    }
}
