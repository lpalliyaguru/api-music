<?php
/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Blog\AdminBundle\Security;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Blog\AdminBundle\Document\User;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

class OAuthUserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    protected $encoderFactory;
    /**
     * Constructor
     *
     * @param Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     */
    public function __construct(ManagerRegistry $manager, EncoderFactoryInterface $encoderFactory)
    {
        $this->documentManager  = $manager->getManager();
        $this->encoderFactory   = $encoderFactory;
        $this->repository       = $manager->getRepository('BlogAdminBundle:User');
    }
    /**
     * Creates a new user from an OAuth response
     *
     * @todo Should this flush?
     *
     * @param \HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface $response
     *
     * @return Insead\TrackerBundle\Document\User;
     * @throws \RuntimeException
     */
    protected function createUserFromResponse(UserResponseInterface $response)
    {

        if (!$response->getUsername()) {
            // TODO: Enumerate response errors?
            throw new \RuntimeException('Unable to authenticate. An error occurred during OAuth authentication.');
        }
        $user = new User();
        $user->setUsername(sprintf('%s-%s', $response->getResourceOwner()->getName(), $response->getUsername()));
        $user->setName((string) $response->getRealName());
        $user->addRole('ROLE_UER');
        $user->setEmail($response->getEmail());
        $user->setEnabled(true);
        $user->setAvatar($response->getProfilePicture());
        $user->setGithubID($response->getUsername());
        $factory = $this->encoderFactory->getEncoder($user);
        $user->setPassword($factory->encodePassword(md5(uniqid(mt_rand(), true)), $user->getSalt()));
        $this->documentManager->persist($user);
        $this->documentManager->flush();
        return $user;
    }
    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @param \HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface $response
     *
     * @return Insead\TrackerBundle\Document\User
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            $user = $this->loadUserByUsername(sprintf('%s-%s', $response->getResourceOwner()->getName(), $response->getUsername()));
        } catch (UsernameNotFoundException $e) {
            $user = $this->createUserFromResponse($response);
        }
        return $user;
    }
    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Blog\AdminBundle\Document\User';
    }
    /**
     * @param string $username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {

        $user = $this->repository->loadUserByUsername($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }
    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->repository->refreshUser($user);
    }
}