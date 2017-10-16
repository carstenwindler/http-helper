<?php

namespace Carstenwindler\HttpHelper\Tests\Unit\Response;

use Zend\Diactoros\Response\TextResponse as Psr7Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use PHPUnit\Framework\TestCase;

class ResponseToStringTest extends TestCase
{
    /**
     * @test
     */
    public function psr7_to_string()
    {
        $response = new Psr7Response(
            'some response',
            400,
            [ 'X-TEST-HEADER' => 'somevalue' ]
        );

        $responseString = response_to_string($response);

        TestCase::assertContains('HTTP', $responseString);
        TestCase::assertContains('some response', $responseString);
    }

    /**
     * @test
     */
    public function symfony_request_to_string()
    {
        $response = new SymfonyResponse(
            'some response',
            400,
            [ 'X-TEST-HEADER' => 'somevalue' ]
        );

        $responseString = response_to_string($response);

        TestCase::assertContains('HTTP', $responseString);
        TestCase::assertContains('some response', $responseString);
    }
}
