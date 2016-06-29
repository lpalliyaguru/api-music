<?php
namespace AppBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class SongRepository extends DocumentRepository
{
    public function searchSongs($term)
    {
        $qb = $this->createQueryBuilder('song');
        $songs = $qb
                    ->field('displayName')->equals(new \MongoRegex("/$term/i"))
                    ->getQuery()
                    ->toArray()
                ;

        return $songs;
    }

}