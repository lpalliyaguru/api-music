<?php

namespace AppBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class PlayListRepository extends DocumentRepository
{
    /*public function findByOwner($user)
    {
        $qb = $this->createQueryBuilder('AppBundle:PlayList');
        $list = $qb
            ->field('owner.$id')->equals($user->getId())
            ->getQuery()
            ->execute()
        ;

        return $list;
    }*/
}
