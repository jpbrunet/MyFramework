<?php

use App\Framework\Renderer\TwigRendererFactory;
use App\Framework\Router\RouterTwigExtension;
use App\Framework\Session\PhpSession;
use App\Framework\Session\SessionInterface;
use App\Framework\Twig\FlashExtension;
use App\Framework\Twig\FormExtension;
use App\Framework\Twig\PagerFantaExtension;
use App\Framework\Twig\TextExtension;
use App\Framework\Twig\TimeExtension;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

use function DI\get;

return [
    'database.host' => 'localhost',
    'database.port' => '5432',
    'database.user' => 'jeep',
    'database.pass' => 'jeep',
    'database.name' => 'my_framework',
    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        get(RouterTwigExtension::class),
        get(PagerFantaExtension::class),
        get(TextExtension::class),
        get(TimeExtension::class),
        get(FlashExtension::class),
        get(FormExtension::class)
    ],
    SessionInterface::class => \DI\create(PhpSession::class),
    Router::class => \DI\create(),
    RendererInterface::class => DI\factory(TwigRendererFactory::class),
    PDO::class => function (ContainerInterface $c) {
        $pdo = new PDO(
            'pgsql:host=' . $c->get('database.host') . ';port=' . $c->get('database.port') . ';dbname=' . $c->get('database.name') . ';user=' . $c->get('database.user') . ';password=' . $c->get('database.pass')
        );
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
];
