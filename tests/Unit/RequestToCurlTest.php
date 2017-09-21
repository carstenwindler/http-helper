<?php

use Zend\Diactoros\Request;
use Zend\Diactoros\Stream;
use PHPUnit\Framework\TestCase;

class RequestToCurlTest extends TestCase
{
    /**
     * @test
     */
    public function toCurlRequest()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'GET'
        );

        $this->assertEquals(
            "curl -X GET http://www.carstenwindler.de -H 'Host: www.carstenwindler.de'",
            requestToCurl($request)
        );
    }

    /**
     * @test
     */
    public function toCurlRequestWithBody()
    {
        $body = new Stream('php://memory', 'w');
        $body->write('body');

        $request = new Request(
            'http://www.carstenwindler.de',
            'POST',
            $body
        );

        $this->assertEquals(
            "curl -X POST http://www.carstenwindler.de -H 'Host: www.carstenwindler.de' -d 'body'",
            requestToCurl($request)
        );
    }

    /**
     * @test
     */
    public function toCurlRequestWithHeader()
    {
        $body = new Stream('php://memory', 'w');

        $request = new Request(
            'http://www.carstenwindler.de',
            'POST',
            $body,
            ['Content-Type' => 'text/xml']
        );

        $this->assertEquals(
            "curl -X POST http://www.carstenwindler.de -H 'Content-Type: text/xml' -H 'Host: www.carstenwindler.de'",
            requestToCurl($request)
        );
    }
}
