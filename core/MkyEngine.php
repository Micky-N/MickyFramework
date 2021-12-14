<?php

namespace Core;

use Exception;

class MkyEngine
{

    private const VIEW_SUFFIX = '.mky';
    private const CACHE_SUFFIX = '.cache.php';
    private array $config;
    /**
     * @var null|string|false
     */
    private $view;
    private array $sections = [];
    private array $data = [];
    private string $viewName = '';
    private string $viewPath = '';
    private MkyCompile $directives;

    public array $errors;

    public function __construct(array $config)
    {
        $this->directives = new MkyCompile();
        $this->config = $config;
        $this->errors = $_GET['errors'] ?? [];
    }

    /**
     * @param string $viewName
     * @param array $data
     * @param bool $extends
     * @return false|string
     * @throws Exception
     */
    public function view(string $viewName, array $data = [], $extends = false)
    {
        extract($data);
        $viewPath = $this->getConfig('views') . DIRECTORY_SEPARATOR . $this->parseViewName($viewName);
        if(!$extends){
            $this->viewName = $viewName;
            $this->data = $data;
            $this->viewPath = $viewPath;
        }

        if(!file_exists($viewPath)){
            throw new Exception(sprintf('la view %s n\'existe pas', $viewPath));
        }

        $this->view = file_get_contents($viewPath);
        $this->parse();


        $cachePath = $this->getConfig('cache') . DIRECTORY_SEPARATOR . md5($this->viewName) . self::CACHE_SUFFIX;
        if(!file_exists($cachePath)){
            file_put_contents($cachePath, $this->view);
        }

        if(filemtime($cachePath) < filemtime($viewPath) ||
            filemtime($cachePath) < filemtime($this->viewPath)
        ){
            echo '<!-- cache modifié -->';
            file_put_contents($cachePath, $this->view);
        }

        if(!$extends){
            ob_start();
            require $cachePath;
            echo ob_get_clean();
        }

    }

    public function withError($message)
    {

    }

    private function parseViewName(string $viewName): string
    {
        $viewName = str_replace('.', DIRECTORY_SEPARATOR, $viewName);
        return $viewName . self::VIEW_SUFFIX;
    }

    public function parse()
    {
        $this->parseIncludes();
        $this->parseVariables();
        $this->parseSections();
        $this->parseExtends();
        $this->parseYields();
        $this->parseDirectives();
    }

    public function parseVariables(): void
    {
        $this->view = preg_replace_callback('/{{(.*?)}}/', function ($variable) {
            return "<?=" . trim($variable[1]) . "?>";
        }, $this->view);
    }


    /**
     * @param string $key
     * @return mixed
     */
    private function getConfig(string $key)
    {
        return $this->config[$key] ?? $this->config;
    }

    public function parseIncludes(): void
    {
        $this->view = preg_replace_callback('/@include\(\'(.*?)\'\)/', function ($viewName) {
            return file_get_contents($this->getConfig('views') . DIRECTORY_SEPARATOR . $this->parseViewName($viewName[1]));
        }, $this->view);
    }

    public function parseExtends(): void
    {
        $this->view = preg_replace_callback('/@extends\(\'(.*?)\'\)/', function ($viewName) {
            return $this->view($this->getConfig('layouts') . DIRECTORY_SEPARATOR . $viewName[1], $this->data, true);
        }, $this->view);
    }

    public function parseYields(): void
    {
        $this->view = preg_replace_callback('/@yield\(\'(.*?)\'\)/s', function ($yieldName) {
            return $this->sections[$yieldName[1]] ?? '';
        }, $this->view);
    }

    public function parseSections(): void
    {
        $this->view = preg_replace_callback('/@section\(\'(.*?)\', \'(.*?)\'\)/', function ($sectionDetail) {
            $this->sections[$sectionDetail[1]] = $sectionDetail[2];
            return '';
        }, $this->view);

        $this->view = preg_replace_callback('/@section\(\'(.*?)\'\)(.*?)@endsection/s', function ($sectionName) {
            $this->sections[$sectionName[1]] = $sectionName[2];
            return '';
        }, $this->view);
    }

    public function directive(string $key, $callback): void
    {
        $this->directives[$key] = $callback;
    }

    /**
     * Compile directives
     * see MkyCompile
     */
    private function parseDirectives(): void
    {
        foreach ($this->directives->getDirectives() as $key => $directives) {
            foreach ($directives as $directive) {
                $index = array_search($directive, $directives);
                $callback = $this->directives->getCallbacks($key, $index);
                $this->view = preg_replace_callback('/@' . $directive . '(\((.*?)?\))?/', function ($expression) use ($callback) {
                    return call_user_func($callback, $expression[2] ?? null);
                }, $this->view);
            }
        }
    }
}