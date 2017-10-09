# PSR-7 Msgpack middleware

Msgpack packer as PSR-7 middleware. It will pack all response body contents and set new Content-Type header

## Usage

> Slim framework example

```php
<?php
require 'vendor/autoload.php';

$body = new \Slim\Http\Body(fopen('php://temp', 'r+')); //empty body for new response, can be replaced with any other implementation of PSR-7 StreamInterface
$config = [
    'config_dir' => __DIR__.'/config',
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];

$app = new \Slim\App($config);
$app->add(new \TitaniumCodes\Middleware\Msgpack($body, 'application/x-msgpack'));
$app->get('/', function ($request, $response, $args) use($config) {
    return $response->withJson($config);
});
$app->run();
```

## [TiSuit](http://tisuit.titanium.codes) integration

config `msgpack.php`:

```php
<?php
return [
    'body' => new \Slim\Http\Body(fopen('php://temp', 'r+'));
    'content-type' => 'application/x-msgpack',
];
```

config `suit.php`:

```php
<?php
return [
    //...
    'providers' => [
        '\TitaniumCodes\Middleware\MsgpackProvider',
        //...
    ],
    'middlewares' => [
        'msgpack_middleware',
        //...
    ],
];
```
