<?php

namespace Carstenwindler\HttpHelper\Tests\Unit;

use Zend\Diactoros\Request;
use Zend\Diactoros\Stream;
use PHPUnit\Framework\TestCase;

class RequestToCurlTest extends TestCase
{
    /**
     * @test
     */
    public function to_curl()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'GET'
        );

        $this->assertEquals(
            "curl -X GET http://www.carstenwindler.de -H 'Host: www.carstenwindler.de'",
            request_to_curl($request)
        );
    }

    /**
     * @test
     */
    public function to_curl_with_body()
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
            request_to_curl($request)
        );
    }

    /**
     * @test
     */
    public function to_curl_with_header()
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
            request_to_curl($request)
        );
    }
}
