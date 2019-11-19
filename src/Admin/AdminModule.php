<?php

namespace App\Admin;

use App\Framework\Module;
use App\Framework\Renderer\TwigRenderer;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class AdminModule extends Module
{
    public const DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR .'config.php';

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        AdminTwigExtension $extension,
        string $prefix
    ) {
        $renderer->addPath('admin', __DIR__ . DIRECTORY_SEPARATOR . 'views');
        $router->get($prefix, DashboardAction::class, 'admin');
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($extension);
        }
    }
}
