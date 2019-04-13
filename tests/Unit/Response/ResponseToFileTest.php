<?php

namespace Carstenwindler\HttpHelper\Tests\Unit\Response;

use Zend\Diactoros\Response\TextResponse as Psr7Response;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

function time() {
    return ResponseToFileTest::$now ?: \time();
}

class ResponseToFileTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    public static $now;

    public function setUp(): void
    {
        parent::setUp();

        self::$now = time();

        $this->vfsRoot = vfsStream::setup('root');
        vfsStream::create([ 'webroot' => [] ], $this->vfsRoot);
    }

    /**
     * Reset custom time after test
     */
    protected function tearDown(): void
    {
        self::$now = null;
    }

    /**
     * @test
     */
    public function to_file_with_default_name()
    {
        $response = new Psr7Response('some response');

        $_SERVER['DOCUMENT_ROOT'] = vfsStream::url('root/webroot');

        $filename = response_to_file($response);

        TestCase::assertTrue(file_exists(vfsStream::url('root/webroot/response.http')));
        TestCase::assertEquals(vfsStream::url('root/webroot/response.http'), $filename);

        TestCase::assertStringContainsString('some response', file_get_contents($filename));
    }

    /**
     * @test
     */
    public function to_file_with_given_path()
    {
        $response = new Psr7Response('some response');

        $filename = response_to_file($response, vfsStream::url('root'));

        TestCase::assertTrue(file_exists(vfsStream::url('root/response.http')));
        TestCase::assertEquals(vfsStream::url('root/response.http'), $filename);

        TestCase::assertStringContainsString('some response', file_get_contents($filename));
    }

    /**
     * @test
     */
    public function to_file_will_append_if_file_exists()
    {
        $response = new Psr7Response('some response');

        $filename = response_to_file($response, vfsStream::url('root'));

        $httpRequest = file_get_contents($filename);

        $filename = response_to_file($response, vfsStream::url('root'));

        $expectedFile = $httpRequest;
        $expectedFile .= "\n\n### " . date(DATE_RFC822, time()) . "\n\n" . $httpRequest;

        TestCase::assertTrue(file_exists(vfsStream::url('root/response.http')));
        TestCase::assertEquals(vfsStream::url('root/response.http'), $filename);

        TestCase::assertEquals($expectedFile, file_get_contents($filename));
    }
}
