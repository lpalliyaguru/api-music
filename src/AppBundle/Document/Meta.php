<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation\Exclude;
/**
 * Class Location
 * @ODM\EmbeddedDocument
 * @package AppBundle\Document
 */
class Meta
{
    const GENRE_BAILA       = 'baila';
    const GENRE_BLUES       = 'blues';
    const GENRE_CLASSICAL   = 'classical';
    const GENRE_COUNTRY     = 'country';
    const GENRE_DANCE       = 'dance';
    const GENRE_ELECTRO     = 'electronic';
    const GENRE_HIPHOP      = 'hip_hop';
    const GENRE_LIGHT       = 'light';
    const GENRE_FOLK        = 'folks';
    const GENRE_ROCK        = 'rock';
    /**
     * @Exclude()
     */
    public static $genres = array(
        self::GENRE_BAILA       => 'Baila',
        self::GENRE_BLUES       => 'Blues',
        self::GENRE_CLASSICAL   => 'Classical',
        self::GENRE_COUNTRY     => 'Country',
        self::GENRE_DANCE       => 'Dance',
        self::GENRE_ELECTRO     => 'Electro',
        self::GENRE_FOLK        => 'Folk',
        self::GENRE_LIGHT       => 'Light',
        self::GENRE_HIPHOP      => 'HipHop',
        self::GENRE_ROCK        => 'Rock',

    );
    /**
     * @ODM\Date
     */
    public $created;

    /**
     * @ODM\Date
     */
    public $updated;
}
