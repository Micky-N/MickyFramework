<?php

namespace Core\Compiler;

use Exception;
use Core\Facades\Cache;
use Core\Facades\Session;

class MkyEngine
{

    private const VIEW_SUFFIX = '.mky';
    private const CACHE_SUFFIX = '.cache.php';
    private const ECHO = ['{{', '}}'];
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
     * Affiche la view compilée
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
        }

        if(!file_exists($viewPath)){
            throw new Exception(sprintf('la view %s n\'existe pas', $viewPath));
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
            echo '<!-- cache modifié -->';
            Cache::addCache($cachePath, $this->view);
        }

        if(!$extends){
            ob_start();
            extract($data);
            $errors = Session::getFlashMessagesByType(Session::getConstant('FLASH_ERROR'));
            $success = Session::getFlashMessagesByType(Session::getConstant('FLASH_SUCCESS'));
            $messages = Session::getFlashMessagesByType(Session::getConstant('FLASH_MESSAGE'));
            require $cachePath;
            echo ob_get_clean();
        }
    }

    /**
     * Réecrit le nom de la view
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
     * Lance la compilation de la view
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
     * Compile les variables dans l'echo
     */
    public function parseVariables(): void
    {
        $this->view = preg_replace_callback('/' . self::ECHO[0] . '(.*?)' . self::ECHO[1] . '/', function ($variable) {
            return "<?=" . trim($variable[1]) . "?>";
        }, $this->view);
    }


    /**
     * Retourn la valeur de la config
     *
     * @param string $key
     * @return mixed
     */
    private function getConfig(string $key)
    {
        return $this->config[$key] ?? $this->config;
    }

    /**
     * Compile les fichiers inclus
     */
    public function parseIncludes(): void
    {
        $this->view = preg_replace_callback('/@include\(\'(.*?)\'\)/', function ($viewName) {
            return file_get_contents($this->getConfig('views') . '/' . $this->parseViewName($viewName[1]));
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * Compile le layout
     */
    public function parseExtends(): void
    {
        $this->view = preg_replace_callback('/@extends\(\'(.*?)\'\)/', function ($viewName) {
            return $this->view($this->getConfig('layouts') . '/' . $viewName[1], $this->data, true);
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * compile les blocks du layout
     */
    public function parseYields(): void
    {
        $this->view = preg_replace_callback('/@yield\(\'(.*?)\'\)/s', function ($yieldName) {
            return $this->sections[$yieldName[1]] ?? '';
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * Compile les sections de la view
     */
    public function parseSections(): void
    {
        $this->view = preg_replace_callback('/@section\(\'(.*?)\', (\"(.*?)\"|\'(.*?)\')\)/', function ($sectionDetail) {
            $this->sections[$sectionDetail[1]] = $sectionDetail[3] != "" ? $sectionDetail[3] : $sectionDetail[4];
            return '';
        }, str_replace(' (', '(', $this->view));

        $this->view = preg_replace_callback('/@section\(\'(.*?)\'\)(.*?)@endsection/s', function ($sectionName) {
            $this->sections[$sectionName[1]] = $sectionName[2];
            return '';
        }, str_replace(' (', '(', $this->view));
    }

    /**
     * Compile les directives
     *
     * @see MkyCompile
     */
    private function parseDirectives(): void
    {
        foreach ($this->directives->getDirectives() as $key => $mkyDirective) {
            foreach ($mkyDirective->encodes as $index => $directive) {
                $callback = $mkyDirective->callbacks[$index];
                $this->view = preg_replace_callback('/\B@(@?' . $directive . '?)([ \t]*)(\( ( ([^()]+) | (?3) )* \))?/x', function ($expression) use ($callback, $directive) {
                    $str = isset($expression[3]) ? substr($expression[3], 1, -1) : null;
                    return call_user_func($callback, $str);
                }, str_replace(' (', '(', $this->view));
            }
        }
    }
}
