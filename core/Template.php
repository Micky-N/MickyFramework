<?php

namespace Core;

use Core\Facades\View;


class Template
{

    private string $layoutPath = '';
    private array $sections = [];
    private array $endsections = [];
    private array $content = [];
    private array $value = [];

    public function escape(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public function _templateParams(array $params = []): array
    {
        if ($params) {
            return $this->value = $params;
        }
        return $this->value;
    }

    public function layout(string $path = ''): string
    {
        $path = "layouts.$path";
        if ($path && !(bool) $this->layoutPath) {
            return $this->layoutPath = $path;
        }
    }

    public function content(string $name)
    {
        if(empty($this->content[$name])){
            $this->content[] = $name;
        }
        return $this->content[$name] ?? false;
    }

    public function section(string $name): bool
    {
        if (!isset($this->sections[$name])) {
            return $this->sections[$name] = ob_start();
        }
    }

    public function endsection(): bool
    {
        $this->endsections[] = !empty($this->endsections) ? count($this->endsections) : 0;
        $index = !empty($this->sections) ? array_keys($this->sections)[count($this->endsections) - 1] : null;
        if (!empty($this->sections[$index])) {
            $this->content[$index] = trim(ob_get_clean());
            $params = $this->_templateParams();
            $params[$index] = $this->content[$index];
            $this->_templateParams($params);
            unset($this->sections[$index]);
            array_shift($this->endsections);
            return true;
        }
        return false;
    }

    public function getLayoutPath(): string
    {
        return $this->layoutPath;
    }
}
