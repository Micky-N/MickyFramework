<?php


namespace Core\Security;


use Core\Exceptions\CsrfMiddlewareException;
use Core\Facades\Session;
use Psr\Http\Message\ServerRequestInterface;

class CsrfMiddleware implements \Core\Interfaces\MiddlewareInterface
{

    const FORM_KEY = '_csfr';
    const SESSION_KEY = 'csrf';

    /**
     * @inheritDoc
     */
    public function process(callable $next, ServerRequestInterface $request)
    {
        if(in_array(strtoupper($request->getMethod()), ['POST', 'PUT', 'DELETE'])){
            $params = $request->getParsedBody();
            if(!array_key_exists(self::FORM_KEY, $params)){
                $this->reject();
            }
            $csrfList = Session::get(self::SESSION_KEY, []);
        }
        return $next($request);

    }

    private function reject()
    {
        throw new CsrfMiddlewareException();
    }
}