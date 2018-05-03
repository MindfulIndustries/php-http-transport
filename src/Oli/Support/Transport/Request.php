<?php

namespace Oli\Support\Transport;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class Request
{
    /** @var array */
    protected $options;

    /** @var string */
    protected $bodyFormat;


    public function __construct()
    {
        $this->options = [
            'http_errors' => false,
        ];

        $this->bodyFormat = 'json';
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
     * Request GET.
     * @param   string $url
     * @param   array $params
     * @return  \Oli\Support\Transport\Response
     * @throws  \Oli\Support\Transport\ConnectionException
     */
    public function get(string $url, array $params = []) : Response
    {
        return $this->request('GET', $url, [
            'query' => $params
        ]);
    }


    /**
     * Execute Http Request.
     * @param   string $method
     * @param   string $url
     * @param   mixed $params
     * @return  \Oli\Support\Transport\Response
     * @throws  \Oli\Support\Transport\ConnectionException
     */
    protected function request(string $method, string $url, $params) : Response
    {
        try {
            return new Response(
                $this->guzzleClient()->request(
                    $method,
                    $url,
                    $this->buildParams(
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
        return new Client();
    }


    /**
     * Merge given params.
     * @return  array
     */
    protected function buildParams(...$params) : array
    {
        return array_merge_recursive($this->options, ...$params);
    }


    protected function queryParams(string $url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        return $query;
    }
}