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
        $data = $this->preprocess($response);
        $this->body->write(msgpack_pack($data));
        $response = $response->withHeader('Content-Type', $this->contentType)->withBody($this->body);

        return $response;
    }

    /**
     * Preprocess response body and convert it to array (if available).
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    protected function preprocess(ResponseInterface $response)
    {
        $content = $response->getBody()->__toString();
        if (false === strpos($response->getHeaderLine('Content-Type'), 'json')) {
            return $content;
        }

        return json_decode($content, true);
    }
}
