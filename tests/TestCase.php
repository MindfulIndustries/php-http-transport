<?php

namespace Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass()
    {
        FakeServer::start();
    }
}