<?php

namespace Minormous\Configuration;

use Auryn\Injector;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;

class DiactorosConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Injector $injector)
    {
        // Wrong type hint. see https://github.com/relayphp/Relay.Relay/issues/25
        $injector->alias(
            RequestInterface::class,
            ServerRequest::class
        );

        $injector->alias(
            ResponseInterface::class,
            Response::class
        );

        $injector->alias(
            ServerRequestInterface::class,
            ServerRequest::class
        );

        $injector->delegate(
            ServerRequest::class,
            [ServerRequestFactory::class, 'fromGlobals']
        );
    }
}
