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
}