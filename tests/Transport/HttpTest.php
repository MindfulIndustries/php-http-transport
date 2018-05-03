<?php

namespace Tests\Transport;

use Oli\Support\Transport\Http;
use Tests\TestCase;

class HttpTest extends TestCase
{
    /** @test */
    public function testCanExecuteBasicGet()
    {
        $response = Http::get($this->url('/get'), [
            'foo' => 'bar'
        ]);

        $this->assertTrue($response->isOk());

        $this->assertArraySubset([
            'query' => [
                'foo' => 'bar'
            ]
        ], $response->json());
    }


    /**
     * Build testing Url.
     * @param   string $query
     * @return  string
     */
    private function url(string $query) : string
    {
        return vsprintf('http://localhost:%d/%s', [
            getenv('TEST_SERVER_PORT'),
            ltrim($query, '/')
        ]);
    }
}