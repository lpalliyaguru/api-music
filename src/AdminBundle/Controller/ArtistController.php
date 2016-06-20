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
     * @Route("/artists", name="adminArtists")
     * @Template()
     */
    public function indexAction()
    {
        $artistManager = $this->get('manager.artist');
        $artists = $artistManager->getAll();
        return array('artists' => $artists);
    }

    /**
     * @Route("/artists/edit/{artistId}", name="adminEditArtist")
     * @Template()
     */
    public function editAction(Request $request, $artistId)
    {
        $artistManager  = $this->get('manager.artist');
        $mediaManager = $this->get('manager.media');
        $mainWebSite    = $this->getParameter('app_main_web_site');

        $s3Bucket       = $this->getParameter('amazon_s3_bucket_name_assets');
        $artist         = $artistManager->getOneByArtistId($artistId);
        $options    = array();
        $form       = $this->get('form.factory')->create(new ArtistCreateType(), $artist, $options);

        if($request->getMethod() == 'POST') {
            $form->submit($request->get($form->getName()), false);

            if($form->isValid()) {

                $files = $request->files->get('artist');
                if(!empty($files) && isset($files['imageFile'])) {
                    $imageHiddenURL = $mediaManager->upload($files['imageFile'], 'images', true);
                    $artist->setImage($imageHiddenURL);
                    error_log('image' . $imageHiddenURL);
                }

                if(!empty($files) && isset($files['bannerFile'])) {
                    $bannerHiddenURL = $mediaManager->upload($files['bannerFile'], 'images', true);
                    $artist->setBanner($bannerHiddenURL);
                    error_log('banner ' . $bannerHiddenURL);

                }
                $artistManager->update($artist);
            }

        }

        return array(
            'form'              => $form->createView(),
            'artist'            => $artist,
            'main_web_site'     => $mainWebSite
        );
    }

    /**
     * @Route("/artists/create", name="adminCreateArtist")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $artistManager  = $this->get('manager.artist');
        $mediaManager = $this->get('manager.media');
        $mainWebSite    = $this->getParameter('app_main_web_site');

        $s3Bucket       = $this->getParameter('amazon_s3_bucket_name_assets');
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
                    error_log('image' . $imageHiddenURL);
                }

                if(!empty($files) && isset($files['bannerFile'])) {
                    $bannerHiddenURL = $mediaManager->upload($files['bannerFile'], 'images', true);
                    $artist->setBanner($bannerHiddenURL);
                    error_log('banner ' . $bannerHiddenURL);

                }
                $artistManager->update($artist);
            }

        }

        return array(
            'form'              => $form->createView(),
            'artist'            => $artist,
            'main_web_site'     => $mainWebSite
        );
    }

    /**
     * @Route("/artists/{artistId}/albums", name="adminArtistAlbums")
     * @Template()
     */
    public function listAlbumsAction(Request $request, $artistId)
    {
        $artistManager = $this->get('manager.artist');
        $artist = $artistManager->getOneByArtistId($artistId);
        return array('artist' => $artist);
    }

    private function getErrorMessages($form)
    {
        $errors = array();

        if ($form->count() > 0) {
            foreach ($form->all() as $child) {
                /**
                 * @var \Symfony\Component\Form\Form $child
                 */
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        } else {
            /**
             * @var \Symfony\Component\Form\FormError $error
             */
            foreach ($form->getErrors(true) as $key => $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }
}
