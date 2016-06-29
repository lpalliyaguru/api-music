<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class ArtistManager
{
    protected $documentManager;
    protected $repository;

    public function __construct(ManagerRegistry $registryManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Artist');
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function getOneByArtistId($artistId)
    {
        return $this->repository->findOneByArtistId($artistId);
    }

    public function getAll()
    {
        return $this->repository->findAll();
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
}
