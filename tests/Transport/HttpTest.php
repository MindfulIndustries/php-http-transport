<?php

namespace Tests\Transport;

use MindfulIndustries\Support\Transport\Http;
use Tests\TestCase;

class HttpTest extends TestCase
{
    public static function setUpBeforeClass() : void
    {
        \Tests\FakeServer::start();
    }


    /** @test */
    public function testCanGetParamsAsArray()
    {
        $response = Http::get($this->url('/get'), [
            'foo' => 'bar',
            'baz' => 'qwe'
        ]);

        $this->assertTrue($response->isOk());

        $this->assertSame([
                'foo' => 'bar',
                'baz' => 'qwe'
        ], $response->json()['query']);
    }


    /** @test */
    public function testCanGetParamsAsQuery()
    {
        $response = Http::get($this->url('/get?foo=bar&baz=qwe'));

        $this->assertTrue($response->isOk());
        $this->assertSame([
            'foo' => 'bar',
            'baz' => 'qwe'
        ], $response->json()['query']);
    }


    /** @test */
    public function testGetParamsCanBeCombined()
    {
        $response = Http::get($this->url('/get?foo=bar'), [
            'baz' => 'qwe'
        ]);

        $this->assertTrue($response->isOk());
        $this->assertSame([
            'foo' => 'bar',
            'baz' => 'qwe'
        ], $response->json()['query']);
    }


    /** @test */
    public function testCanPostPutPatchDeleteJsonParams()
    {
        foreach (['post', 'put', 'patch', 'delete'] as $method) {
            $response = Http::{$method}($this->url('/' . $method), [
                'foo' => 'bar',
                'baz' => 'qwe'
            ]);

            $this->assertTrue($response->isOk());
            $this->assertSame([
                'foo' => 'bar',
                'baz' => 'qwe'
            ], $response->json()['json']);
        }
    }


    /** @test */
    public function testCanPassCustomHeader()
    {
        $response = Http::withHeaders(['foo' => 'bar'])->post($this->url('/post'));

        $this->assertTrue($response->isOk());
        $this->assertSame(['bar'], $response->json()['headers']['foo']);
    }


    /** @test */
    public function testExceptionIsNotThrownByDefault()
    {
        foreach ([400, 500] as $statusCode) {
            $response = Http::withHeaders(['testing-response-code' => $statusCode])->get($this->url('/get'));
            $this->assertEquals($statusCode, $response->status());
        }
    }


    /** @test */
    public function testCanTimeout()
    {
        $this->expectException(\MindfulIndustries\Support\Transport\ConnectionException::class);
        Http::timeout(1)->get($this->url('/timeout?seconds=2'));
    }


    /** @test */
    public function testPrefixedUrlCallWorks()
    {
        $response = Http::withUrlPrefix(sprintf('http://localhost:%d/', getenv('TEST_SERVER_PORT')))->get('get');
        $this->assertTrue($response->isOk());
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