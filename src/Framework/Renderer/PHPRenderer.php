<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 05/11/2019
 * Time: 01:16
 */

namespace Framework\Renderer;

class PHPRenderer implements RendererInterface
{
    /**
     * Define a default namespace
     */
    public const DEFAULT_NAMESPACE = '__MAIN';

    /**
     * @var array
     */
    private $paths = [];

    /**
     * Variables globally accessible for all views
     * @var array
     */
    private $globals = [];

    public function __construct(?string $defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

    /**
     * Allows you to add a path to load views
     * @param string $namespace
     * @param string|null $path
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        if ($path === null) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    /**
     * Allows to render a view
     * The path can be specified with namespace added via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }
        ob_start();
        $renderer = $this;
        extract($this->globals);
        extract($params);
        require($path);
        return ob_get_clean();
    }

    /***
     * @param string $view
     * @return bool
     */
    private function hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    /**
     * @param string $view
     * @return string
     */
    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamesepace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }

    /**
     * @param string $view
     * @return string
     */
    private function getNamesepace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }

    /**
     * Allows you to add global variables to all views
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }
}
