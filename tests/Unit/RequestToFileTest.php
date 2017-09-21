<?php

use Zend\Diactoros\Request;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class RequestToFileTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    public function setUp()
    {
        parent::setUp();

        $this->vfsRoot = vfsStream::setup('root');
        vfsStream::create([ 'webroot' => [] ], $this->vfsRoot);
    }

    /**
     * @test
     */
    public function toFileWithDefaultName()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        $_SERVER['DOCUMENT_ROOT'] = vfsStream::url('root/webroot');

        $filename = requestToFile($request);

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
    public function toFileWithGivenName()
    {
        $request = new Request(
            'http://www.carstenwindler.de',
            'POST'
        );

        $filename = requestToFile($request, vfsStream::url('root'));

        $this->assertTrue(file_exists(vfsStream::url('root/request.http')));
        $this->assertEquals(vfsStream::url('root/request.http'), $filename);

        $this->assertEquals(
            "POST / HTTP/1.1\r\nHost: www.carstenwindler.de",
            file_get_contents($filename)
        );
    }
}
