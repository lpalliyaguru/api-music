<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Location
 * @ODM\EmbeddedDocument
 * @package AppBundle\Document
 */
class Social
{
    /**
     * @ODM\String
     */
    public $fb;

    /**
     * @ODM\String
     */
    public $tw;

    /**
     * @ODM\String
     */
    public $gl;

    /**
     * @ODM\String
     */
    public $pn;

    /**
     * @ODM\String
     */
    public $in;
}
