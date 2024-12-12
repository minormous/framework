<?php

namespace MinormousTests\Fake;

use Minormous\Adr\DomainInterface;
use Minormous\Framework\Payload;

class FakeDomain implements DomainInterface
{

    public function __invoke(array $input)
    {
        return (new Payload())
            ->withStatus(Payload::STATUS_OK)
            ->withOutput(['success' => true, 'input' => $input]);
    }
}
