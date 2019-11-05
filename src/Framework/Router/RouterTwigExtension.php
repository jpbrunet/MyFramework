<?php

namespace App\Framework\Router;

use Framework\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterTwigExtension extends AbstractExtension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * RouterTwigExtension constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'pathFor'])
        ];
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateURI($path, $params);
    }
}
