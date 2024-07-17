<?php

namespace App\Http;

class Response
{
    private $httpCode;
    private $headers = [];
    private $contentType;
    private $content;

    public function __construct($httpCode = 200, $content = '', $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content  = $content;
        $this->setContentType($contentType);
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function addHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    private function sendHeaders()
    {
        // Define o status
        http_response_code($this->httpCode);

        // Envia os headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
    }

    public function sendResponse()
    {
        // Envia os headers
        $this->sendHeaders();

        // Imprime o conteúdo
        switch ($this->contentType) {
            case 'application/json':
                echo json_encode($this->content);
                break;
            case 'text/html':
            default:
                echo $this->content;
                break;
        }

        exit;
    }

    public function setStatusCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getContent()
    {
        return $this->content;
    }
}
