<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class SongManager
{
    protected $documentManager;
    protected $repository;
    protected $router;
    protected $mediaManager;

    public function __construct(ManagerRegistry $registryManager, $router, $mediaManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Song');
        $this->router           = $router;
        $this->mediaManager     = $mediaManager;

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
                    'edit'      => $this->router->generate('adminEditSong', array('id' => $song->getId())),
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
                'name'  => $artist->getName(),
                'url'   => '/admin/artist/' , $artist->getArtistId()
            );
        }

        return $artists;
    }

    public function manageSongSource(Request $request, $webDir)
    {
        if($request->files->has('source_file')) {

        }
        else {
            $url            = $request->request->get('source_url');
            error_log($url);
            preg_match('/^(.+)\/(.+)\.(mp3|ogg|mp4)$/', $url, $matches);

            if(!isset($matches[1])) { throw new \Exception('URL is not valid!'); }

            $tempLocation   = \sprintf('%s%suploads%ssongs', $webDir, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);
            $mp3Content     = file_get_contents($url);
            $extension      = $matches[3];
            $fileName       = $matches[2];
            $fullName       = sprintf('%s.%s', $fileName, $extension);
            $filePath = sprintf('%s%s%s', $tempLocation, DIRECTORY_SEPARATOR, $fullName);
            file_put_contents($filePath, $mp3Content);
            //uploading to s3
            $songURL = $this->mediaManager->uploadFromLocal('song', $filePath, true);
            return $songURL;
        }
    }
}
