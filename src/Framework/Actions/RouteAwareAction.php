<?php

namespace App\Framework\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Adds methods related to router redirection
 *
 * Trait RouteAwareAction
 * @package App\Framework\Actions
 */
trait RouteAwareAction
{
    /**
     * Return a redirection response
     * @param string $path
     * @param array $params
     * @return ResponseInterface
     */
    public function redirect(string $path, array $params = []): ResponseInterface
    {
        $redirectUri = $this->router->generateURI($path, $params);
        return (new Response())
            ->withStatus(301)
            ->withHeader('location', $redirectUri);
    }
}
