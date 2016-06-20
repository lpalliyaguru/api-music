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

    public function getOne()
    {

    }

    public function getOneByArtistId($artistId)
    {
        return $this->repository->findOneByArtistId($artistId);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function update($artist)
    {
        $this->documentManager->persist($artist);
        $this->documentManager->flush();
        return true;
    }
}
