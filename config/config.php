<?php

use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Renderer\TwigRendererFactory;
use App\Framework\Router\RouterFactory;
use App\Framework\Router\RouterTwigExtension;
use App\Framework\Session\PhpSession;
use App\Framework\Session\SessionInterface;
use App\Framework\Twig\CsrfExtension;
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
    'env' => \DI\env('ENV', 'production'),
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
        get(FormExtension::class),
        get(CsrfExtension::class)
    ],
    SessionInterface::class => \DI\create(PhpSession::class),
    CsrfMiddleware::class => \DI\create()->constructor(get(SessionInterface::class))->lazy(),
    Router::class => \DI\factory(RouterFactory::class),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),
    PDO::class => function (ContainerInterface $c) {
        $pdo = new PDO(
            'pgsql:host=' . $c->get('database.host') . ';port=' . $c->get('database.port') . ';dbname=' . $c->get('database.name') . ';user=' . $c->get('database.user') . ';password=' . $c->get('database.pass')
        );
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
];
