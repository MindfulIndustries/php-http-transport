<?php

namespace Tests\Transport;

use MindfulIndustries\Support\Transport\Http;
use MindfulIndustries\Support\Transport\MockResponse;
use Tests\TestCase;

class MockTest extends TestCase
{
    /** @test */
    public function testCanGuzzleMock()
    {
        $response = Http::withMock(
            new \GuzzleHttp\Handler\MockHandler([
                new \GuzzleHttp\Psr7\Response(200, ['foo' => 'bar'], json_encode(['baz' => 'qwe']))
            ]))
            ->get('invalid url');

        $this->assertTrue($response->isOk());
        $this->assertEquals('bar', $response->header('foo'));
        $this->assertEquals(['baz' => 'qwe'], $response->json());
    }


    /**
     * @test
     * @depends testCanGuzzleMock
     */
    public function testCanShortcutMock()
    {
        $response = Http::withMock(MockResponse::json(['body-param' => 'foo'], ['header-param' => 'foo']))->get('invalid url');

        $this->assertTrue($response->isOk());
        $this->assertEquals('foo', $response->header('header-param'));
        $this->assertEquals(['body-param' => 'foo'], $response->json());
    }


    /**
     * @test
     * @depends testCanShortcutMock
     */
    public function testEmptyMock()
    {
        $response = Http::withMock(MockResponse::make())->get('foo');
        $this->assertTrue($response->isOk());
    }


    /**
     * @test
     * @depends testCanGuzzleMock
     */
    public function testStaticMock()
    {
        Http::staticMock(MockResponse::json(['body-param' => 'foo']));

        $response = Http::get('invalid url');

        $this->assertTrue($response->isOk());
        $this->assertEquals(['body-param' => 'foo'], $response->json());
    }


    /**
     * @test
     * @depends testCanGuzzleMock
     * @depends testStaticMock
     */
    public function testInstanceMockOverridesStaticOne()
    {
        Http::staticMock(MockResponse::json(['body-param' => 'static-mock']));

        $response = Http::withMock(MockResponse::json(['body-param' => 'instance-mock']))->get('invalid url');

        $this->assertTrue($response->isOk());
        $this->assertEquals(['body-param' => 'instance-mock'], $response->json());
    }
}