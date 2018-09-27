<?php

namespace Tests\Transport;

use MindfulIndustries\Support\Transport\Http;
use Tests\TestCase;

class MockTest extends TestCase
{
    /** @test */
    public function testCanMock()
    {
        $response = Http::withMock(
            new \GuzzleHttp\Handler\MockHandler([
                new \GuzzleHttp\Psr7\Response(200, ['foo' => 'bar'], json_encode(['baz' => 'qwe']))
            ]))
            ->get('invalid url');

        $this->assertTrue($response->isOk());
        $this->assertEquals('bar', $response->header('foo'));
        $this->assertEquals([
            'baz' => 'qwe'
        ], $response->json());
    }
}