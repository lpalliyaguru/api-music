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

class UserController extends FOSRestController
{
    /**
     * @Rest\Options("/user/{id}/get-following-artists")
     * @param Request $request
     * @return array
     */
    public function optionsFollowingArtistsAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("/user/{id}/get-following-artists")
     * @param Request $request
     * @return array
     */
    public function getFollowingArtistsAction(Request $request, $id)
    {
        $userManager      = $this->get('manager.user');
        $followerManager  = $this->get('manager.follower');

        $user = $userManager->getOne($id);
        $page = $request->query->has('page') ? $request->query->get('page') : 1;

        $followings = $followerManager->getFollowing(Follower::FOLLOWER_ARTIST, $user, $page);

        return $followings    ;
    }

    /**
     * @Rest\Options("/user/{id}")
     * @param Request $request
     * @return array
     */
    public function optionsUserAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("/user/{id}")
     * @param Request $request
     * @return array
     */
    public function getUesrAction(Request $request, $id)
    {
        $userManager = $this->get('manager.user');
        return $userManager->getOne($id);
    }
}
