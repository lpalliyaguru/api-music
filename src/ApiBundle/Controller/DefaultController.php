<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends FOSRestController
{
    /**
     * @Rest\Get("/")
     */
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }

    /**
     * @Rest\Options("/search")
     */
    public function optionsSearchAction(Request $request)
    {}

    /**
     * @Rest\Get("/search")
     */
    public function getSearchAction(Request $request)
    {
        $keyword = $request->query->get('search');
        $artistManager = $this->get('manager.artist');
        $albumManager = $this->get('manager.album');
        $artists = $artistManager->search($keyword);

        return array(
            'success'   => true,
            'data'      => $artists
        );
    }

}
