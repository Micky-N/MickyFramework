<?php

namespace Core\MkyCompiler;

use Closure;

class MkyCompile
{

    /**
     * @var MkyDirective[]
     */
    private array $directives;

    /**
     * @var bool[]
     */
    private array $conditions;

    public function __construct()
    {
        $this->conditions = [
            'firstCaseSwitch' => false
        ];

        $this->directives = [
            'style' => new MkyDirective(['style', 'endstyle'], [
                function ($href = null) {
                    if ($href) {
                        return '<link rel="stylesheet" type="text/css" href=' . $href . '>';
                    }
                    return '<style>';
                },
                function () {
                    return '</style>';
                }
            ]),
            'script' => new MkyDirective(['script', 'endscript'], [
                function ($src = null) {
                    if ($src) {
                        return '<script type="text/javascript" src=' . $src . '></script>';
                    }
                    return '<script>';
                },
                function () {
                    return '</script>';
                }
            ]),
            'php' => new MkyDirective(['php', 'endphp'], [
                function () {
                    return '<?php';
                },
                function () {
                    return '?>';
                }
            ]),
            'foreach' => new MkyDirective(['foreach', 'endforeach'], [
                function ($expression) {
                    return '<?php foreach(' . $expression . '): ?>';
                },
                function () {
                    return '<?php endforeach; ?>';
                }
            ]),
            'json' => new MkyDirective(['json'], [
                function ($expression) {
                    return '<?= json_encode(' . $expression . ', JSON_UNESCAPED_UNICODE); ?>';
                }
            ]),
            'if' => new MkyDirective(['if', 'elseif', 'else', 'endif'], [
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
            ]),
            'isset' => new MkyDirective(['isset', 'else', 'endisset'], [
                function ($expression) {
                    return '<?php if(isset(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'notisset' => new MkyDirective(['notisset', 'else', 'endnotisset'], [
                function ($expression) {
                    return '<?php if(!isset(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'empty' => new MkyDirective(['empty', 'else', 'endempty'], [
                function ($expression) {
                    return '<?php if(empty(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'notempty' => new MkyDirective(['notempty', 'else', 'endnotempty'], [
                function ($expression) {
                    return '<?php if(!empty(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'auth' => new MkyDirective(['auth', 'else', 'endauth'], [
                function () {
                    return '<?php if(isLoggin()): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'guest' => new MkyDirective(['guest', 'else', 'endguest'], [
                function () {
                    return '<?php if(!isLoggin()): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'dump' => new MkyDirective(['dump'], [
                function ($expression) {
                    return '<?php dump(' . $expression . ') ?>';
                }
            ]),
            'route' => new MkyDirective(['route', 'else', 'endroute'], [
                function ($expression) {
                    return '<?php if(currentRoute(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'routenot' => new MkyDirective(['routenot', 'else', 'endroutenot'], [
                function ($expression) {
                    return '<?php if(!currentRoute(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'repeat' => new MkyDirective(['repeat', 'endrepeat'], [
                function ($expression) {
                    return '<?php foreach(range(0, ' . ($expression - 1) . ') as $index): ?>';
                },
                function () {
                    return '<?php endforeach; ?>';
                }
            ]),
            'haserrors' => new MkyDirective(['haserrors', 'else', 'endhaserrors'], [
                function () {
                    return '<?php if(isset($errors) && !empty($errors)): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'error' => new MkyDirective(['error', 'else', 'enderror'], [
                function ($expression) {
                    return '<?php if(isset($errors) && isset($errors[' . $expression . '])): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'permission' => new MkyDirective(['permission', 'else', 'endpermission'], [
                function ($expression) {
                    return '<?php if(permission(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'notpermission' => new MkyDirective(['notpermission', 'else', 'endnotpermission'], [
                function ($expression) {
                    return '<?php if(!permission(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php else: ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'switch' => new MkyDirective(['switch', 'case', 'break', 'default', 'endswitch'], [
                function ($expression) {
                    $this->conditions['firstCaseSwitch'] = true;
                    return '<?php switch(' . $expression . '):';
                },
                function ($expression) {
                    if ($this->conditions['firstCaseSwitch']) {
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
            ])
        ];
    }

    /**
     * Set a directive
     *
     * @param string $key
     * @param string[] $directives
     * @param callable[] $callbacks
     * @return MkyCompile
     */
    public function setDirective(string $key, array $directives, array $callbacks): MkyCompile
    {
        $this->directives[$key] = new MkyDirective($directives, $callbacks);
        return $this;
    }


    /**
     * Set a condition
     *
     * @param string $key
     * @param bool $value
     * @return MkyCompile
     */
    public function setCondition(string $key, bool $value): MkyCompile
    {
        $this->conditions[$key] = $value;
        return $this;
    }

    /**
     * Get all directives
     *
     * @return array
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }
}