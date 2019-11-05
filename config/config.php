<?php

use App\Framework\Renderer\TwigRendererFactory;
use App\Framework\Router\RouterTwigExtension;
use Framework\Renderer\RendererInterface;
use Framework\Router;

return [
    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class)
    ],
    Router::class => \DI\create(),
    RendererInterface::class => DI\factory(TwigRendererFactory::class)
];
