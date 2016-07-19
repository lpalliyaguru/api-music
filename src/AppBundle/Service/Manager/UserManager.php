<?php

namespace AppBundle\Service\Manager;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class UserManager
{
    protected $documentManager;
    protected $repository;
    protected $properties;

    public function __construct(ManagerRegistry $registryManager)
    {
        $this->documentManager  = $registryManager->getManager();
        $this->repository       = $registryManager->getRepository('AppBundle:User');
    }

    public function getOneByUsername($username)
    {
        return $this->repository->findOneByUsername($username);
    }

    public function getOneByEmail($email)
    {
        return $this->repository->findOneBy(array('email' => $email));
    }

    public function save($user)
    {
        $this->documentManager->persist($user);
        $this->documentManager->flush();
        return true;
    }

    public function getOne($id)
    {
        return $this->repository->find(new \MongoId($id));
    }
}