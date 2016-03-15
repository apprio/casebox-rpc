<?php

namespace Casebox\RpcBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListener
 */
class ExceptionListener
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
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        $extType = $event->getRequest()->get('extType');

        if ($e instanceof \Exception && $extType == 'rpc') {
            $params = [
                'type' => 'rpc',
                'tid' => (!empty($event->getRequest()->get('extTID'))) ? $event->getRequest()->get('extTID') : null,
                'action' => $event->getRequest()->get('extAction'),
                'method' => $event->getRequest()->get('extMethod'),
                'result' => [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => null,
                ],
            ];

            $response = new Response(json_encode($params));
            $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
            $response->headers->set('X-Status-Code', 200);
            $event->setResponse($response);
        }
    }
}
