<?php

namespace MinormousTests;

use Auryn\Injector;
use Minormous\Framework\Application;
use Minormous\Configuration\ConfigurationInterface;
use Minormous\Configuration\ConfigurationSet;
use Minormous\Framework\Directory;
use Minormous\Framework\Middleware\MiddlewareSet;
use PHPUnit_Framework_TestCase as TestCase;
use ReflectionObject;
use Relay\MiddlewareInterface;
use Relay\Relay;

class ApplicationTest extends TestCase
{
    private function assertApplication($app)
    {
        $appObject = new ReflectionObject($app);

        $props = [
            'injector' => Injector::class,
            'configuration' => ConfigurationSet::class,
            'middleware' => MiddlewareSet::class,
            'routing' => null,
        ];

        foreach ($props as $name => $expected) {
            $prop = $appObject->getProperty($name);
            $prop->setAccessible(true);
            $value = $prop->getValue($app);

            if ($expected) {
                $this->assertInstanceOf($expected, $value, $name);
            }

            $props[$name] = $value;
        }

        if (!empty($props['routing'])) {
            $this->assertTrue(is_callable($props['routing']));
        }
    }

    public function testBuild()
    {
        $app = Application::build();
        $this->assertApplication($app);
    }

    public function testCreate()
    {
        $injector = $this->getMock(Injector::class);
        $configuration = $this->getMock(ConfigurationSet::class);
        $middleware = $this->getMock(MiddlewareSet::class);

        $app = new Application($injector, $configuration, $middleware);

        $this->assertApplication($app);
    }

    public function testSetConfiguration()
    {
        $data = [
            $this->getMock(ConfigurationInterface::class),
        ];

        $configuration = $this->getMock(ConfigurationSet::class);
        $configuration
            ->expects($this->once())
            ->method('withValues')
            ->with($data)
            ->willReturn(clone $configuration);

        $app = new Application(null, $configuration);
        $app->setConfiguration($data);

        $this->assertApplication($app);
    }

    public function testSetMiddleware()
    {
        $data = [
            $this->getMock(MiddlewareInterface::class),
        ];

        $middleware = $this->getMock(MiddlewareSet::class);
        $middleware
            ->expects($this->once())
            ->method('withValues')
            ->with($data)
            ->willReturn(clone $middleware);

        $app = new Application(null, null, $middleware);
        $app->setMiddleware($data);

        $this->assertApplication($app);
    }

    public function testSetRouting()
    {
        $app = new Application();

        // Routing can be a closure ...
        $app->setRouting(function () {
        });
        $this->assertApplication($app);

        // ... or a callback
        $app->setRouting([$this, __FUNCTION__]);
        $this->assertApplication($app);
    }

    public function testRun()
    {
        $injector = $this->getMock(Injector::class);
        $middleware = $this->getMock(MiddlewareSet::class);
        $config1 = $this->getMock(ConfigurationInterface::class);
        $config2 = $this->getMock(ConfigurationInterface::class);
        $routing = function () {
        };

        $config1
            ->expects($this->once())
            ->method('apply')
            ->with($injector);

        $config2
            ->expects($this->once())
            ->method('apply')
            ->with($injector);

        $injector
            ->expects($this->once())
            ->method('make')
            ->with(get_class($config1))
            ->willReturn($config1);

        $injector
            ->expects($this->once())
            ->method('share')
            ->with($middleware)
            ->willReturnSelf();

        $injector
            ->expects($this->once())
            ->method('prepare')
            ->with(Directory::class, $routing)
            ->willReturnSelf();

        $injector
            ->expects($this->once())
            ->method('execute')
            ->with(Relay::class);

        $app = Application::build($injector, null, $middleware);
        $app->setConfiguration([get_class($config1), $config2]);
        $app->setRouting($routing);
        $app->run();
    }
}
