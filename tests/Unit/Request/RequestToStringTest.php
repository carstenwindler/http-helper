<?php

namespace Carstenwindler\HttpHelper\Tests\Unit\Request;

use Zend\Diactoros\Request as Psr7Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use PHPUnit\Framework\TestCase;

class RequestToStringTest extends TestCase
{
    /**
     * @test
     */
    public function psr7_to_string()
    {
        $request = new Psr7Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        // not testing Diactoros in detail here obviously, just a quick check a
        // basic http request is returned
        TestCase::assertEquals(
            "POST / HTTP/1.1\r\nHost: www.carstenwindler.de",
            request_to_string($request)
        );
    }

    /**
     * @test
     */
    public function symfony_request_to_string()
    {
        $request = SymfonyRequest::create(
            'http://www.carstenwindler.de',
            'POST',
            [],
            [],
            [],
            // symfony adds a lot of stuff by default, which is cool, but what we don't want to test
            [
                'HTTP_ACCEPT' => null,
                'HTTP_ACCEPT_LANGUAGE' => null,
                'HTTP_ACCEPT_CHARSET' => null,
                'HTTP_USER_AGENT' => null
            ]
        );

        TestCase::assertEquals(
            "POST / HTTP/1.1\r\n" .
            "Accept:          \r\n" .
            "Accept-Charset:  \r\n" .
            "Accept-Language: \r\n" .
            "Content-Type:    application/x-www-form-urlencoded\r\n" .
            "Host:            www.carstenwindler.de\r\n" .
            "User-Agent:      \r\n\r\n",
            request_to_string($request)
        );
    }

    /**
     * @test
     */
    public function returns_unknown_if_request_is_not_supported()
    {
        // just some random nonsense
        $request = new \stdClass;
        $request->host = 'http://www.carstenwindler.de';

        TestCase::assertEquals(
            "unknown\r\n",
            request_to_string($request)
        );
    }
}
