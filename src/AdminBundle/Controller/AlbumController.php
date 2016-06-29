<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\AlbumCreateType;
use AppBundle\Document\Album;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends BaseController
{
    /**
     * @Route("/", name="adminAlbums")
     * @Template()
     */
    public function adminAlbumsAction()
    {
        /**
         * @TODO Add pagination and search here
         */
        $albumManager = $this->get('manager.album');
        $albums = $albumManager->getAll();
        return array('albums' => $albums);
    }

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

    /**
     * @Route("/{albumId}/banner", name="albumUpdateBanner")
     * @Template()
     */
    public function albumUpdateBannerAction(Request $request, $albumId)
    {
        $albumManager   = $this->get('manager.album');
        $serializer     = $this->get('jms_serializer');
        $mediaManager   = $this->get('manager.media');
        $album      = $albumManager->getOne($albumId);
        $bannerFile = $request->files->get('banner');
        $bannerUrl  = $mediaManager->selfUpload($bannerFile,'uploads');
        //$album->setBanner($bannerUrl);
        //$albumManager->update($album);
        return array('banner' => $bannerUrl , 'album' => $album);
    }

    /**
     * @Route("/{albumId}/banner/resize", name="albumResizeBanner")
     * @Template()
     */
    public function albumResizeBannerAction(Request $request, $albumId)
    {
        $albumManager   = $this->get('manager.album');
        $serializer     = $this->get('jms_serializer');
        $mediaManager   = $this->get('manager.media');
        $apiURL         = $this->getParameter('app_main_api');
        $webDir     = $this->getParameter('web_dir');

        $album      = $albumManager->getOne($albumId);
        $bannerFile = $request->request->get('image');
        $coords = $request->request->get('banner-coors-hidden');
        $coords = json_decode($coords, true);
        $realFile = substr($bannerFile, strlen($apiURL . '/uploads/'));
        $fileInfo = $mediaManager->getFileInfo($realFile);
        $resizableImage =sprintf('%s%suploads%s%s', $webDir, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $fileInfo['name'] . '_resized.' . $fileInfo['ext']);

        $mediaManager->resizeImage(
            sprintf('%s%suploads%s%s', $webDir, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $realFile),
            $resizableImage,
            $coords
        );

        $url = $mediaManager->uploadFromLocal($resizableImage, true);
        $album->setBanner($url);
        $albumManager->update($album);
        return new JsonResponse(array(
            'path' => $url
        ));
    }

    /**
     * @Route("/create/new", name="adminCreateAlbum")
     * @Template()
     */
    public function adminCreateAlbumAction(Request $request)
    {
        $albumManager   = $this->get('manager.album');
        $artistManager   = $this->get('manager.artist');
        $album          = new Album();
        $options        = array();
        $form           = $this->get('form.factory')->create(new AlbumCreateType(), $album, $options);

        if($request->getMethod() == 'POST') {

            $form->submit($request->get($form->getName()), false);

            if($form->isValid()) {

                $artistIds = $request->request->get('artistIds');
                foreach($artistIds as $id) {
                    $artist = $artistManager->getOne($id);
                    $album->addArtist($artist);
                }
                $albumManager->update($album);
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'Created ' . $album->getName(),
                    'albumUrl' => $this->generateUrl('adminAlbum', array('albumId' => $album->getAlbumId()))
                ));
            }
            else {
                return new JsonResponse($this->getErrorMessages($form));
            }
        }
        return array(
            'album' => $album,
            'form'  => $form->createView()
        );
    }
}

