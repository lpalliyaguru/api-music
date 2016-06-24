<?php

namespace AppBundle\Document\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;

class AlbumRepository extends DocumentRepository
{
    public function removeSong($album, $songId) {
        $qb = $this->createQueryBuilder();
        $qb->update()
            ->field('_id')->equals(new \MongoId($album->getId()))
            ->field('songs')->pull(array('$id' => new \MongoId($songId)))
            ->getQuery()
            ->execute();
        return $album;
    }
}