<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 04/11/2019
 * Time: 21:19
 */

namespace Tests\Framework;


use Framework\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = new ServerRequest('GET', '/blog');
        $this->router->get('/blog', function () {
            return 'Hello';
        }, 'blog');
        $route = $this->router->match($request);
        $this->assertEquals('blog', $route->getName());
        $this->assertEquals('Hello', call_user_func($route->getCallBack(), $request));
    }

    public function testGetMethodIfURLDoesNotExists()
    {
        $request = new ServerRequest('GET', '/blog');
        $this->router->get('/blogaze', function () {
            return 'Hello';
        }, 'blog');
        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParams()
    {
        $request = new ServerRequest('GET', '/blog/mon-slug-8');
        $this->router->get('/blog', function () {
            return 'azeaeze';
        }, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function () {
            return 'Hello';
        }, 'post.show');
        $route = $this->router->match($request);
        $this->assertEquals('post.show', $route->getName());
        $this->assertEquals('Hello', call_user_func($route->getCallBack(), $request));
        $this->assertEquals(['slug' => 'mon-slug', 'id' => '8'], $route->getParams());
        // Test invalid url
        $route = $this->router->match(new ServerRequest('GET', '/mon_slug-8'));
        $this->assertEquals(null, $route);
    }

    public function testGenerateURI()
    {
        $this->router->get('/blog', function () {
            return 'azeaeze';
        }, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function () {
            return 'Hello';
        }, 'post.show');
        $uri = $this->router->generateURI('post.show', ['slug' => 'mon-article', 'id' => '18']);
        $this->assertEquals('/blog/mon-article-18', $uri);
    }

    public function testGenerateURIWithQueryParams()
    {
        $this->router->get('/blog', function () {
            return 'azeaeze';
        }, 'posts');
        $this->router->get(
            '/blog/{slug:[a-z0-9\-]+}-{id:\d+}',
            function () {
                return 'Hello';
            },
            'post.show');
        $uri = $this->router->generateURI(
            'post.show',
            ['slug' => 'mon-article', 'id' => '18'],
            ['p' => 2]
        );
        $this->assertEquals('/blog/mon-article-18?p=2', $uri);
    }

}