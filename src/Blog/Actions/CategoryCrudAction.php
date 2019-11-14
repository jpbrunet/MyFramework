<?php

namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\CategoryTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Session\FlashService;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class CategoryCrudAction extends CrudAction
{
    /**
     * @var string
     */
    protected $viewPath = "@blog/admin/categories";

    /**
     * @var string
     */
    protected $routePrefix = "admin.blog.category";

    /**
     * PostCrudAction constructor.
     * @param RendererInterface $renderer
     * @param Router $router
     * @param CategoryTable $table
     * @param FlashService $flash
     */
    public function __construct(RendererInterface $renderer, Router $router, CategoryTable $table, FlashService $flash)
    {
        parent::__construct($renderer, $router, $table, $flash);
    }

    /**
     * @return Post
     * @throws \Exception
     */
    protected function getNewEntity()
    {
        $post = new Post();
        $post->created_at = new \DateTime();
        return $post;
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    protected function getParams(ServerRequestInterface $request)
    {

        $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug']);
        }, ARRAY_FILTER_USE_KEY);

        return $params;
    }

    /**
     * @param ServerRequestInterface $request
     * @return \App\Framework\Validator
     */
    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('name', 'slug')
            ->length('name', 2, 250)
            ->length('slug', 2, 250)
            ->slug('slug');
    }
}
