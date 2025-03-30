<?php

namespace Carstenwindler\HttpHelper\Tests\Unit\Request;

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Stream;
use PHPUnit\Framework\TestCase;

class RequestToCurlTest extends TestCase
{
    public function test_to_curl()
    {
        $request = new Request(
            'https://www.carstenwindler.de',
            'GET'
        );

        TestCase::assertEquals(
            "curl -i -X GET https://www.carstenwindler.de -H 'Host: www.carstenwindler.de'",
            request_to_curl($request)
        );
    }

    public function test_to_curl_with_body()
    {
        $body = new Stream('php://memory', 'w');
        $body->write('body');

        $request = new Request(
            'https://www.carstenwindler.de',
            'POST',
            $body
        );

        TestCase::assertEquals(
            "curl -i -X POST https://www.carstenwindler.de -H 'Host: www.carstenwindler.de' -d 'body'",
            request_to_curl($request)
        );
    }

    public function test_to_curl_with_header()
    {
        $body = new Stream('php://memory', 'w');

        $request = new Request(
            'http://www.carstenwindler.de',
            'POST',
            $body,
            ['Content-Type' => 'text/xml']
        );

        TestCase::assertEquals(
            "curl -i -X POST http://www.carstenwindler.de -H 'Content-Type: text/xml' -H 'Host: www.carstenwindler.de'",
            request_to_curl($request)
        );
    }
}
