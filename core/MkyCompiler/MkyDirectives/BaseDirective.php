<?php


namespace Core\MkyCompiler\MkyDirectives;


use Core\Interfaces\MkyDirectiveInterface;

class BaseDirective implements MkyDirectiveInterface
{
    private array $conditions = [
        'firstCaseSwitch' => false
    ];
    private array $sections = [];

    public function getFunctions()
    {
        return [
            'if' => [[$this, 'if'], [$this, 'endif']],
            'elseif' => [[$this, 'elseif']],
            'else' => [[$this, 'else']],
            'each' => [[$this, 'each'], [$this, 'endeach']],
            'repeat' => [[$this, 'repeat'], [$this, 'endrepeat']],
            'php' => [[$this, 'php'], [$this, 'endphp']],
            'switch' => [[$this, 'switch'], [$this, 'endswitch']],
            'case' => [[$this, 'case']],
            'break' => [[$this, 'break']],
            'default' => [[$this, 'default']],
            'dump' => [[$this, 'dump']],
            'permission' => [[$this, 'permission'], [$this, 'endpermission']],
            'notpermission' => [[$this, 'notpermission'], [$this, 'endnotpermission']],
            'auth' => [[$this, 'auth'], [$this, 'endauth']],
            'guest' => [[$this, 'guest'], [$this, 'endguest']],
            'json' => [[$this, 'json']],
            'currentRoute' => [[$this, 'currentRoute'], [$this, 'endcurrentRoute']],
        ];
    }

    public function if($expression)
    {
        return "<?php if($expression): ?>";
    }

    public function elseif($expression)
    {
        return "<?php else if($expression): ?>";
    }

    public function else()
    {
        return "<?php else: ?>";
    }

    public function endif()
    {
        return "<?php endif; ?>";
    }

    public function each($expression)
    {
        if(strpos($expression, 'as') === false){
            $expression .= ' as $self';
        }
        return '<?php foreach(' . $expression . '): ?>';
    }

    public function endeach()
    {
        return '<?php endforeach; ?>';
    }

    public function repeat($expression)
    {
        return '<?php foreach(range(0, ' . ($expression - 1) . ') as $index): ?>';
    }

    public function endrepeat()
    {
        return '<?php endforeach; ?>';
    }

    public function php()
    {
        return '<?php';
    }

    public function endphp()
    {
        return '?>';
    }

    public function switch($expression)
    {
        $this->conditions['firstCaseSwitch'] = true;
        return '<?php switch(' . $expression . '):';
    }

    public function case($expression)
    {
        if($this->conditions['firstCaseSwitch']){
            $this->conditions['firstCaseSwitch'] = false;
            return ' case ' . $expression . ': ?>';
        }
        return '<?php case(' . $expression . '): ?>';
    }

    public function break()
    {
        return '<?php break; ?>';
    }

    public function default()
    {
        return '<?php default; ?>';
    }

    public function endswitch()
    {
        return '<?php endswitch; ?>';
    }

    public function dump($expression)
    {
        return '<?php dump(' . $expression . ') ?>';
    }

    public function permission($expression)
    {
        return '<?php if(permission(' . $expression . ')): ?>';
    }

    public function endpermission()
    {
        return '<?php endif; ?>';
    }

    public function notpermission($expression)
    {
        return '<?php if(!permission(' . $expression . ')): ?>';
    }

    public function endnotpermission()
    {
        return '<?php endif; ?>';
    }

    public function auth()
    {
        return '<?php if(isLogin()): ?>';
    }

    public function endauth()
    {
        return '<?php endif; ?>';
    }

    public function guest()
    {
        return '<?php if(!isLogin()): ?>';
    }

    public function endguest()
    {
        return '<?php endif; ?>';
    }

    public function json($expression)
    {
        return '<?= json_encode(' . $expression . ', JSON_UNESCAPED_UNICODE); ?>';
    }

    public function currentRoute(string $route)
    {
        $route = trim($route, '\'\"');
        return '<?php if(\Core\Facades\Route::currentRoute("' . $route . '")): ?>';
    }

    public function endcurrentRoute()
    {
        return '<?php endif; ?>';
    }
}