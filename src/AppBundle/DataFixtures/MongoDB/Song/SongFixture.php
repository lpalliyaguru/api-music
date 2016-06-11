<?php

namespace AppBundle\DataFixtures\Song;

use AppBundle\Document\Meta;
use AppBundle\Document\Song;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SongFixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $artistManager = $this->container->get('manager.artist');
        $albumManager = $this->container->get('manager.album');

        $dataDir = $this->container->getParameter('kernel.root_dir') . '\Resources\data';
        $songs = file_get_contents(sprintf('%s\%s', $dataDir, 'song_divulgane.json'));
        $songs = json_decode($songs, true);

        foreach($songs as $songData) {
            $artist = $artistManager->getOneByArtistId($songData['artist']);
            $album = $albumManager->getOneByAlbumId($songData['album']);

            $song = new Song();
            $song->setDisplayName($songData['displayName']);
            $song->setTags($songData['tags']);
            $song->setUrl($songData['url']);
            $song->setType($songData['type']);
            $song->setArtist($artist);
            $song->setAlbum($album);
			$song->setImage($songData['image']);

            $meta = new Meta();
            $meta->created = $meta->updated = new \DateTime();
            $song->setMeta($meta);
            $manager->persist($song);
        }

        $manager->flush();
    }
}
