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


}
