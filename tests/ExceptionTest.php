<?php

namespace MinormousTests;

use Minormous\Framework\Exception\HttpException;
use Minormous\Framework\Router;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Diactoros\Response;

class ExceptionTest extends TestCase
{
    public function testHttpNotFound()
    {
        $exception = HttpException::notFound('/');

        $this->assertInstanceOf(HttpException::class, $exception);
        $this->assertEquals(404, $exception->getCode());
    }

    public function testHttpMethodNotAllowed()
    {
        $allowed = ['POST', 'PATCH'];

        $exception = HttpException::methodNotAllowed('/', 'GET', $allowed);

        $this->assertInstanceOf(HttpException::class, $exception);
        $this->assertEquals(405, $exception->getCode());

        $response = $exception->withResponse(new Response);

        $this->assertEquals(implode(',', $allowed), $response->getHeaderLine('Allow'));
    }

    public function testHttpBadRequest()
    {
        $exception = HttpException::badRequest('Cannot parse request');

        $this->assertInstanceOf(HttpException::class, $exception);
        $this->assertEquals(400, $exception->getCode());

    }
}
