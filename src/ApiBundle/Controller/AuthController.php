<?php

namespace ApiBundle\Controller;

//use AppBundle\Document\AccessToken;
use AppBundle\Document\User;
use AppBundle\Form\RegisterType;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends FOSRestController
{
    /**
     * CORS setting
     * @Rest\Options("login")
     */
    public function optionsLoginAction(Request $request)
    {
    }

    /**
     * @Rest\Post("login")
     */
    public function postLoginAction(Request $request)
    {
        $username   = $request->request->get('username');
        $password   = $request->request->get('password');
        $userAgent  = $request->headers->get('User-Agent');

        $encoderFactory = $this->get('security.encoder_factory');
        $userManager    = $this->get('manager.user');
        $tokenManager   = $this->get('manager.api_token');
        $user           = $userManager->getOneByUsername($username);

        if($user) {
            $encoder        = $encoderFactory->getEncoder($user);
            $validPassword  = $encoder->isPasswordValid(
                $user->getPassword(),
                $password,
                $user->getSalt()
            );

            if($validPassword) {
                $accessToken = $tokenManager->createNewToken($user, $userAgent);
            }
            else {
                throw $this->createAccessDeniedException('Access Deined');
            }

            return array( 'access_token' => $accessToken, 'success' => true, 'user' => $user);
        }

        return array(
            'success' => false,
            'message' => 'Login failed'
        );

    }

    /**
     * CORS setting
     * @Rest\Options("register")
     */
    public function optionsRegisterAction(Request $request)
    {
    }

    /**
     * @Rest\Post("register")
     */
    public function postRegisterAction(Request $request)
    {
        $user           = new User();
        $userManager    = $this->get('manager.user');
        $tokenManager   = $this->get('manager.api_token');
        $encryptor      = $this->get('app.encryptor');
        $encoderFactory = $this->get('security.encoder_factory');
        $form           = $this->get('form.factory')->create(new RegisterType(), $user);
        $json_data      = json_decode($request->getContent(), true);
        $form->bind($json_data);

        if($form->isValid()) {
            $encoder    = $encoderFactory->getEncoder($user);
            $salt       = $encryptor->encrypt($user->getName());
            $user->setSalt($salt);
            $password   = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
            $user->setPassword($password);
            $user->setProfilePic('/images/avatar.png');
            $userManager->save($user);

            return array(
                'success' => true,
                'user'    => $user
            );
        }

        return array(
            'success'   => false,
            'errors'    => $form->getErrors(true, false)
        );

    }
}
