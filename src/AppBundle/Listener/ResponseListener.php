<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{

    private $mainWebSite;

    private $acceptedHttpMethods;

    private $acceptHeaders;

    public function __construct($mainWebSite, $acceptedHttpMethods, $httpAcceptHeaders)
    {
        $this->mainWebSite          = $mainWebSite;
        $this->acceptedHttpMethods  = $acceptedHttpMethods;
        $this->acceptHeaders        = $httpAcceptHeaders;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->set('Access-Control-Allow-Origin', $this->mainWebSite);
        $event->getResponse()->headers->set('Access-Control-Allow-Methods', $this->acceptedHttpMethods);
		$event->getResponse()->headers->set('Allow', $this->acceptedHttpMethods);
        $event->getResponse()->headers->set('Access-Control-Allow-Credentials', true);
		$event->getResponse()->headers->set('Access-Control-Allow-Headers', implode(', ',$this->acceptHeaders));
    }
}