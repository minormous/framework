<?php

namespace MinormousTests\Configuration;

use Minormous\Adr\PayloadInterface;
use Minormous\Configuration\PayloadConfiguration;
use Minormous\Payload;

class PayloadConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        return [
            new PayloadConfiguration,
        ];
    }

    public function testApply()
    {
        $payload = $this->injector->make(PayloadInterface::class);

        $this->assertInstanceOf(Payload::class, $payload);
    }
}
