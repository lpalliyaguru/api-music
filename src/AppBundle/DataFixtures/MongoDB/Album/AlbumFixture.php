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
        $dataDir = $this->container->getParameter('kernel.root_dir') . '\Resources\data';
        $albums = file_get_contents(sprintf('%s\%s', $dataDir, 'albums.json'));
        $albums = json_decode($albums, true);
        $artistManager = $this->container->get('manager.artist');

        foreach ($albums as $albumData) {
            $artist = $artistManager->getOneByArtistId($albumData['artist']);
            $album = new Album();
            $album->setName($albumData['name']);
            $album->setAbout($albumData['about']);
            $album->setGenre($albumData['genre']);
            $album->setImage($albumData['image']);
            $album->setAlbumId($albumData['albumId']);
            $album->setArtist($artist);
            $meta = new Meta();
            $meta->created = $meta->updated = new \DateTime();
            $album->setMeta($meta);
            $manager->persist($album);
        }

        $manager->flush();
    }
}
