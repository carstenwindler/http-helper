<?php

namespace Carstenwindler\HttpHelper\Tests\Unit;

use Zend\Diactoros\Request;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class RequestToFileTest extends TestCase
{
    /**
     * @var int $now Timestamp that will be returned by time()
     */
    public static $now;

    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    public function setUp()
    {
        parent::setUp();

        // Tackle the time during the tests
        self::$now = time();

        $this->vfsRoot = vfsStream::setup('root');
        vfsStream::create([ 'webroot' => [] ], $this->vfsRoot);
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

        $this->assertTrue(file_exists(vfsStream::url('root/webroot/request.http')));
        $this->assertEquals(vfsStream::url('root/webroot/request.http'), $filename);

        $this->assertEquals(
            "POST / HTTP/1.1\r\nHost: www.carstenwindler.de",
            file_get_contents($filename)
        );
    }

    /**
     * @test
     */
    public function to_file_with_given_name()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        $filename = request_to_file($request, vfsStream::url('root'));

        $this->assertTrue(file_exists(vfsStream::url('root/request.http')));
        $this->assertEquals(vfsStream::url('root/request.http'), $filename);

        $this->assertEquals(
            "POST / HTTP/1.1\r\nHost: www.carstenwindler.de",
            file_get_contents($filename)
        );
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

        $expectedFile .= "\n\n### " . date(DATE_RFC822) . "\n\n" . $httpRequest;

        $this->assertTrue(file_exists(vfsStream::url('root/request.http')));
        $this->assertEquals(vfsStream::url('root/request.http'), $filename);

        $this->assertEquals(
            $expectedFile,
            file_get_contents($filename)
        );
    }
}
