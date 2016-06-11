<?php

namespace AppBundle\Document;

use AppBundle\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="AppBundle\Document\Repository\AccessTokenRepository")
 * @ODM\HasLifecycleCallbacks
 *
 */
class AccessToken
{
    const ISSUER = 'music.local';

    /**
     * @ODM\Id
     * @Exclude()
     */
    private $id;

    /**
     * @ODM\String
     */
    private $accessToken;

    /**
     * @ODM\String
     */
    private $refreshToken;

    /**
     * @ODM\ReferenceOne(targetDocument="User")
     * @Exclude()
     */
    private $user;

    /**
     * @ODM\Date
     */
    private $expires;

    /**
     * @ODM\Date
     * @Exclude()
     */
    private $created;

    /**
     * @ODM\String
     * @Exclude()
     */
    private $device;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function setExpires($date)
    {
        $this->expires = $date;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($date)
    {
        $this->created = $date;
    }

    public function getDevice()
    {
        return $this->created;
    }

    public function setDevice($device)
    {
        $this->device = $device;
    }
}
