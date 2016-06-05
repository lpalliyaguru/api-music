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

class AlbumController extends FOSRestController
{
    /**
     * @Rest\Options("albums")
     * @param Request $request
     * @return array
     */
    public function optionsAlbumsAction(Request $request)
    {

    }

    /**
     * @Rest\Get("albums")
     * @param Request $request
     * @return array
     */
    public function getAlbumsAction(Request $request)
    {
        $albumManager   = $this->get('manager.album');
        $albums         = $albumManager->getAll();

        return $albums;
    }

    /**
     * @Rest\Get("albums/{albumId}")
     * @param Request $request
     * @return array
     */
    public function getAlbumAction(Request $request, $albumId)
    {
        $albumManager   = $this->get('manager.album');
        $album         = $albumManager->getOneByAlbumId($albumId);

        return $album;
    }
}