<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class AlbumManager
{
    protected $documentManager;
    protected $repository;

    public function __construct(ManagerRegistry $registryManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Album');
    }

    public function getOneByAlbumId($albumId)
    {
        return $this->repository->findOneByAlbumId($albumId);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }


}
