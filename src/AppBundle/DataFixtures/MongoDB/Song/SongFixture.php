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
        $artist = $artistManager->getOneByArtistId('divulgane');
        $album = $albumManager->getOneByAlbumId('asata-asuwana-maaime');

        $song = new Song();
        $song->setArtist('divulgane');
        $song->setDisplayName('King of the mood - Here comes the sun');
        $song->setTags(array('Classic', 'Rock'));
        $song->setUrl('http://ccmixter.org/content/unreal_dm/unreal_dm_-_Recycle_This.mp3');
        $song->setType('audio/mpeg');
        $song->setArtist($artist);
        $song->setAlbum($album);
        $manager->persist($song);
        $meta = new Meta();
        $meta->created = $meta->updated = new \DateTime();
        $song->setMeta($meta);
        $manager->flush();
    }
}
