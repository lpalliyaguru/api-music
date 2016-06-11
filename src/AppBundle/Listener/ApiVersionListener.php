<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use JMS\Serializer\Serializer;

class ApiVersionListener
{

    public function onKernelRequest(GetResponseEvent $event)
    {

    }
}
