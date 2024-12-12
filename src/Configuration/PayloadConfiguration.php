<?php

namespace Minormous\Framework\Configuration;

use Auryn\Injector;
use Minormous\Adr\PayloadInterface;
use Minormous\Framework\Payload;

class PayloadConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Injector $injector)
    {
        $injector->alias(
            PayloadInterface::class,
            Payload::class
        );
    }
}

