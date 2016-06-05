<?php

namespace AppBundle\DataFixtures\Artist;

use AppBundle\Document\Album;
use AppBundle\Document\Artist;
use AppBundle\Document\Meta;
use AppBundle\Document\Song;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AlbumFixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $artistManager = $this->container->get('manager.artist');
        $artist = $artistManager->getOneByArtistId('divulgane');
        $album = new Album();
        $album->setName('Asata asuwana maime');
        $album->setAbout('Asata asuwana maime is one of the best album of Karunaratne Divulgane');
        $album->setGenre(array('Classic'));
        $album->setImage('dist/images/albums/album1.jpg');
        $album->setAlbumId('asata-asuwana-maaime');
        $album->setArtist($artist);
        $meta = new Meta();
        $meta->created = $meta->updated = new \DateTime();
        $album->setMeta($meta);
        $manager->persist($album);
        $manager->flush();
    }
}
