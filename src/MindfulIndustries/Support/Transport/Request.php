<?php

namespace MindfulIndustries\Support\Transport;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class Request
{
    /** @var array */
    protected $options;

    /** @var string */
    protected $bodyFormat;

    /** @var string */
    protected $urlPrefix;

    /** @var \GuzzleHttp\Handler\MockHandler */
    protected $mockHandler = null;


    /** @var \GuzzleHttp\Handler\MockHandler */
    static protected $staticMockHandler = null;


    public function __construct()
    {
        $this->options = [
            'http_errors' => false,
        ];

        $this->bodyFormat = 'json';

        $this->urlPrefix = '';

        $this->mock = null;
    }


    /**
     * Configure request params as Json.
     * @return  $this
     */
    public function asJson() : Request
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }


    /**
     * Configure request params as Form query.
     * @return  $this
     */
    public function asForm() : Request
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }


    /**
     * Configure request body format.
     * @param   string $format
     * @return  $this
     */
    public function bodyFormat(string $format) : Request
    {
        $this->bodyFormat = $format;
        return $this;
    }


    /**
     * Configure request content type.
     * @param   string $format
     * @return  $this
     */
    public function contentType(string $type) : Request
    {
        return $this->withHeaders([
            'content-type' => $type
        ]);
    }


    /**
     * Add customs headers to the Request.
     * @param   array $headers
     * @return  $this
     */
    public function withHeaders(array $headers) : Request
    {
        $this->options = array_merge_recursive(
            $this->options,
            [
                'headers' => $headers
            ]
        );

        return $this;
    }


    /**
     * Prepend any call Url with given Prefix.
     * @param   string $prefix
     * @return  $this
     */
    public function withUrlPrefix(string $prefix) : Request
    {
        $this->urlPrefix = $prefix;
        return $this;
    }


    /**
     * Enable Response Mocking.
     * @param   \GuzzleHttp\Handler\MockHandler $handler
     * @return  $this
     */
    public function withMock(MockHandler $handler) : Request
    {
        $this->mockHandler = $handler;
        return $this;
    }


    /**
     * Set static Mock Handler.
     * @param  \GuzzleHttp\Handler\MockHandler $handler
     * @return void
     */
    public function staticMock(MockHandler $handler)
    {
        static::$staticMockHandler = $handler;
    }


    /**
     * Configure desired Timeout for the Request.
     * @param   int $seconds
     * @return  $this
     */
    public function timeout(int $seconds) : Request
    {
        $this->options['timeout'] = $seconds;
        return $this;
    }


    /**
     * Request GET.
     * @param   string $url
     * @param   array $params
     * @return  \MindfulIndustries\Support\Transport\Response
     * @throws  \MindfulIndustries\Support\Transport\ConnectionException
     */
    public function get(string $url, array $params = []) : Response
    {
        return $this->request('GET', $url, [
            'query' => $params
        ]);
    }


    /**
     * Request POST.
     * @param   string $url
     * @param   array $params
     * @return  \MindfulIndustries\Support\Transport\Response
     * @throws  \MindfulIndustries\Support\Transport\ConnectionException
     */
    public function post(string $url, array $params = []) : Response
    {
        return $this->request('POST', $url, [
            $this->bodyFormat => $params
        ]);
    }


    /**
     * Request PATCH.
     * @param   string $url
     * @param   array $params
     * @return  \MindfulIndustries\Support\Transport\Response
     * @throws  \MindfulIndustries\Support\Transport\ConnectionException
     */
    public function patch(string $url, array $params = []) : Response
    {
        return $this->request('PATCH', $url, [
            $this->bodyFormat => $params
        ]);
    }


    /**
     * Request PUT.
     * @param   string $url
     * @param   array $params
     * @return  \MindfulIndustries\Support\Transport\Response
     * @throws  \MindfulIndustries\Support\Transport\ConnectionException
     */
    public function put(string $url, array $params = []) : Response
    {
        return $this->request('PUT', $url, [
            $this->bodyFormat => $params
        ]);
    }



    /**
     * Request DELETE.
     * @param   string $url
     * @param   array $params
     * @return  \MindfulIndustries\Support\Transport\Response
     * @throws  \MindfulIndustries\Support\Transport\ConnectionException
     */
    public function delete(string $url, array $params = []) : Response
    {
        return $this->request('DELETE', $url, [
            $this->bodyFormat => $params
        ]);
    }

    /**
     * Execute Http Request.
     * @param   string $method
     * @param   string $url
     * @param   array $params
     * @return  \MindfulIndustries\Support\Transport\Response
     * @throws  \MindfulIndustries\Support\Transport\ConnectionException
     */
    protected function request(string $method, string $url, array $params) : Response
    {
        try {
            return new Response(
                $this->guzzleClient()->request(
                    $method,
                    $this->urlPrefix . $url,
                    $this->mergeParams(
                        ['query' => $this->queryParams($url)],
                        $params
                    )
                )
            );
        } catch (ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }


    /**
     * Build Guzzle Client instance.
     * @return  \GuzzleHttp\Client
     */
    protected function guzzleClient() : Client
    {
        $mock = $this->mockHandler ?? static::$staticMockHandler ?? null;

        return is_null($mock)
            ? new Client()
            : new Client(['handler' => HandlerStack::create($mock)]);
    }


    /**
     * Merge options with given Parameters.
     * @param   array $params
     * @return  array
     */
    protected function mergeParams(array ...$params) : array
    {
        return array_merge_recursive($this->options, ...$params);
    }


    protected function queryParams(string $url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        return $query;
    }
}