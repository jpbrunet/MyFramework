<?php

namespace App\Framework\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use App\Framework\Database\Table;
use App\Framework\Session\FlashService;
use App\Framework\Validator;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class CrudAction
 * @package App\Framework\Actions
 */
class CrudAction
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
     * @var Table
     */
    private $table;
    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var string
     */
    protected $viewPath;

    /**
     * @var string
     */
    protected $routePrefix;

    /**
     * @var string[]
     */
    protected $message = [
        'create' => "L'élément a bien été créé",
        'edit' => "L'élément a bien été modifié"
    ];

    use RouteAwareAction;

    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     * @param Router $router
     * @param $table
     * @param FlashService $flash
     */
    public function __construct(
        RendererInterface $renderer,
        Router $router,
        Table $table,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->table = $table;
        $this->flash = $flash;
    }

    /**
     * @param Request $request
     * @return ResponseInterface|string
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);
        if ($request->getMethod() === "DELETE") {
            return $this->delete($request);
        }
        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    /**
     * Show list of elements
     * @param Request $request
     * @return string
     */
    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findPaginated(12, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items'));
    }

    /**
     * Create a new element
     * @param Request $request
     * @return ResponseInterface|string
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $item = $this->getNewEntity();
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);

            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($params);
                $this->flash->success($this->message['create']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $item = $params;
            $errors = $validator->getErrors();
        }

        return $this->renderer->render(
            $this->viewPath . '/create',
            $this->fromParams(compact('item', 'errors'))
        );
    }

    /**
     * Update one element
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->table->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $params['updated_at'] = date('Y-m-d H:i:s');
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->update($item->id, $params);
                $this->flash->success($this->message['edit']);
                return $this->redirect($this->routePrefix . '.index');
            }

            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;
        }

        return $this->renderer->render(
            $this->viewPath . '/edit',
            $this->fromParams(compact('item', 'errors'))
        );
    }

    /**
     * Remove one element
     * @param Request $request
     * @return ResponseInterface
     */
    public function delete(Request $request)
    {
        $this->table->delete($request->getAttribute('id'));
        return $this->redirect('admin.blog.index');
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getParams(Request $request)
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    protected function getValidator(Request $request)
    {
        return new Validator($request->getParsedBody());
    }

    /**
     * @return array
     */
    protected function getNewEntity()
    {
        return [];
    }

    /**
     * Processes the parameters to be sent to the view
     * @param array $params
     * @return array
     */
    protected function fromParams(array $params): array
    {
        return $params;
    }
}
