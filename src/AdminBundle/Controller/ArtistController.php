<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\ArtistCreateType;
use AppBundle\Document\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArtistController extends Controller
{
    /**
     * @Route("/", name="adminArtists")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $artistManager  = $this->get('manager.artist');
        $artists        = $artistManager->getAll();

        if($request->isXmlHttpRequest()) {

        }
        //normal request
        return array('artists' => $artists);
    }

    /**
     * @Route("/edit/{artistId}", name="adminEditArtist")
     * @Template()
     */
    public function editAction(Request $request, $artistId)
    {
        $artistManager  = $this->get('manager.artist');
        $mediaManager   = $this->get('manager.media');
        $mainWebSite    = $this->getParameter('app_main_web_site');
        $artist         = $artistManager->getOneByArtistId($artistId);
        $options    = array();
        $form       = $this->get('form.factory')->create(new ArtistCreateType(), $artist, $options);

        if($request->getMethod() == 'POST') {
            $form->submit($request->get($form->getName()), false);

            if($form->isValid()) {
                error_log('songs => ' . count($artist->getSongs()));
                $files = $request->files->get('artist');

                if(!empty($files) && isset($files['imageFile'])) {
                    $imageHiddenURL = $mediaManager->upload($files['imageFile'], 'images', true);
                    $artist->setImage($imageHiddenURL);
                }

                if(!empty($files) && isset($files['bannerFile'])) {
                    $bannerHiddenURL = $mediaManager->upload($files['bannerFile'], 'images', true);
                    $artist->setBanner($bannerHiddenURL);
                }
                $artistManager->update($artist);
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'Updated ' . $artist->getName() . ' \'s profile'
                ));
            }
        }

        return array(
            'form'              => $form->createView(),
            'artist'            => $artist,
            'main_web_site'     => $mainWebSite
        );
    }

    /**
     * @Route("/create", name="adminCreateArtist")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $artistManager  = $this->get('manager.artist');
        $mediaManager   = $this->get('manager.media');
        $artist         = new Artist();
        $options    = array();
        $form       = $this->get('form.factory')->create(new ArtistCreateType(), $artist, $options);

        if($request->getMethod() == 'POST') {
            $form->submit($request->get($form->getName()), false);

            if($form->isValid()) {

                $files = $request->files->get('artist');
                if(!empty($files) && isset($files['imageFile'])) {
                    $imageHiddenURL = $mediaManager->upload($files['imageFile'], 'images', true);
                    $artist->setImage($imageHiddenURL);

                }

                if(!empty($files) && isset($files['bannerFile'])) {
                    $bannerHiddenURL = $mediaManager->upload($files['bannerFile'], 'images', true);
                    $artist->setBanner($bannerHiddenURL);
                }

                $artistManager->update($artist);
                return new JsonResponse(array(
                    'success'   => true,
                    'path'      => $this->generateUrl('adminEditArtist', array('artistId' => $artist->getArtistId()))
                ));
            }
        }

        return array(
            'form'      => $form->createView(),
            'artist'    => $artist,
        );
    }

    /**
     * @Route("/{artistId}/albums", name="adminArtistAlbums")
     * @Template()
     */
    public function listAlbumsAction(Request $request, $artistId)
    {
        $artistManager  = $this->get('manager.artist');
        $artist         = $artistManager->getOneByArtistId($artistId);
        return array(
            'artist' => $artist
        );
    }

    /**
     * @Route("/artists/{artistId}/albums/create", name="adminCreateAlbums")
     * @Template()
     */
    public function createAlbumAction(Request $request, $artistId)
    {
        $artistManager  = $this->get('manager.artist');
        $artist         = $artistManager->getOneByArtistId($artistId);
        $albumManager = $this->get('manager.album');
        //$albums = $albumManager->getByArtist($artist);

        return array(
            'artist' => $artist
        );
    }


    private function getErrorMessages($form)
    {
        $errors = array();
        if ($form->count() > 0) {
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        } else {
            foreach ($form->getErrors(true) as $key => $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }
}
