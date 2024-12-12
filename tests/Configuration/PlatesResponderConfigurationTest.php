<?php

namespace MinormousTests\Configuration;

use Minormous\Configuration\AurynConfiguration;
use Minormous\Configuration\PlatesResponderConfiguration;
use Minormous\Formatter\PlatesFormatter;
use Minormous\Responder\FormattedResponder;
use League\Plates\Engine;

class PlatesResponderConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        if (!class_exists(Engine::class)) {
            $this->markTestSkipped('Plates is not installed');
        }

        return [
            new AurynConfiguration,
            new PlatesResponderConfiguration,
        ];
    }

    public function testApply()
    {
        $responder = $this->injector->make(FormattedResponder::class);

        $this->assertArrayHasKey(PlatesFormatter::class, $responder);
        $this->assertSame(1.0, $responder[PlatesFormatter::class]);
    }
}
