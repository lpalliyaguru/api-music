<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class PlayListManager
{
    protected $documentManager;
    protected $repository;
    protected $songManager;

    public function __construct(ManagerRegistry $registryManager, $songManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:PlayList');
        $this->songManager      = $songManager;
    }

    public function getOneByPlayListId($id)
    {
        return $this->repository->findOneByPlaylistId($id);
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getAllByUser($user)
    {
        error_log($user->getId());
        return $this->repository->findBy(array(
            'owner.$id' => new \MongoId($user->getId())
        ));
    }

    public function generatePlayListId($name)
    {
        $name       = preg_replace('/\s+/', '_', $name);
        $id         = strtolower($name);
        $playList   = $this->getOneByPlayListId($id);

        if($playList) {
            $id = $id . '_' . rand(3000,9999);
        }

        return $id;
    }

    public function update($playList)
    {
        $this->documentManager->persist($playList);
        $this->documentManager->flush();
        return true;
    }

    public function addSongsByIds($playList, $songIds)
    {
        foreach ($songIds as $songId) {
            $song = $this->songManager->getOne($songId);
            $playList->addSong($song);
        }

        $this->documentManager->persist($playList);
        $this->documentManager->flush();
        return $playList;
    }
}
