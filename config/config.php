<?php

use App\Framework\Renderer\TwigRendererFactory;
use App\Framework\Router\RouterTwigExtension;
use Framework\Renderer\RendererInterface;
use Framework\Router;

return [
    'database.host' => 'localhost',
    'database.port' => '5432',
    'database.user' => 'jeep',
    'database.pass' => 'jeep',
    'database.name' => 'my_framework',
    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class)
    ],
    Router::class => \DI\create(),
    RendererInterface::class => DI\factory(TwigRendererFactory::class),
    \PDO::class => function(\Psr\Container\ContainerInterface $c){
        return new PDO(
            'pgsql:host='.$c->get('database.host').';port='.$c->get('database.port').';dbname='.$c->get('database.name').';user='.$c->get('database.user').';password='.$c->get('database.pass')
        );
    }
];
