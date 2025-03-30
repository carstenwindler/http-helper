<?php

namespace Carstenwindler\HttpHelper\Tests\Unit\Response;

use Laminas\Diactoros\Response\TextResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use PHPUnit\Framework\TestCase;

class ResponseToStringTest extends TestCase
{
    public function test_psr7_to_string()
    {
        $response = new TextResponse(
            'some response',
            400,
            [ 'X-TEST-HEADER' => 'somevalue' ]
        );

        $responseString = response_to_string($response);

        TestCase::assertStringContainsString('HTTP', $responseString);
        TestCase::assertStringContainsString('some response', $responseString);
    }

    public function test_symfony_request_to_string()
    {
        $response = new SymfonyResponse(
            'some response',
            400,
            [ 'X-TEST-HEADER' => 'somevalue' ]
        );

        $responseString = response_to_string($response);

        TestCase::assertStringContainsString('HTTP', $responseString);
        TestCase::assertStringContainsString('some response', $responseString);
    }
}
