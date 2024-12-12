<?php

namespace MinormousTests\Responder;

use MinormousTests\Configuration\ConfigurationTestCase;
use Minormous\Adr\PayloadInterface;
use Minormous\Adr\ResponderInterface;
use Minormous\Configuration\AurynConfiguration;
use Minormous\Exception\ResponderException;
use Minormous\Responder\ChainedResponder;
use Minormous\Responder\FormattedResponder;
use Minormous\Responder\RedirectResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChainedResponderTest extends ConfigurationTestCase
{
    /**
     * @var ChainedResponder
     */
    private $responder;

    protected function getConfigurations()
    {
        return [
            new AurynConfiguration,
        ];
    }

    public function setUp()
    {
        parent::setUp();

        $this->responder = $this->injector->make(ChainedResponder::class);
    }

    public function testDefaultResponders()
    {
        $responders = $this->responder->toArray();

        $this->assertContains(FormattedResponder::class, $responders);
        $this->assertContains(RedirectResponder::class, $responders);
    }

    public function testInvalidResponder()
    {
        $this->setExpectedExceptionRegExp(
            ResponderException::class,
            '/Responder class .* must implement .*ResponderInterface/i'
        );

        $this->responder->withValue(get_class($this));
    }

    public function testAddResponder()
    {
        $responder = $this->getMockResponder();
        $chained = $this->responder->withValue($responder);

        $this->assertContains($responder, $chained);

        $response = $this->execute($chained);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testResponse()
    {
        $response = $this->execute($this->responder);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    private function getMockResponder()
    {
        $responder = $this->getMockBuilder(ResponderInterface::class)->getMock();
        $responder
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->isInstanceOf(ServerRequestInterface::class),
                $this->isInstanceOf(ResponseInterface::class),
                $this->isInstanceOf(PayloadInterface::class)
            )
            ->will($this->returnArgument(1));

        return $responder;
    }

    private function execute($responder)
    {
        $request  = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $payload  = $this->getMockBuilder(PayloadInterface::class)->getMock();

        return call_user_func($responder, $request, $response, $payload);
    }
}
