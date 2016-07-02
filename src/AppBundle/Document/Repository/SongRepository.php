<?php
namespace AppBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class SongRepository extends DocumentRepository
{
    public function searchSongs($term, $artistsIds)
    {
        $qb         = $this->createQueryBuilder('song');
        $songs      = array();
        $queryable  = false;

        if($term) {
            $qb->field('displayName')->equals(new \MongoRegex("/$term/i"));
            $queryable = true;
        }

        if(\is_array($artistsIds)) {

            $qb->field('artists.$id')->in($artistsIds);
            $queryable = true;
        }

        if($queryable) {
            $songs = $qb->getQuery()->toArray();
        }

        return $songs;
    }
}
