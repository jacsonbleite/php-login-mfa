<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use App\Http\Response;
use phpmock\phpunit\PHPMock;

class ResponseTest extends TestCase
{
    use PHPMock;

    public function testResponseInitialization()
    {
        $response = new Response(200, 'OK', 'text/html');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getHttpCode());
        $this->assertEquals('OK', $response->getContent());
        $this->assertEquals('text/html', $response->getHeaders()['Content-Type']);
    }

    public function testSetContentType()
    {
        $response = new Response(200, 'OK');
        $response->setContentType('application/json');

        $this->assertEquals('application/json', $response->getHeaders()['Content-Type']);
    }

    public function testAddHeader()
    {
        $response = new Response(200, 'OK');
        $response->addHeader('X-Custom-Header', 'CustomValue');

        $this->assertEquals('CustomValue', $response->getHeaders()['X-Custom-Header']);
    }

    public function testAddHeaders()
    {
        $response = new Response(200, 'OK');
        $headers = [
            'X-Header-1' => 'Value1',
            'X-Header-2' => 'Value2',
        ];
        $response->addHeaders($headers);

        $this->assertEquals('Value1', $response->getHeaders()['X-Header-1']);
        $this->assertEquals('Value2', $response->getHeaders()['X-Header-2']);
    }

    public function testSendHeaders()
    {
        $http_response_code = $this->getFunctionMock('App\Http', 'http_response_code');
        $http_response_code->expects($this->once())->with(200);

        $header = $this->getFunctionMock('App\Http', 'header');
        $header->expects($this->at(0))->with('Content-Type: text/html');
        $header->expects($this->at(1))->with('X-Custom-Header: CustomValue');

        $response = new Response(200, 'OK');
        $response->addHeader('X-Custom-Header', 'CustomValue');

        // Simulando o envio dos headers
        $response->sendResponse();
    }

    public function testSendResponseWithJson()
    {
        $http_response_code = $this->getFunctionMock('App\Http', 'http_response_code');
        $http_response_code->expects($this->once())->with(200);

        $header = $this->getFunctionMock('App\Http', 'header');
        $header->expects($this->at(0))->with('Content-Type: application/json');

        $data = ['message' => 'OK'];
        $response = new Response(200, $data, 'application/json');

        // Simulando a resposta
        ob_start();
        $response->sendResponse();
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertEquals(json_encode($data), $output);
    }

    public function testSetStatusCode()
    {
        $response = new Response(200, 'OK');
        $response->setStatusCode(404);

        $this->assertEquals(404, $response->getHttpCode());
    }

    public function testSetContent()
    {
        $response = new Response(400, 'OK');
        $response->setContent('New Content');

        $this->assertEquals('New Content', $response->getContent());
    }
}
