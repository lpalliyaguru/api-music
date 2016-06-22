<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="adminIndex")
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    /**
     * @Route("/match-id/{id}/{type}", name="matchID")
     */
    public function matchIdAction(Request $request, $id, $type)
    {
        $helper = $this->get('app.helper');
        $exists = $helper->isExistsId($id, $type);

        return new JsonResponse(array(
            'success'   => true,
            'exists'    => $exists
        ));
    }
}
