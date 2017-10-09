<?php

declare(strict_types=1);

namespace TitaniumCodes\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class Msgpack
{
    /**
     * Empty body.
     *
     * @var StreamInterface
     */
    private $body;

    /**
     * Content-Type header for packed data.
     *
     * @var string
     */
    private $contentType;

    public function __construct(StreamInterface $body, string $contentType = 'application/x-msgpack')
    {
        $this->body = $body;
        $this->contentType = $contentType;
    }

    /**
     * Run middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $response = $next($request, $response);
        $this->body->write(msgpack_pack($response->getBody()->__toString()));
        $response = $response->withHeader('Content-Type', $this->contentType)->withBody($this->body);

        return $response;
    }
}
