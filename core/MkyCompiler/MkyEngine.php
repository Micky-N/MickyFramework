<?php

namespace Core\MkyCompiler;

use Exception;
use Core\Facades\Cache;
use Core\Facades\Session;

class MkyEngine
{

    private const VIEW_SUFFIX = '.mky';
    private const CACHE_SUFFIX = '.cache.php';
    private const ECHO = ['{{', '}}'];
    private const OPEN_FUNCTION = ['{@', '}'];
    private const CLOSE_FUNCTION = ['{\/', '}'];
    private array $config;
    /**
     * @var null|string|false
     */
    private $view;
    private array $sections = [];
    private array $data = [];
    private string $viewName = '';
    private string $viewPath = '';

    public array $errors;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->errors = $_GET['errors'] ?? [];
    }

    /**
     * Compile and send the view
     *
     * @param string $viewName
     * @param array $data
     * @param bool $extends
     * @return false|string
     * @throws Exception
     */
    public function view(string $viewName, array $data = [], $extends = false)
    {
        $viewPath = $this->getConfig('views') . '/' . $this->parseViewName($viewName);
        if(!$extends){
            $this->viewName = $viewName;
            $this->data = $data;
            $this->viewPath = $viewPath;
        } else {
            $viewPath = $this->getConfig('layouts') . '/' . $this->parseViewName($viewName);
        }
        if(!file_exists($viewPath)){
            throw new Exception(sprintf('View %s does not exist', $viewPath));
        }

        $this->view = file_get_contents($viewPath);
        $this->parse();


        $cachePath = $this->getConfig('cache') . '/' . md5($this->viewName) . self::CACHE_SUFFIX;
        if(!file_exists($cachePath)){
            Cache::addCache($cachePath, $this->view);
        }

        if(
            (filemtime($cachePath) < filemtime($viewPath)) ||
            (filemtime($cachePath) < filemtime($this->viewPath))
        ){
            echo '<!-- cache file updated -->';
            Cache::addCache($cachePath, $this->view);
        }

        if(!$extends){
            ob_start();
            extract($data);
            $errors = Session::getFlashMessagesByType(Session::getConstant('FLASH_ERROR'));
            $success = Session::getFlashMessagesByType(Session::getConstant('FLASH_SUCCESS'));
            $flashMessages = Session::getFlashMessagesByType(Session::getConstant('FLASH_MESSAGE'));
            require $cachePath;
            echo ob_get_clean();
        }
    }

    /**
     * Format the view file
     *
     * @param string $viewName
     * @return string
     */
    private function parseViewName(string $viewName): string
    {
        $viewName = str_replace('.', '/', $viewName);
        return $viewName . self::VIEW_SUFFIX;
    }

    /**
     * Compile the view
     */
    public function parse()
    {
        $this->parseIncludes();
        $this->parseVariables();
        $this->parseSections();
        $this->parseExtends();
        $this->parseYields();
        $this->parseDirectives();
    }

    /**
     * Compile echo variables
     */
    public function parseVariables(): void
    {
        $this->view = preg_replace_callback(sprintf("/%s(.*?)(#(.*?)(\((.*?)\)?)?)?%s/", self::ECHO[0], self::ECHO[1]), function ($variable) {
            $var = trim($variable[1]);
            if(isset($variable[2])){
                $args = isset($variable[4]) ? trim(trim($variable[4]), '\(\)') : null;
                $params = ["'" . trim($variable[3]) . "'", $var, "[" . $args . "]"];
                $var = sprintf("call_user_func_array([new %s(), '%s'], [%s])", MkyFormatter::class, 'callFormat', join(', ', array_filter($params)));
            }
            return "<?= $var ?>";
        }, $this->view);
    }


    /**
     * Get config value
     *
     * @param string $key
     * @return mixed
     */
    private function getConfig(string $key)
    {
        return $this->config[$key] ?? $this->config;
    }

    /**
     * Compile included files
     */
    public function parseIncludes(): void
    {
        $this->view = preg_replace_callback(sprintf("/%s include name=(.*?) ?%s/s", self::OPEN_FUNCTION[0], self::OPEN_FUNCTION[1]), function ($viewName) {
            $name = trim($viewName[1], '"\'');
            return file_get_contents($this->getConfig('views') . '/' . $this->parseViewName($name));
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * Compile the layout
     */
    public function parseExtends(): void
    {
        $this->view = preg_replace_callback(sprintf("/%s extends name=(.*?) ?%s/s", self::OPEN_FUNCTION[0], self::OPEN_FUNCTION[1]), function ($viewName) {
            $name = trim($viewName[1], '"\'');
            return $this->view($name, $this->data, true);
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * compile layout yield block
     */
    public function parseYields(): void
    {
        $this->view = preg_replace_callback(sprintf("/%s yield name=(.*?) (default=(.*?))? ?%s/s", self::OPEN_FUNCTION[0], self::OPEN_FUNCTION[1]), function ($yieldName) {
            $name = trim($yieldName[1], '"\'');
            $default = isset($yieldName[2]) ? trim($yieldName[3], '"\'') : '';
            return $this->sections[$name] ?? $default;
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * Compile view sections
     */
    public function parseSections(): void
    {
        $this->view = preg_replace_callback(sprintf("/%s section name=(.*?) value=(.*)? ?%s/", self::OPEN_FUNCTION[0], self::OPEN_FUNCTION[1]), function ($sectionDetail) {
            $value = trim($sectionDetail[2], '"\'');
            $name = trim($sectionDetail[1], '"\'');
            $this->sections[$name] = $value;
            return '';
        }, str_replace(' (', '(', $this->view));

        $this->view = preg_replace_callback(sprintf("/%s section name=(.*?) ?%s(.*?)%s section ?%s/s", self::OPEN_FUNCTION[0], self::OPEN_FUNCTION[1], self::CLOSE_FUNCTION[0], self::CLOSE_FUNCTION[1]), function ($sectionName) {
            $name = trim($sectionName[1], '"\'');
            $this->sections[$name] = $sectionName[2];
            return '';
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * Compile directives
     *
     * @see MkyCompile
     */
    private function parseDirectives(): void
    {
        $this->view = preg_replace_callback(sprintf("/%s ([\w]+)? (.*?) ?%s/", self::OPEN_FUNCTION[0], self::OPEN_FUNCTION[1]), function ($expression) {
            $function = trim($expression[1]);
            $expression = isset($expression[2]) ? trim($expression[2]) : null;
            $params = [$function, $expression];
            return call_user_func_array([new MkyDirective(), 'callFunction'], $params);
        }, str_replace(' (', '(', $this->view));

        $this->view = preg_replace_callback(sprintf("/%s ([\w]+)? ?%s/", self::CLOSE_FUNCTION[0], self::CLOSE_FUNCTION[1]), function ($expression) {
            $function = trim($expression[1]);
            $params = [$function, null, false];
            return call_user_func_array([new MkyDirective(), 'callFunction'], $params);
        }, str_replace(' (', '(', $this->view));
    }
}
