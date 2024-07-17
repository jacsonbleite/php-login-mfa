<?php

use PHPUnit\Framework\TestCase;
use App\Http\Request;

class RequestTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
    }

    public function testGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request(null);
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testGetUri()
    {
        $_SERVER['REQUEST_URI'] = '/test/uri?foo=bar';
        $request = new Request(null);
        $this->assertEquals('/test/uri', $request->getUri());
    }

    public function testGetQueryParams()
    {
        $_GET = ['foo' => 'bar'];
        $request = new Request(null);
        $this->assertEquals(['foo' => 'bar'], $request->getQueryParams());
    }

    public function testGetPostVars()
    {
        $_POST = ['username' => 'john'];
        $request = new Request(null);
        $this->assertEquals(['username' => 'john'], $request->getPostVars());
    }

    public function testGetHeaders()
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
        $request = new Request(null);
        $headers = $request->getHeaders();
        $this->assertEquals('localhost', $headers['Host']);
        $this->assertEquals('PHPUnit', $headers['User-Agent']);
    }
}
