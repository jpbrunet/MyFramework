<?php

namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use App\Framework\Actions\RouteAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;

    use RouteAwareAction;

    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     * @param Router $router
     * @param PostTable $postTable
     */
    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
    }

    public function __invoke(Request $request)
    {
        if ($request->getAttribute('id')) {
            return $this->show($request);
        }
        return $this->index();
    }

    /**
     * @return string
     */
    public function index(): string
    {
        $posts = $this->postTable->findPaginated();
        return $this->renderer->render('@blog/index', compact('posts'));
    }

    /**
     * Show one post
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postTable->find($request->getAttribute('id'));
        if ($post->slug !== $slug) {
            return $this->redirect(
                'blog.show',
                [
                    'slug' => $post->slug,
                    'id' => $post->id
                ]
            );
        }

        return $this->renderer->render('@blog/show', [
            'post' => $post
        ]);
    }
}
