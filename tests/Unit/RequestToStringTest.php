<?php

use Zend\Diactoros\Request;
use PHPUnit\Framework\TestCase;

class RequestToStringTest extends TestCase
{
    /**
     * @test
     */
    public function toString()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        // not testing Diactoros in detail here obviously, just a quick check a
        // basic http request is returned
        $this->assertEquals(
            "POST / HTTP/1.1\r\nHost: www.carstenwindler.de",
            requestToString($request)
        );
    }
}
