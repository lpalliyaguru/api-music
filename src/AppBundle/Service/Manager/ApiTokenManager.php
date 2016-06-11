<?php

namespace AppBundle\Service\Manager;

use AppBundle\Document\AccessToken;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiTokenManager
{
    protected $documentManager;
    protected $repository;
    protected $jwtKey;

    public function __construct(ManagerRegistry $registryManager, $jwtKey)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:AccessToken');
        $this->jwtKey           = $jwtKey;
    }

    public function save($accessToken)
    {
        $this->documentManager->persist($accessToken);
        $this->documentManager->flush();
    }

    public function getTokenByAccessTokenAndDevice($token, $device)
    {
        return $this->repository->findOneBy(array('accessToken' => $token, 'device' => $device));
    }

    public function createNewToken($user, $device)
    {
        $token      = new AccessToken();
        $now        = new \DateTime();
        $expires    = $now->modify('+1 day');
        $token->setAccessToken($this->createJWTToken($user));
        $token->setUser($user);
        $token->setExpires($expires);
        $token->setDevice($device);
        $token->setRefreshToken($this->createJWTToken($user, 'r'));
        $this->save($token);
        return $token;
    }

    public function createJWTToken($user, $type = 'a')
    {
        $time   = new \DateTime();
        $tokenInfo  = array(
            "iss"       => AccessToken::ISSUER,
            "iat"       => $time->getTimestamp(),
            "name"      => $user->getName(),
            "username"  => $user->getUsername(),
            "type"      => $type
        );

        $jwtToken = JWT::encode($tokenInfo, $this->jwtKey, 'HS256');
        return $jwtToken;
    }

    public function validateAccessToken($token)
    {
        $decodedToken = (array)JWT::decode($token, $this->jwtKey, array('HS256'));

        if(!isset($decodedToken['username'])) {
           throw new AccessDeniedException('Token is invalid');
        }
    }
}
