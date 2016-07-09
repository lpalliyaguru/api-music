<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class ArtistManager
{
    protected $documentManager;
    protected $repository;
    protected $albumManager;
    protected $songManager;

    public function __construct(ManagerRegistry $registryManager, $albumManager, $songManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Artist');
        $this->albumManager     = $albumManager;
        $this->songManager      = $songManager;
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function getOneByArtistId($artistId)
    {
        $artist = $this->repository->findOneByArtistId($artistId);
        $artistAlbums = $this->albumManager->getAlbumsByArtists(array($artist));
        $artist->setAlbums($artistAlbums);
        return $artist;
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getAllByParams($term, $skip, $limit = 15)
    {
        return $this->repository->getAllByParams($term, $skip, $limit);
    }

    public function getAllActiveArtists()
    {
        return $this->repository->getAllActiveArtists();
    }

    public function update($artist)
    {
        $this->documentManager->persist($artist);
        $this->documentManager->flush();
        return true;
    }

    public function search($query)
    {
        return $this->repository->search($query);
    }

    public function mapArtistInfo($artists)
    {
        $mapped = array();
        foreach($artists as $artist) {
            $mapped[] = array(
                'name' => $artist->getName(),
                'id' => $artist->getId(),
                'artistId' => $artist->getArtistId(),
                'image' => $artist->getImage(),
                'banner' => $artist->getBanner(),
                'albums' => $this->albumManager->map($this->albumManager->getAlbumsByArtists(array($artist)))

            );
        }

        return $mapped;
    }
}
