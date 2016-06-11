<?php

namespace AppBundle\Security\User;

use AppBundle\Document\AccessToken;
use AppBundle\Security\User\ApiUserProviderInterface;
use AppBundle\Service\Manager\ApiTokenManager;
use AppBundle\Service\Manager\UserManager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiUserProvider implements  ApiUserProviderInterface
{
    protected $documentManager;

    protected $userManager;

    public function __construct(ManagerRegistry $registryManager, UserManager $userManager, ApiTokenManager $tokenManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->userManager      = $userManager;
        $this->tokenManager     = $tokenManager;
    }

    /**
     * @param string $token
     * @param string $device
     * @return UserInterface
     */
    public function loadUserByTokenAndDevice($token, $device)
    {
        $this->tokenManager->validateAccessToken($token);

        $token = $this->tokenManager->getTokenByAccessTokenAndDevice($token, $device);

        if($token instanceof AccessToken) {
            return $token->getUser();
        }

        return null;
    }

    public function loadUserByUsername($username)
    {
        return $this->userManager->getByUsername();
    }

    public function refreshUser(UserInterface $user)
    {
        return null;
    }

    public function supportsClass($class)
    {
        return true;
    }

}
