<?php

namespace Core\MkyCompiler;

use Core\Interfaces\MkyDirectiveInterface;
use Core\MkyCompiler\MkyDirectives\BaseDirective;
use Core\MkyCompiler\MkyDirectives\ScriptDirective;
use Core\MkyCompiler\MkyDirectives\StyleDirective;

class MkyDirective
{
    /**
     * @var MkyDirectiveInterface[]
     */
    private static array $directives = [];

    public function __construct()
    {
        self::$directives[] = new BaseDirective();
        self::$directives[] = new StyleDirective();
        self::$directives[] = new ScriptDirective();
    }

    public static function addDirective(MkyDirectiveInterface $directive)
    {
        self::$directives[] = $directive;
    }

    public function callFunction(string $function, $expression = null, bool $open = true)
    {
        foreach (self::$directives as $directive) {
            if(array_key_exists($function, $directive->getFunctions())){
                $methodDirective = $directive->getFunctions()[$function][(int) !$open];
                return call_user_func([$methodDirective[0], $methodDirective[1]], $expression);
            }
        }
        return $expression;
    }
}