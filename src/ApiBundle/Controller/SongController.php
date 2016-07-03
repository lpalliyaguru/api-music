<?php

namespace ApiBundle\Controller;

use AppBundle\Document\Follower;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as View;

class SongController extends FOSRestController
{
    /**
     * @Rest\Options("songs/{id}/played")
     * @param Request $request
     * @return array
     */
    public function optionsSongsPlayedAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("songs/{id}/played")
     * @param Request $request
     * @return array
     */
    public function getSongsPlayedAction(Request $request, $id)
    {
        $songManager   = $this->get('manager.song');
        $song = $songManager->getOne($id);
        $numOfPlayed = $song->getNumOfPlayed();
        //use update method in the ODM instead.
        $song->setNumOfPlayed($numOfPlayed + 1);
        $songManager->update($song);

        return $song;
    }

    /**
     * @Rest\Options("songs/search")
     * @param Request $request
     * @return array
     */
    public function optionSongSearchAction(Request $request) { }

    /**
     * @Rest\Get("songs/search", name="ApisearchSong")
     * @return array
     */
    public function getSongSearchAction(Request $request)
    {
        $songManager    = $this->get('manager.song');
        $term           = $request->query->get('term') ? $request->query->get('term') : '';

        if($term != '') {
            $songs = $songManager->searchSongs($term, false);
        }
        else {
            $songs = array();
        }

        return $songs;
    }
}
