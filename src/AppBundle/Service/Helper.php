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

    public function __construct($artistManager)
    {
        $this->artistManager = $artistManager;
    }

    public function isExistsId($id, $type)
    {
        if($type == 'artist') {
            $artist = $this->artistManager->getOneByArtistId($id);
            if($artist) {
                return true;
            }
        }

        return false;
    }
}