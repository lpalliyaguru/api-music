<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\ArtistCreateType;
use AppBundle\Document\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends Controller
{

    /**
     * @Route("/{albumId}", name="adminAlbum")
     * @Template()
     */
    public function albumAction(Request $request, $albumId)
    {
        $albumManager   = $this->get('manager.album');
        $album          = $albumManager->getOneByAlbumId($albumId);

        return array(
            'album' => $album
        );
    }

    /**
     * @Route("/{albumId}/songs", name="adminAddSongsToAlbum")
     * @Template()
     */
    public function albumAddSongsAction(Request $request, $albumId)
    {
        $albumManager   = $this->get('manager.album');
        $serializer     = $this->get('jms_serializer');
        $album      = $albumManager->getOne($albumId);
        $songsIds   = $request->request->get('songs');
        $songs      = $albumManager->addSongsByIDs($album, $songsIds);
        return new Response($serializer->serialize($songs, 'json'));
    }

}
