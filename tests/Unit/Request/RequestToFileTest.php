<?php

namespace Carstenwindler\HttpHelper\Tests\Unit\Request;

use Laminas\Diactoros\Request;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

function time() {
    return RequestToFileTest::$now ?: \time();
}

class RequestToFileTest extends TestCase
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
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        $_SERVER['DOCUMENT_ROOT'] = vfsStream::url('root/webroot');

        $filename = request_to_file($request);

        TestCase::assertTrue(file_exists(vfsStream::url('root/webroot/request.http')));
        TestCase::assertEquals(vfsStream::url('root/webroot/request.http'), $filename);

        TestCase::assertStringContainsString('Host: www.carstenwindler.de', file_get_contents($filename));
    }

    /**
     * @test
     */
    public function to_file_with_given_path()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        $filename = request_to_file($request, vfsStream::url('root'));

        TestCase::assertTrue(file_exists(vfsStream::url('root/request.http')));
        TestCase::assertEquals(vfsStream::url('root/request.http'), $filename);

        TestCase::assertStringContainsString('Host: www.carstenwindler.de', file_get_contents($filename));
    }

    /**
     * @test
     */
    public function to_file_will_append_if_file_exists()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        $filename = request_to_file($request, vfsStream::url('root'));

        $httpRequest = file_get_contents($filename);
        $expectedFile = $httpRequest;

        $filename = request_to_file($request, vfsStream::url('root'));

        $expectedFile .= "\n\n### " . date(DATE_RFC822, time()) . "\n\n" . $httpRequest;

        TestCase::assertTrue(file_exists(vfsStream::url('root/request.http')));
        TestCase::assertEquals(vfsStream::url('root/request.http'), $filename);

        TestCase::assertEquals($expectedFile, file_get_contents($filename));
    }
}
