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
        $dataDir = $this->container->getParameter('kernel.root_dir') . '\Resources\data';
        $artists = file_get_contents(sprintf('%s\%s', $dataDir, 'artists.json'));
        $artists = json_decode($artists, true);
        foreach ($artists as $artistData) {
            $artist = new Artist();
            $artist->setName($artistData['name']);
            $artist->setAbout($artistData['about']);
            $artist->setGenre($artistData['genre']);
            $artist->setImage($artistData['image']);
            $artist->setArtistId($artistData['artistId']);
            $meta = new Meta();
            $meta->created = $meta->updated = new \DateTime();
            $artist->setMeta($meta);
            $manager->persist($artist);
        }

        $manager->flush();
    }
}
