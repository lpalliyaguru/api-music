<?php
/**
 * Created by PhpStorm.
 * User: M.LASANTHA
 * Date: 6/21/2016
 * Time: 12:04 AM
 */

namespace AppBundle\Service;


class Helper
{
    private $artistManager;
    private $albumManager;

    public function __construct($artistManager, $albumManager)
    {
        $this->artistManager    = $artistManager;
        $this->albumManager     = $albumManager;
    }

    public function isExistsId($id, $type)
    {
        if($type == 'artist') {
            $artist = $this->artistManager->getOneByArtistId($id);
            if($artist) {
                return true;
            }
        }
        else if($type == 'album'){
            $album = $this->albumManager->getOneByAlbumId($id);
            if($album) {
                return true;
            }
        }

        return false;
    }
}