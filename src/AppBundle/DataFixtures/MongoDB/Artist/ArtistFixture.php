<?php

namespace AppBundle\DataFixtures\Artist;

use AppBundle\Document\Artist;
use AppBundle\Document\Meta;
use AppBundle\Document\Song;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArtistFixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $artist = new Artist();
        $artist->setName('Divulgane');
        $artist->setAbout('Classical singer, music director');
        $artist->setGenre(array('Classic'));
        $artist->setImage('dist/images/artists/artist4.jpg');
        $artist->setArtistId('divulgane');
        $meta = new Meta();
        $meta->created = $meta->updated = new \DateTime();
        $artist->setMeta($meta);
        $manager->persist($artist);
        $manager->flush();
    }
}
