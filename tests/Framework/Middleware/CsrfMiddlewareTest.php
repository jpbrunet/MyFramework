<?php

namespace Test\Framework\Middleware;

use App\Framework\Exception\CsrfInvalidException;
use App\Framework\Middleware\CsrfMiddleware;
use App\Framework\Session\PhpSession;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddlewareTest extends TestCase
{

    /**
     * @var CsrfMiddleware
     */
    private $middleware;

    private $session;

    protected function setUp(): void
    {
        $this->session = [];
        $this->middleware = new CsrfMiddleware($this->session);
    }

    public function testLetGetRequestPass()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('handle')
            ->willReturn(new Response());

        $request = (new ServerRequest('GET', '/demo'));
        $this->middleware->process($request, $delegate);
    }

    public function testBlockPostRequestWithoutCsrf()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $delegate->expects($this->never())
            ->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $delegate);
    }

    public function testLetPostWithTokenPass()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();

        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $delegate);
    }

    public function testLetPostWithTokenPassOnce()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();

        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $delegate);

        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $delegate);
    }

    public function testBlockPostRequestWithInvalidCsrf()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $delegate->expects($this->never())
            ->method('handle');
        $this->middleware->generateToken();
        $request = (new ServerRequest('POST', '/demo'));
        $request = $request->withParsedBody(['_csrf' => 'azeaz']);
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $delegate);
    }

//    public function testLimitTheTokenNumber()
//    {
//        for ($i = 0; $i < 100; ++$i) {
//            $token = $this->middleware->generateToken();
//        }
//        $this->assertCount(50, $this->session['csrf']);
//        $this->assertEquals($token, $this->session['csrf'][49]);
//    }
}
