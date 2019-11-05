<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 04/11/2019
 * Time: 23:56
 */

namespace App\Blog;

use App\Blog\Actions\BlogAction;
use App\Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerResponseInterface;

class BlogModule extends Module
{

    public const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(string $prefix, Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('blog', __DIR__ . '/views');
        $router->get($prefix, BlogAction::class, 'blog.index');
        $router->get($prefix .'/{slug:[a-z\0-9]+}', BlogAction::class, 'blog.show');
    }
}
