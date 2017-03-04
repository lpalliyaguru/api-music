<?php

namespace ApiBundle\Controller;

//use AppBundle\Document\AccessToken;
use AppBundle\Document\User;
use AppBundle\Form\AuthSocialType;
use AppBundle\Form\RegisterType;

use Facebook\Facebook;
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
                return array(
                    'success' => false,
                    'message' => 'Login failed. Username or password is incorrect.'
                );
            }

            return array( 'access_token' => $accessToken, 'success' => true, 'user' => $user);
        }

        return array(
            'success' => false,
            'message' => 'Login failed. Username or password is incorrect.'
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
    /**
     * @Rest\Options("auth/social")
     */
    public function optionsAuthSocialAction(Request $request) {  }

    /**
     * @Rest\Post("auth/social")
     */
    public function postAuthSocialAction(Request $request)
    {
        $form           = $this->get('form.factory')->create(new AuthSocialType());
        $authManager    = $this->get('manager.auth');
        $userManager    = $this->get('manager.user');
        $tokenManager   = $this->get('manager.api_token');
        $userAgent      = $request->headers->get('User-Agent');
        $form->handleRequest($request);

        if($form->isValid()) {
            $formData = $form->getData();
            $provider = $formData['provider'];
            if($provider == 'facebook') {
                if($authManager->authenticateFb($formData['token'], $formData['uid'])) {
                    $user = $userManager->getOneByUsername($formData['uid']);
                    $profilePic = $authManager->getFBProfilePicture($formData['token']);
                    if(is_null($user)) {
                        $user = $userManager->populateUser($formData);
                    }
                    $user->setProfilePic($profilePic);
                    $userManager->save($user);
                    $token = $tokenManager->createNewToken($user, $userAgent);
                }
            }
            return array(
                'access_token' => $token,
                'user'  => $user,
                'success' => true
            );
        }
        return array(
            'success'   => false,
            'message'   => 'Unable to process data. Please try again.'
        );
    }
}
