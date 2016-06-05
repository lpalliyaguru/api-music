<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as View;

class ArtistController extends FOSRestController
{
    /**
     * @Rest\Options("artists")
     * @param Request $request
     * @return array
     */
    public function optionsArtistAction(Request $request)
    {

    }

    /**
     * @Rest\Get("artists")
     * @param Request $request
     * @return array
     */
    public function getArtistsAction(Request $request)
    {
        $artistsManager   = $this->get('manager.artist');
        $artists         = $artistsManager->getAll();

        return $artists;
    }

    /**
     * @Rest\Get("artists/{artistId}")
     * @param Request $request
     * @return array
     */
    public function getArtistAction(Request $request, $artistId)
    {
        $artistsManager   = $this->get('manager.artist');
        $artists         = $artistsManager->getOneByArtistId($artistId);

        return $artists;
    }
}
