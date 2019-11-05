<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 04/11/2019
 * Time: 19:29
 */

namespace Tests\Framework;


use App\Blog\BlogModule;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use function GuzzleHttp\Psr7\str;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\Framework\Modules\ErroredModule;
use Tests\Framework\Modules\StringModule;

class AppTest extends TestCase
{
    public function testRedirectTrailingSlash()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/demoslash/');
        $response = $app->run($request);
        $this->assertContains('/demoslash', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testBlog()
    {
        $app = new App([
            BlogModule::class
        ]);
        $request = new ServerRequest('GET', '/blog');
        $response = $app->run($request);

        $this->assertStringContainsString('<h1>Bienvenue sur le blog</h1>', (string)$response->getBody());
        $this->assertEquals(200, $response->getStatusCode());

        $requestSingle = new ServerRequest('GET', '/blog/article-de-test');

        $responseSingle = $app->run($requestSingle);

        $this->assertStringContainsString('<h1>Bienvenue sur l\'article article-de-test</h1>', (string)$responseSingle->getBody());
    }


    public function testThrowExceptionIfNoResponseSend()
    {

        $app = new App([
            ErroredModule::class
        ]);
        $resquest = new ServerRequest('GET', '/demo');
        $this->expectException(\Exception::class);
        $app->run($resquest);
    }

    public function testConvertStringToResponse()
    {

        $app = new App([
            StringModule::class
        ]);
        $resquest = new ServerRequest('GET', '/demo');
        $response = $app->run($resquest);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('DEMO',(string) $response->getBody());
    }

    public function testError404()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/aze');
        $response = $app->run($request);
        $this->assertStringContainsString('<h1>Erreur 404</h1>', (string)$response->getBody()->__toString());
        $this->assertEquals(404, $response->getStatusCode());
    }


}