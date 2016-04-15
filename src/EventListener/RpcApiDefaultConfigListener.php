<?php

namespace Casebox\RpcBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RpcApiDefaultConfigListener
 */
class RpcApiDefaultConfigListener
{
    /**
     * @var Container
     */
    private $container;

    /**
     * RequestListener constructor
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $default = $this->container->get('casebox_rpc.service.rpc_api_config_service')->getDefaultConfig();
        $this->container->get('casebox_rpc.service.rpc_api_config_service')->setConfig($default);
    }
}
