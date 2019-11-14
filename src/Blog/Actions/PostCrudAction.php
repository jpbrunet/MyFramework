<?php

namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Session\FlashService;
use App\Framework\Validator;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostCrudAction
 * @package App\Blog\Actions
 */
class PostCrudAction extends CrudAction
{
    /**
     * @var string
     */
    protected $viewPath = "@blog/admin/posts";

    /**
     * @var string
     */
    protected $routePrefix = "admin.blog";
    /**
     * @var CategoryTable
     */
    private $categoryTable;

    /**
     * PostCrudAction constructor.
     * @param RendererInterface $renderer
     * @param Router $router
     * @param PostTable $table
     * @param FlashService $flash
     * @param CategoryTable $categoryTable
     */
    public function __construct(
        RendererInterface $renderer,
        Router $router,
        PostTable $table,
        FlashService $flash,
        CategoryTable $categoryTable
    ) {
        parent::__construct($renderer, $router, $table, $flash);
        $this->categoryTable = $categoryTable;
    }

    protected function fromParams(array $params): array
    {
        $params['categories'] = $this->categoryTable->findList();
        return $params;
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
            return in_array($key, ['name', 'content', 'slug', 'created_at', 'category_id']);
        }, ARRAY_FILTER_USE_KEY);
        $params = array_merge($params, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $params;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Validator
     */
    protected function getValidator(ServerRequestInterface $request)
    {
        return parent::getValidator($request)
            ->required('content', 'name', 'slug', 'created_at', 'category_id')
            ->length('content', 10)
            ->length('name', 2, 250)
            ->length('slug', 2, 250)
            ->exists('category_id', $this->categoryTable->getTable(), $this->categoryTable->getPdo())
            ->dateTime('created_at')
            ->slug('slug');
    }
}
