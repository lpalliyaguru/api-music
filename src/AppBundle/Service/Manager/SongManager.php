<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class SongManager
{
    protected $documentManager;
    protected $repository;
    protected $router;

    public function __construct(ManagerRegistry $registryManager, $router)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Song');
        $this->router           = $router;
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function update($song)
    {
        $this->documentManager->persist($song);
        $this->documentManager->flush();
    }

    public function searchSongs($term, $artistIds)
    {
        if($artistIds) {
            $artistIds = $this->prepareIds($artistIds);
        }
        return $this->repository->searchSongs($term, $artistIds);
    }

    private function prepareIds($ids)
    {
        $objectIds = array();
        foreach($ids as $id) {
            $objectIds[] = new \MongoId($id);
        }
        return $objectIds;
    }

    public function mapSongInfo($songs)
    {
        $mapped = array();
        foreach($songs as $song) {
            $mapped[] = array(
                'displayName'   => $song->getDisplayName(),
                'source'        => $song->getUrl(),
                'image'         => $song->getImage(),
                'played'        => $song->getNumOfPlayed(),
                'artists'       => $this->getArtistInfo($song),
                'links'         => array(
                    'edit'      => '/edit',
                    'delete'    => '/delete'
                )
            );
        }
        return $mapped;
    }

    private function getArtistInfo($song)
    {
        $artists = array();
        foreach ($song->getArtists() as $artist) {
            $artists[] = array(
                'name' => $artist->getName(),
                'url' => '/admin/artist/' , $artist->getArtistId()
            );
        }
    }

}
