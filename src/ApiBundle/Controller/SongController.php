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
}
