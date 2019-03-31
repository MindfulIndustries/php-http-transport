<?php

namespace Tests\Transport;

use MindfulIndustries\Support\Transport\Http;
use MindfulIndustries\Support\Transport\MockResponse;
use Tests\TestCase;

class SerializationTest extends TestCase
{
    /** @test */
    public function testCanGuzzleMock()
    {
        $stringOutput = (string) Http::withMock(MockResponse::make(200, [], ['foo' => 'bar']))->get('invalid url');

        $this->assertStringContainsString('foo', $stringOutput);
        $this->assertStringContainsString('bar', $stringOutput);
    }
}