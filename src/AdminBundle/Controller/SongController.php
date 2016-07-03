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
                foreach($artistIds as $id) {
                    $artist = $artistManager->getOne($id);
                    $song->addArtist($artist);
                }

                $resourceURL = $songManager->manageSongSource($request, $webDir);
                $song->setUrl($resourceURL);
                $songManager->update($song);
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'Created ' . $song->getDisplayName(),
                   // 'albumUrl' => $this->generateUrl('adminAlbum', array('albumId' => $album->getAlbumId()))
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
}
