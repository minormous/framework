<?php

namespace MinormousTests\Configuration;

use Minormous\Configuration\AurynConfiguration;
use Minormous\Configuration\RelayConfiguration;
use Relay\Relay;

class RelayConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        return [
            new AurynConfiguration,
            new RelayConfiguration,
        ];
    }

    public function testApply()
    {
        $dispatcher = $this->injector->make(Relay::class);

        $this->assertInstanceOf(Relay::class, $dispatcher);
    }
}
