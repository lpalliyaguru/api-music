<?php

namespace AppBundle\Service\Manager;


use Facebook\Facebook;

class AuthManager
{
    private $fb;
    private $gl;
    private $logger;

    public function __construct($fbConfigs, $logger)
    {
        $this->fb = new Facebook($fbConfigs);
        $this->logger = $logger;
    }

    public function getFBProfilePicture($token)
    {
        $pictureRes = $this->fb->get('/me/picture?redirect=false&type=large', $token);
        $picture = $pictureRes->getGraphUser();
        error_log($picture['url']);
        return $picture['url'];
    }

    public function authenticateFB($token, $id)
    {
        try {
            $response   = $this->fb->get('/me', $token);
            $body       = $response->getDecodedBody();
            return $body['id'] == $id;

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $this->logger->error(sprintf('Unable to login user %s ; %s', $id, $e->getMessage()));
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->logger->error(sprintf('Unable to login user %s ; %s', $id, $e->getMessage()));
        }
        catch(\Exception $e){
            $this->logger->error(sprintf('Unable to login user %s ; %s', $id, $e->getMessage()));
        }
        return false;
    }
}