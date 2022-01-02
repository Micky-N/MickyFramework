<?php


namespace Core\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    /**
     * Contrôle la route à l'entrée
     *
     * @param callable $next
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function process(callable $next, ServerRequestInterface $request);
}