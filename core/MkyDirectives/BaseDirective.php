<?php


namespace Core\MkyDirectives;

use MkyEngine\Interfaces\MkyDirectiveInterface;

class BaseDirective implements MkyDirectiveInterface
{

    public function getFunctions()
    {
        return [
            'assets' => [$this, 'assets'],
            'dump' => [$this, 'dump'],
            'can' => [[$this, 'can'], [$this, 'endcan']],
            'notcan' => [[$this, 'notcan'], [$this, 'endnotcan']],
            'auth' => [[$this, 'auth'], [$this, 'endauth']],
            'currentRoute' => [[$this, 'currentRoute'], [$this, 'endcurrentRoute']],
            'route' => [$this, 'route']
        ];
    }


    public function dump($var)
    {
        return "<?php dump($var) ?>";
    }

    public function can($permission, $subject)
    {
        $condition = json_encode(\Core\Facades\Permission::authorizeAuth($permission, $subject));
        return "<?php if($condition): ?>";
    }

    public function endcan()
    {
        return '<?php endif; ?>';
    }

    public function notcan($permission, $subject)
    {
        $condition = json_encode(\Core\Facades\Permission::authorizeAuth($permission, $subject));
        return "<?php if(!$condition): ?>";
    }

    public function endnotcan()
    {
        return '<?php endif; ?>';
    }

    public function auth(bool $is = null)
    {
        $cond = json_encode($is === (new \Core\AuthManager())->isLogin());
        return "<?php if($cond): ?>";
    }

    public function endauth()
    {
        return '<?php endif; ?>';
    }

    public function currentRoute(string $name = '', bool $path = false)
    {
        $current = \Core\Facades\Route::currentRoute($name, $path);
        if($name){
            $current = json_encode($current);
            return "<?php if($current): ?>";
        }
        return $current;
    }

    public function endcurrentRoute()
    {
        return '<?php endif; ?>';
    }

    public function assets(string $path)
    {
        $path = trim($path, '\'\"');
        return BASE_ULR . 'public/' . 'assets/' . $path;
    }

    public function route(string $name, array $params = [])
    {
        return \Core\Facades\Route::generateUrlByName($name, $params);
    }
}