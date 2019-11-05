<?php

namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');
        if ($slug) {
            return $this->show($slug);
        }
        return $this->index();
    }

    /**
     * @return string
     */
    public function index(): string
    {
        return $this->renderer->render('@blog/index');
    }

    /**
     * @param string $slug
     * @return string
     */
    public function show(string $slug): string
    {
        return $this->renderer->render('@blog/show', [
            'slug' => $slug
        ]);
    }
}