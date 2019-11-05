<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 05/11/2019
 * Time: 15:02
 */

namespace Tests\Framework\Renderer;

use Framework\Renderer\PHPRenderer;

class PHPRendererTest
{

    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer(__DIR__ . '/views');
    }

    public function testRenderTheRightPath()
    {
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $content = $this->renderer->render('@blog/demo');
        $this->assertEquals('Salut les gens', $content);
    }

    public function testRenderTheDefaultPath()
    {
        $content = $this->renderer->render('demo');
        $this->assertEquals('Salut les gens', $content);
    }

    public function testRenderWithParams()
    {
        $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
        $this->assertEquals('Salut Marc', $content);
    }

    public function testGlobalParams()
    {
        $this->renderer->addGlobal('nom', 'Marc');
        $content = $this->renderer->render('demoparams');
        $this->assertEquals('Salut Marc', $content);
    }
}