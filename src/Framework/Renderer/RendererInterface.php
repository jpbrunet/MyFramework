<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 05/11/2019
 * Time: 14:45
 */

namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * Allows you to add a path to load views
     * @param string $namespace
     * @param string|null $path
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     * Allows to render a view
     * The path can be specified with namespace added via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string;

    /**
     * Allows you to add global variables to all views
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void;
}
