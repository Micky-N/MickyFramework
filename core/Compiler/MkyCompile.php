<?php

namespace Core\Compiler;

use Closure;

class MkyCompile
{

    private array $directives;
    private array $callbacks;
    /**
     * @var string[][]
     */
    private array $formatEcho;
    /**
     * @var bool[]
     */
    private array $conditions;

    public function __construct()
    {
        $this->directives = [
            'style' => ['style', 'endstyle'],
            'script' => ['script', 'endscript'],
            'php' => ['php', 'endphp'],
            'foreach' => ['foreach', 'endforeach'],
            'json' => ['json'],
            'if' => ['if', 'elseif', 'else', 'endif'],
            'isset' => ['isset', 'else', 'endisset'],
            'notisset' => ['notisset', 'else', 'endnotisset'],
            'empty' => ['empty', 'else', 'endempty'],
            'notempty' => ['notempty', 'else', 'endnotempty'],
            'auth' => ['auth', 'else', 'endauth'],
            'guest' => ['guest', 'else', 'endguest'],
            'dump' => ['dump'],
            'route' => ['route', 'else', 'endroute'],
            'repeat' => ['repeat', 'endrepeat'],
            'haserror' => ['haserror', 'else', 'endhaserror'],
            'error' => ['error', 'else', 'enderror'],
            'permission' => ['permission', 'else', 'endpermission'],
            'switch' => ['switch', 'case', 'break', 'default', 'endswitch']
        ];

        $this->conditions = [
            'firstCaseSwitch' => false
        ];

        $this->formatEcho = [
            'echo' => ['{{', '}}'],
            'escape' => ['{!!', '!!}']
        ];

        $this->callbacks = [
            'style' => [
                function ($href = null) {
                    if($href){
                        return '<link rel="stylesheet" type="text/css" href=' . $href . '/>';
                    }
                    return '<style>';
                },
                function () {
                    return '</style>';
                }
            ],
            'script' => [
                function ($src = null) {
                    if($src){
                        return '<script type="text/javascript" src=' . $src . '></script>';
                    }
                    return '<script>';
                },
                function () {
                    return '</script>';
                }
            ],
            'php' => [
                function () {
                    return '<?php';
                },
                function () {
                    return '?>';
                }
            ],
            'foreach' => [
                function ($expression) {
                    return '<?php foreach(' . $expression . '): ?>';
                },
                function () {
                    return '<?php endforeach; ?>';
                }
            ],
            'echo' => [
                function ($expression) {
                    return '<?=' . $expression;
                },
                function () {
                    return '?>';
                }
            ],
            'escape' => [
                function ($expression) {
                    return '<?php htmlspecialchars(' . $expression . ')';
                },
                function () {
                    return '?>';
                }
            ],
            'json' => [
                function ($expression) {
                    return '<?= json_encode(' . $expression . ') ?>';
                }
            ],
            'if' => [
                function ($expression) {
                    return '<?php if(' . $expression . '): ?>';
                },
                function ($expression) {
                    return '<?php else if(' . $expression . '): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'empty' => [
                function ($expression) {
                    return '<?php if(empty(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'notempty' => [
                function ($expression) {
                    return '<?php if(!empty(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'isset' => [
                function ($expression) {
                    return '<?php if(isset(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'notisset' => [
                function ($expression) {
                    return '<?php if(!isset(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'auth' => [
                function () {
                    return '<?php if(isLoggin()): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'guest' => [
                function () {
                    return '<?php if(!isLoggin()): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'dump' => [
                function ($expression) {
                    return '<?php dump(' . $expression . ') ?>';
                }
            ],
            'route' => [
                function ($expression) {
                    return '<?php if(currentRoute(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'routenot' => [
                function ($expression) {
                    return '<?php if(!currentRoute(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'repeat' => [
                function ($expression) {
                    return '<?php foreach(range(0, ' . ($expression - 1) . ') as $index): ?>';
                },
                function () {
                    return '<?php endforeach; ?>';
                }
            ],
            'haserror' => [
                function ($expression) {
                    return '<?php if(isset($errors) && isset($errors[' . $expression . '])): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'error' => [
                function () {
                    return '<?php if(isset($errors) && !empty($errors)): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'permission' => [
                function ($expression) {
                    return '<?php if(permission(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'notauthorize' => [
                function ($expression) {
                    return '<?php if(!permission(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ],
            'switch' => [
                function ($expression) {
                    $this->conditions['firstCaseSwitch'] = true;
                    return '<?php switch(' . $expression . '):';
                },
                function ($expression) {
                    if($this->conditions['firstCaseSwitch']){
                        $this->conditions['firstCaseSwitch'] = false;
                        return ' case ' . $expression . ': ?>';
                    }
                    return '<?php case(' . $expression . '): ?>';
                },
                function () {
                    return '<?php break; ?>';
                },
                function () {
                    return '<?php default; ?>';
                },
                function () {
                    return '<?php endswitch; ?>';
                }
            ]
        ];
    }

    /**
     * @param string $key
     * @param string[] $directives
     * @return MkyCompile
     */
    public function setDirectives(string $key, array $directives): MkyCompile
    {
        $this->directives[$key] = $directives;
        return $this;
    }

    /**
     * @return array
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }

    /**
     * @param string $key
     * @param int $callback
     * @return null|Closure[]
     */
    public function getCallbacks(string $key, int $callback = null)
    {
        if($callback !== null){
            return $this->callbacks[$key][$callback] ?? null;
        }
        return $this->callbacks[$key] ?? null;
    }

    /**
     * @param string $key
     * @param Closure[] $callbacks
     * @return MkyCompile
     */
    public function setCallbacks(string $key, array $callbacks): MkyCompile
    {
        $this->callbacks[$key] = $callbacks;
        return $this;
    }
}