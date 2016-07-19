<?php

namespace Casebox\RpcBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RpcApiSubscriber
 */
class RpcApiSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Implements attachRpcApi().
     */
    public function attachRpcApi()
    {
        $default = $this->container->get('casebox_rpc.service.rpc_api_config_service')->getDefaultConfig();
        $this->container->get('casebox_rpc.service.rpc_api_config_service')->setConfig($default);
    }

    /**
     * @return array
     */
    static function getSubscribedEvents()
    {
        return [
            'attachRpcApi' => 'attachRpcApi',
        ];
    }

    /**
     * @param Container $container
     *
     * @return RpcApiSubscriber $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}
