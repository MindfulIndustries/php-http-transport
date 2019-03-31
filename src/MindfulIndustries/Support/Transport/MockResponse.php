<?php

namespace MindfulIndustries\Support\Transport;

use GuzzleHttp\Handler\MockHandler;

class MockResponse
{
    /**
     * Factory Json Response.
     * @param  array $json
     * @param  array $headers
     * @return \GuzzleHttp\Psr7\Response
     */
    public static function json(array $json, array $headers = []) : MockHandler
    {
        return static::make(
            200,
            json_encode($json),
            array_merge_recursive(
                [
                    'Content-Type' => 'application/json'
                ],
                $headers
            )
        );
    }


    /**
     * Factory
     * @param  int $code
     * @param  mixed $body
     * @param  array $headers
     * @return \GuzzleHttp\Handler\MockHandler
     */
    public static function make(int $code = 200, $body = null, array $headers = []) : MockHandler
    {
        return new MockHandler([
            new \GuzzleHttp\Psr7\Response($code, $headers, print_r($body, true))
        ]);
    }
}