<?php

namespace MinormousTests\Configuration;

use Minormous\Configuration\AurynConfiguration;
use Minormous\Configuration\PlatesConfiguration;
use Minormous\Env;
use League\Plates\Engine;

class PlatesConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        if (!class_exists(Engine::class)) {
            $this->markTestSkipped('Plates is not installed');
        }

        $env = new Env([
            'PLATES_DIRECTORY' => sys_get_temp_dir(),
        ]);

        return [
            new PlatesConfiguration($env),
        ];
    }

    public function testApply()
    {
        $engine = $this->injector->make(Engine::class);
        $this->assertInstanceOf(Engine::class, $engine);
    }
}
