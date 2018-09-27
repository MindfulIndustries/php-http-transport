# php-http-transport

Simple PHP Guzzle wrapper inspired by [Zttp](https://github.com/kitetail/zttp).

[![Build Status](https://travis-ci.org/MindfulIndustries/php-http-transport.svg?branch=master)](https://travis-ci.org/MindfulIndustries/php-http-transport)


## Installation

`composer require mindfulindustries/php-http-transport`


## Usage without timeout
```php
$response = \MindfulIndustries\Support\Transport\Http::get('http://example.com', [
   'foo' => 'bar'
]);
```


## Usage with timeout
```php
try {
    $response = \MindfulIndustries\Support\Transport\Http::timeout(5)->post('http://your.domain', [
        'foo' => 'bar'
    ]);
} catch (\MindfulIndustries\Support\Transport\ConnectionException $e) {
    // timed out ..
}
```
