<?php

namespace AppBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ArtistRepository extends DocumentRepository
{
    public function getAllActiveArtists()
    {
        return $this->findBy(array(
            'active' => true
        ));
    }

    public function search($query)
    {
        $qb = $this->createQueryBuilder('artist');
        $artists = $qb
            ->field('name')->equals(new \MongoRegex("/$query/i"))
            ->getQuery()
            ->toArray()
        ;

        return $artists;
    }
}