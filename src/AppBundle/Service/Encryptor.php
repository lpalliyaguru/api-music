<?php

namespace AppBundle\Service;


class Encryptor
{
    protected $encryptSalt;

    public function __construct($encryptSalt)
    {
        $this->salt = $encryptSalt;
    }
    public function refreshSalt($newSalt)
    {
        $this->salt = $newSalt;
    }
    /**
     * Encrypt a string
     *
     * @param $string
     * @return string
     */
    public function encrypt($string)
    {
        return \base64_encode(
            \mcrypt_encrypt(
                MCRYPT_RIJNDAEL_256,
                \md5($this->salt),
                $string,
                MCRYPT_MODE_CBC,
                \md5(
                    \md5(
                        $this->salt
                    )
                )
            )
        );
    }
    /**
     * Decrypt a string already encrypt
     *
     * @param $secureKey
     * @return string
     */
    public function decrypt($secureKey)
    {
        return \rtrim(
            \mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256,
                \md5($this->salt),
                \base64_decode($secureKey),
                MCRYPT_MODE_CBC,
                \md5(
                    \md5(
                        $this->salt
                    )
                )
            )
            , "\0");
    }
}