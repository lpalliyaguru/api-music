<?php

namespace AppBundle\Service\Manager;

use AppBundle\Document\Follower;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class FollowerManager
{
    protected $documentManager;
    protected $repository;
    protected $userManager;

    public function __construct(ManagerRegistry $registryManager, $userManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:Follower');
        $this->userManager      = $userManager;
    }

    public function follow($type, $who, $whom) {

        $followerObject = new Follower();

        $followerObject->setType($type);
        $followerObject->setWho($who->getId());
        $followerObject->setWhom($whom->getId());
        $followerObject->setWhen(new \DateTime());
        $this->documentManager->persist($followerObject);
        $this->documentManager->flush();
    }

    public function unfollow($type, $who, $whom) {

        return $this->repository->removeFollower($type, $who, $whom);
    }

    public function isFollowing($type, $who, $whom)
    {
        $follower = $this->repository->findBy(array(
            'type' => (int)$type,
            'who' => (string)$who->getId(),
            'whom' => (string)$whom->getId()
        ));

        return \count($follower) > 0;
    }

    public function getFollowers($type, $whom)
    {
        $followers = $this->repository->getFollowers($type, $whom);
        return $this->remapFollowers($followers);
    }

    private function remapFollowers($rawFollowers)
    {
        $mappedFollowers = array();
        foreach( $rawFollowers as $rawFollower) {
            $mappedFollowers[] = $this->userManager->getOne($rawFollower->getWho());
        }
        return $mappedFollowers;
    }
}
