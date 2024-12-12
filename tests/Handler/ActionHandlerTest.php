<?php
namespace MinormousTests\Handler;

use Minormous\Framework\Action;
use Minormous\Configuration\AurynConfiguration;
use Minormous\Framework\Handler\ActionHandler;
use MinormousTests\Configuration\ConfigurationTestCase;
use MinormousTests\Fake\FakeDomain;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ActionHandlerTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        return [
            new AurynConfiguration,
        ];
    }

    public function testHandle()
    {
        $request = $this->injector->make(ServerRequest::class);
        $response = $this->injector->make(Response::class);
        $handler = $this->injector->make(ActionHandler::class);

        $action = new Action(FakeDomain::class);

        $request = $request->withAttribute(ActionHandler::ACTION_ATTRIBUTE, $action);
        $request = $request->withAttribute('test', true);

        $response = $handler($request, $response, function ($request, $response) {
            $this->assertInstanceOf(Response::class, $response);
            return $response;
        });

        $body = json_decode($response->getBody(), true);

        $this->assertTrue($body['success']);
        $this->assertTrue($body['input']['test']);
    }
}
