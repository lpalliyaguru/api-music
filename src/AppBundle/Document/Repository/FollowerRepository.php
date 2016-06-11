<?php

namespace AppBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class FollowerRepository extends DocumentRepository
{
    public function getFollowers($type, $whom, $page = 1)
    {
        $qb = $this->createQueryBuilder('AppBundle:Follower');
        $limit = 2;
        $skip = $limit * $page - $limit;

        $followers = $qb
            ->field('type')->equals($type)
            ->field('whom')->equals($whom->getId())
            ->limit($limit)
            ->skip($skip)
            ->getQuery()
            ->execute()
        ;
        return $followers;
    }

    public function removeFollower($type, $who, $whom)
    {
        $qb = $this->createQueryBuilder('AppBundle:Follower');
        $qb
            ->remove()
            ->field('type')->equals($type)
            ->field('who')->equals($who->getId())
            ->field('whom')->equals($whom->getId())
            ->getQuery()
            ->execute()
        ;

        return true;
    }
}
