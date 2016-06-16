<?php

namespace ApiBundle\Controller;

use AppBundle\Document\Meta;
use AppBundle\Document\PlayList;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;

class PlayListController extends FOSRestController
{
    /**
     * @Rest\Options("playlists")
     * @param Request $request
     * @return array
     */
    public function optionsPlayListsAction(Request $request) {}

    /**
     * @Rest\Get("playlists")
     * @param Request $request
     * @View(serializerEnableMaxDepthChecks=true)
     * @return array
     */
    public function getPlayListsAction(Request $request)
    {
        $playListManager   = $this->get('manager.playlist');
        $user = $this->get('security.context')->getToken()->getUser();
        $playLists         = $playListManager->getAllByUser($user);

        return $playLists;
    }

    /**
     * @Rest\Post("playlists")
     * @param Request $request
     * @return array
     */
    public function postPlayListsAction(Request $request)
    {
        $playListManager   = $this->get('manager.playlist');
        $owner = $this->get('security.context')->getToken()->getUser();
        $name  = $request->request->get('name');
        $playListId = $playListManager->generatePlayListId($name);

        $playlist = new PlayList();
        $playlist->setName($name);
        $playlist->setPlayListId($playListId);
        $playlist->setBanner('dist/images/playlists/playlistbanner.jpg');
        $playlist->setImage('dist/images/songs/song10.jpg');
        $playlist->setOwner($owner);

        $meta = new Meta();
        $meta->updated = $meta->created = new \DateTime();
        $playlist->setMeta($meta);
        $playListManager->update($playlist);
        return $playlist;
    }

    /**
     * @Rest\Options("playlists/{playlistId}")
     * @param Request $request
     * @return array
     */
    public function optionsPlayListAction(Request $request, $playlistId) {}

    /**
     * @Rest\Get("playlists/{playlistId}")
     * @param Request $request
     * @return array
     */
    public function getPlayListAction(Request $request, $playlistId)
    {
        $playlistManager    = $this->get('manager.playlist');
        $playlist           = $playlistManager->getOneByPlayListId($playlistId);

        return $playlist;
    }

    /**
     * @Rest\Options("playlists/{playlistId}/add-song")
     * @param Request $request
     * @return array
     */
    public function optionsSongsAction(Request $request, $playlistId) {}

    /**
     * @Rest\Post("playlists/{playlistId}/add-song")
     * @param Request $request
     * @return array
     */
    public function postSongsAction(Request $request, $playlistId)
    {
        $playlistManager    = $this->get('manager.playlist');
        $playlist           = $playlistManager->getOneByPlayListId($playlistId);
        $songIds            = $request->request->get('songs');

        $playlist = $playlistManager->addSongsByIds($playlist, $songIds);
        return $playlist;
    }
}