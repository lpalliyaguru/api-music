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

class ArtistController extends FOSRestController
{
    /**
     * @Rest\Options("artists")
     * @param Request $request
     * @return array
     */
    public function optionsArtistsAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("artists")
     * @param Request $request
     * @return array
     */
    public function getArtistsAction(Request $request)
    {
        $artistsManager   = $this->get('manager.artist');
        $artists         = $artistsManager->getAllActiveArtists();

        return $artists;
    }

    /**
     * @Rest\Options("artists/{id}")
     * @param Request $request
     * @return array
     */
    public function optionsArtistAction(Request $request, $id = null) { }

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

    /**
     * @Rest\Options("artists/{id}/follow")
     * @param Request $request
     * @return array
     */
    public function optionsFollowArtistAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("artists/{artistId}/follow")
     * @param Request $request
     * @return array
     */
    public function getFollowArtistAction(Request $request, $artistId)
    {
        $artistsManager   = $this->get('manager.artist');
        $followerManager   = $this->get('manager.follower');
        $who    = $this->get('security.context')->getToken()->getUser();
        $artist = $artistsManager->getOneByArtistId($artistId);

        if(!$followerManager->isFollowing(Follower::FOLLOWER_ARTIST, $who, $artist)) {
            $followerManager->follow(Follower::FOLLOWER_ARTIST, $who, $artist);
            $numFollowers = $artist->getFollowers();
            $artist->setFollowers($numFollowers + 1);
            $artistsManager->update($artist);
            $message = 'You are now following ' . $artist->getName();
        }
        else {
            $message = 'You are already following ' . $artist->getName();
        }

        return array(
            'success'   => true,
            'artist'    => $artist,
            'message'   => $message
        );
    }

    /**
     * @Rest\Options("artists/{id}/unfollow")
     * @param Request $request
     * @return array
     */
    public function optionsUnFollowArtistAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("artists/{artistId}/unfollow")
     * @param Request $request
     * @return array
     */
    public function getUnFollowArtistAction(Request $request, $artistId)
    {
        $artistsManager   = $this->get('manager.artist');
        $followerManager   = $this->get('manager.follower');
        $who    = $this->get('security.context')->getToken()->getUser();
        $artist = $artistsManager->getOneByArtistId($artistId);

        if($followerManager->isFollowing(Follower::FOLLOWER_ARTIST, $who, $artist)) {
            $followerManager->unfollow(Follower::FOLLOWER_ARTIST, $who, $artist);
            $numFollowers = $artist->getFollowers();
            $artist->setFollowers($numFollowers - 1);
            $artistsManager->update($artist);
            $message = 'You unfollowed ' . $artist->getName();
        }
        else {
            $message = 'You are already not following ' . $artist->getName();
        }

        return array(
            'success'   => true,
            //'artist'    => $artist,
            'message'   => $message
        );
    }

    /**
     * @Rest\Options("artists/{id}/get-followers")
     * @param Request $request
     * @return array
     */
    public function optionsArtistFollowsAction(Request $request, $id = null) { }

    /**
     * @Rest\Get("artists/{artistId}/get-followers")
     * @param Request $request
     * @return array
     */
    public function getArtistFollowsAction(Request $request, $artistId)
    {
        $followerManager    = $this->get('manager.follower');
        $artistsManager     = $this->get('manager.artist');
        $page = $request->query->has('page') ? $request->query->get('page') : 1;

        $artist = $artistsManager->getOneByArtistId($artistId);
        $followers = $followerManager->getFollowers(Follower::FOLLOWER_ARTIST, $artist, $page);
        return $followers;
    }
}
