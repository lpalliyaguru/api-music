<?php

namespace AppBundle\DataFixtures\Song;

use AppBundle\Document\Meta;
use AppBundle\Document\PlayList;
use AppBundle\Document\Song;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\SerializationContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PlayListFixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $songManager = $this->container->get('manager.song');
        $playlistManager = $this->container->get('manager.playlist');

        $dataDir = sprintf('%s%sResources%sdata', $this->container->getParameter('kernel.root_dir'), DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);
        //$songs = file_get_contents(sprintf('%s\%s', $dataDir, 'playlist_greatest-hits.json'));
        $songs = file_get_contents(sprintf('%s%s%s', $dataDir, DIRECTORY_SEPARATOR, 'playlist_greatest-hits.json'));
        $playlistJson = json_decode($songs, true);
        $playList = new PlayList();

        foreach($playlistJson['songs'] as $songData) {
            $song = $songManager->getOne($songData);
            $playList->addSong($song);
        }

        $playList->setName($playlistJson['name']);
        $playList->setPlayListId($playlistJson['id']);
        $playList->setBanner($playlistJson['banner']);
        $playList->setImage($playlistJson['image']);
        $meta = new Meta();
        $meta->created = $meta->updated = new \DateTime();
        $playList->setMeta($meta);
        $manager->persist($playList);
        $manager->flush();
    }
}
