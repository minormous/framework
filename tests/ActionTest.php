<?php

namespace MinormousTests;

use Minormous\Framework\Action;
use Minormous\Adr\DomainInterface;
use Minormous\Adr\InputInterface;
use Minormous\Adr\ResponderInterface;
use Minormous\Framework\Input;
use Minormous\Framework\Responder\ChainedResponder;
use PHPUnit_Framework_TestCase as TestCase;

class ActionTest extends TestCase
{
    public function testInstance()
    {
        $domain = get_class($this->getMock(DomainInterface::class));
        $action = new Action($domain);

        $this->assertSame($domain, $action->getDomain());
        $this->assertSame(Input::class, $action->getInput());
        $this->assertSame(ChainedResponder::class, $action->getResponder());

        $responder = get_class($this->getMock(ResponderInterface::class));
        $action = new Action($domain, $responder);

        $this->assertSame($responder, $action->getResponder());

        $input = get_class($this->getMock(InputInterface::class));
        $action = new Action($domain, null, $input);

        $this->assertSame($input, $action->getInput());
        $this->assertSame(ChainedResponder::class, $action->getResponder());
    }
}
