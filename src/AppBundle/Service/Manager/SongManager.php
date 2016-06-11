<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class SongManager
{
    protected $documentManager;
    protected $repository;

    public function __construct(ManagerRegistry $registryManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Song');
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function update($song)
    {
        error_log(json_encode($song->getNumOfPlayed()));
        $this->documentManager->persist($song);
        $this->documentManager->flush();
    }

}
