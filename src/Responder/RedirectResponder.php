<?php

namespace Minormous\Framework\Responder;

use Minormous\Adr\PayloadInterface;
use Minormous\Adr\ResponderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RedirectResponder implements ResponderInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PayloadInterface $payload
    ) {
        $location = $payload->getSetting('redirect');

        if (!empty($location)) {
            $response = $response->withHeader('Location', $location);
        }

        return $response;
    }
}
