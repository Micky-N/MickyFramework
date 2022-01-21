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
            'style' => new MkyDirective(['style'], [
                function ($href = null) {
                    if($href){
                        return '<link rel="stylesheet" type="text/css" href=' . $href . '>';
                    }
                    return '<style>';
                },
                function () {
                    return '</style>';
                }
            ]),
            'script' => new MkyDirective(['script'], [
                function ($src = null) {
                    if($src){
                        return '<script type="text/javascript" src=' . $src . '></script>';
                    }
                    return '<script>';
                },
                function () {
                    return '</script>';
                }
            ]),
            'php' => new MkyDirective(['php'], [
                function () {
                    return '<?php';
                },
                function () {
                    return '?>';
                }
            ]),
            'each' => new MkyDirective(['each'], [
                function ($expression) {
                    if(strpos($expression, 'as') === false){
                        $expression .= ' as $self';
                    }
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
            'if' => new MkyDirective(['if'], [
                function ($expression) {
                    return '<?php if(' . $expression . '): ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'elseif' => new MkyDirective(['elseif'], [
                function ($expression) {
                    return '<?php else if(' . $expression . '): ?>';
                }
            ]),
            'else' => new MkyDirective(['else'], [
                function () {
                    return '<?php else: ?>';
                }
            ]),
            'auth' => new MkyDirective(['auth'], [
                function () {
                    return '<?php if(isLogin()): ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'guest' => new MkyDirective(['guest'], [
                function () {
                    return '<?php if(!isLogin()): ?>';
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
            'repeat' => new MkyDirective(['repeat'], [
                function ($expression) {
                    return '<?php foreach(range(0, ' . ($expression - 1) . ') as $index): ?>';
                },
                function () {
                    return '<?php endforeach; ?>';
                }
            ]),
            'permission' => new MkyDirective(['permission'], [
                function ($expression) {
                    return '<?php if(permission(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'notpermission' => new MkyDirective(['notpermission'], [
                function ($expression) {
                    return '<?php if(!permission(' . $expression . ')): ?>';
                },
                function () {
                    return '<?php endif; ?>';
                }
            ]),
            'switch' => new MkyDirective(['switch'], [
                function ($expression) {
                    $this->conditions['firstCaseSwitch'] = true;
                    return '<?php switch(' . $expression . '):';
                },
                function () {
                    return '<?php endswitch; ?>';
                }
            ]),
            'case' => new MkyDirective(['case'], [
                function ($expression) {
                    if($this->conditions['firstCaseSwitch']){
                        $this->conditions['firstCaseSwitch'] = false;
                        return ' case ' . $expression . ': ?>';
                    }
                    return '<?php case(' . $expression . '): ?>';
                }
            ]),
            'break' => new MkyDirective(['break'], [
                function () {
                    return '<?php break; ?>';
                }
            ]),
            'default' => new MkyDirective(['default'], [
                function () {
                    return '<?php default; ?>';
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