<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\AlbumCreateType;
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
        $options        = array();
        $form           = $this->get('form.factory')->create(new AlbumCreateType(), $album, $options);

        if($request->getMethod() == 'POST') {
            $form->submit($request->get($form->getName()), false);

            if($form->isValid()) {
                //$files = $request->files->get('artist');

                /*if(!empty($files) && isset($files['imageFile'])) {
                    $imageHiddenURL = $mediaManager->upload($files['imageFile'], 'images', true);
                    $artist->setImage($imageHiddenURL);
                }

                if(!empty($files) && isset($files['bannerFile'])) {
                    $bannerHiddenURL = $mediaManager->upload($files['bannerFile'], 'images', true);
                    $artist->setBanner($bannerHiddenURL);
                }*/
                $albumManager->update($album);
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'Updated ' . $album->getName(),
                    'albumUrl' => $this->generateUrl('adminAlbum', array('albumId' => $album->getId()))
                ));
            }
        }
        return array(
            'album' => $album,
            'form'  => $form->createView()
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

    /**
     * @Route("/{albumId}/songs/delete/{songId}", name="adminRemoveSongsFromAlbum")
     * @Template()
     */
    public function albumRemoveSongsAction(Request $request, $albumId, $songId)
    {
        $albumManager   = $this->get('manager.album');
        $serializer     = $this->get('jms_serializer');

        $album      = $albumManager->getOne($albumId);
        $album      = $albumManager->removeSongById($album, $songId);

        return new Response($serializer->serialize($album, 'json'));
    }
}
