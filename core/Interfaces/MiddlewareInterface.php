<?php


namespace Core\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function process(callable $next, ServerRequestInterface $request);
}