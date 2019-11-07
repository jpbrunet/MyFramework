<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 05/11/2019
 * Time: 14:52
 */

namespace App\Framework\Renderer;

use Framework\Renderer\RendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Allows you to add a path to load views
     * @param string $namespace
     * @param string|null $path
     * @throws \Twig\Error\LoaderError
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->twig->getLoader()->addPath($path, $namespace);
    }

    /**
     * Allows to render a view
     * The path can be specified with namespace added via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.html.twig', $params);
    }

    /**
     * Allows you to add global variables to all views
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
