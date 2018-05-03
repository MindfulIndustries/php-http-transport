<?php

namespace Oli\Support\Transport;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /** @var \Psr\Http\Message\ResponseInterface */
    protected $response;


    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }


    /**
     * Get Response Status.
     * @return  int
     */
    public function status() : int
    {
        return $this->response->getStatusCode();
    }


    /**
     * Get Response body Json.
     * @return  array
     */
    public function json() : array
    {
        return json_decode($this->response->getBody(), true) ?? [];
    }


    /**
     * Get specific Header of the Response.
     * @param   string $name
     * @return  string
     */
    public function header(string $name) : ?string
    {
        return $this->response->getHeaderLine($header);
    }


    /**
     * Determinate if the Response was sucessful.
     * @return  bool
     */
    public function isOk() : bool
    {
        return $this->status() >= 200 && $this->status() < 300;
    }


    /**
     * Determinate if the Response is redirect.
     * @return  bool
     */
    public function isRedirect() : bool
    {
        return $this->status() >= 300 && $this->status() < 400;
    }


    /**
     * Determinate if the Response signals client error.
     * @return  bool
     */
    function isClientError() : bool
    {
        return $this->status() >= 400 && $this->status() < 500;
    }


    /**
     * Determinate if the Response signals server error.
     * @return  bool
     */
    function isServerError() : bool
    {
        return $this->status() >= 500;
    }


    /**
     * Dynamically access Guzzle Response methods.
     * @param   string $method
     * @param   array $argument
     * @return  mixed
     */
    public function __call(string $method, array $arguments)
    {
        return $this->response->{$method}(...$arguments);
    }
}