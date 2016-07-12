<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\ArtistCreateType;
use AdminBundle\Form\Type\SongAddType;
use AppBundle\Document\Artist;
use AppBundle\Document\Song;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SongController extends BaseController
{
    /**
     * @Route("/{songId}/image/resize", name="songResizeImage")
     * @Template()
     */
    public function albumResizeBannerAction(Request $request, $songId)
    {
        $songManager   = $this->get('manager.song');
        $serializer     = $this->get('jms_serializer');
        $mediaManager   = $this->get('manager.media');
        $apiURL         = $this->getParameter('app_main_api');
        $webDir         = $this->getParameter('web_dir');

        $song       = $songId !== 'null' ? $songManager->getOne($songId) : null;
        $imageFile  = $request->request->get('image');
        $coords     = $request->request->get('image-coors-hidden');
        $coords     = json_decode($coords, true);
        $realFile   = substr($imageFile, strlen($apiURL . '/uploads/'));
        $fileInfo   = $mediaManager->getFileInfo($realFile);
        $resizableImage = sprintf('%s%suploads%s%s', $webDir, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $fileInfo['name'] . '_resized.' . $fileInfo['ext']);

        $mediaManager->cropImage(
            sprintf('%s%suploads%s%s', $webDir, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $realFile),
            $resizableImage,
            $coords
        );

        $url = $mediaManager->uploadFromLocal('photo', $resizableImage, true);

        if($song) {
            $song->setImage($url);
            $songManager->update($song);
        }

        return new JsonResponse(array(
            'path' => $url
        ));
    }

    /**
     * @Route("/{id}/image", name="adminUpdateImage")
     * @Template()
     */
    public function updateSongImageAction(Request $request, $id)
    {
        $songManager    = $this->get('manager.song');
        $serializer     = $this->get('jms_serializer');
        $mediaManager   = $this->get('manager.media');
        $song           = $id !== 'null' ? $songManager->getOne($id) : null;
        $bannerFile     = $request->files->get('image');
        $imageURL       = $mediaManager->selfUpload($bannerFile, 'uploads', true);
        return array(
            'image' => $imageURL ,
            'song' => $song
        );
    }
    /**
     * @Route("/create/new", name="adminAddSong")
     * @Template()
     */
    public function adminAddSongAction(Request $request)
    {
        $songManager    = $this->get('manager.song');
        $artistManager  = $this->get('manager.artist');
        $song           = new Song();
        $options        = array();
        $webDir         = $this->getParameter('web_dir');
        $form           = $this->get('form.factory')->create(new SongAddType(), $song, $options);

        if($request->getMethod() == 'POST') {
            $form->submit($request->get($form->getName()), false);
            if($form->isValid()) {

                $artistIds = $request->request->get('artistIds');
                $image = $request->request->get('image-hidden');
                foreach($artistIds as $id) {
                    $artist = $artistManager->getOne($id);
                    $song->addArtist($artist);
                }

                $resourceURL = $songManager->manageSongSource($request, $webDir);
                $song->setUrl($resourceURL);
                $song->setImage($image);

                $songManager->update($song);
                return new JsonResponse(array(
                    'success'   => true,
                    'message'   => 'Created ' . $song->getDisplayName(),
                    'path'      => $this->generateUrl('adminEditSong', array('id' => $song->getId()))
                ));
            }
            else {
                return new JsonResponse($this->getErrorMessages($form));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{id}/edit", name="adminEditSong")
     * @Template()
     */
    public function adminEditSongAction(Request $request, $id)
    {
        $songManager    = $this->get('manager.song');
        $artistManager  = $this->get('manager.artist');
        $song           = $songManager->getOne($id);
        $options        = array();
        $webDir         = $this->getParameter('web_dir');
        $form           = $this->get('form.factory')->create(new SongAddType(), $song, $options);

        if($request->getMethod() == 'POST') {
            $form->submit($request->get($form->getName()), false);
            if($form->isValid()) {

                $artistIds = $request->request->get('artistIds');

                if(\is_array($artistIds)) {

                    $song->setArtist(array());
                    foreach($artistIds as $id) {
                        $artist = $artistManager->getOne($id);
                        $song->addArtist($artist);
                    }
                }

                $resourceURL = $songManager->manageSongSource($request, $webDir);

                if(!\is_null($resourceURL)) {
                    $song->setUrl($resourceURL);
                }

                $songManager->update($song);
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'Updated Song: <b>' . $song->getDisplayName() . '</b>',

                ));
            }
            else {
                return new JsonResponse($this->getErrorMessages($form));
            }
        }

        return array(
            'form' => $form->createView(),
            'song' => $song
        );
    }

    /**
     * @Route("/", name="adminSongs")
     * @Template()
     */
    public function adminSongsAction()
    {
        /**
         * @TODO Add pagination and search here
         */
        $albumManager   = $this->get('manager.album');
        $albums         = $albumManager->getAll();
        return array(
            'albums' => $albums
        );
    }

    /**
     * @Route("search", name="adminSearchSongs")
     * @return array
     * @Template()
     */
    public function getSongSearchAction(Request $request)
    {
        $songManager    = $this->get('manager.song');
        $term           = $request->request->has('term') ? $request->request->get('term') : false;
        $draw           = $request->request->has('draw') ? $request->request->get('draw') : false;
        $artistsIds     = $request->request->has('artistIds') ? $request->request->get('artistIds') : false;

        $songs  = $songManager->searchSongs($term, $artistsIds);
        $data   = array(
            'data'              => $songManager->mapSongInfo($songs),
            'draw'              => $draw,
            'recordsFiltered'   => count($songs),
            'recordsTotal'      => count($songs)
        );
        return new JsonResponse($data);
    }

    /**
     * @Route("delete/{id}", name="adminDeleteSong")
     * @return array
     * @Template()
     */
    public function adminDeleteSongAction(Request $request, $id)
    {

        $songManager = $this->get('manager.song');
        $song = $songManager->getOne($id);
        $songManager->deleteSong($song);
        return new JsonResponse(array(
            'success' => true,
            'message' => 'Deleted Successfully'
        ));
    }
}
