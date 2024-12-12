<?php

namespace Minormous\Framework\Responder;

use Minormous\Adr\PayloadInterface;
use Minormous\Adr\ResponderInterface;
use Minormous\Framework\Exception\ResponderException;
use Minormous\Framework\Resolver\ResolverTrait;
use Minormous\Structure\Set;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Relay\ResolverInterface;

class ChainedResponder extends Set implements ResponderInterface
{
    use ResolverTrait;

    /**
     * @param ResolverInterface $resolver
     * @param array $responders
     */
    public function __construct(
        ResolverInterface $resolver,
        array $responders = [
            FormattedResponder::class,
            RedirectResponder::class,
            StatusResponder::class,
        ]
    ) {
        $this->resolver = $resolver;

        parent::__construct($responders);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PayloadInterface $payload
    ) {
        foreach ($this as $responder) {
            $responder = $this->resolve($responder);
            $response = $responder($request, $response, $payload);
        }

        return $response;
    }

    /**
     * @inheritDoc
     *
     * @throws ResponderException
     *  If $classes does not implement the correct interface.
     */
    protected function assertValid(array $classes)
    {
        parent::assertValid($classes);

        foreach ($classes as $responder) {
            if (!is_subclass_of($responder, ResponderInterface::class)) {
                throw ResponderException::invalidClass($responder);
            }
        }
    }
}
