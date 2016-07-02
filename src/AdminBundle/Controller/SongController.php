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

class SongController extends Controller
{

    /**
     * @Route("/create/new", name="adminAddSong")
     * @Template()
     */
    public function adminAddSongAction(Request $request)
    {
        return array();
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
