<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

use App\Admin\AdminModule;
use App\Blog\BlogModule;
use App\Framework\Middleware\CsrfMiddleware;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use App\Framework\Middleware\DispatcherMiddleware;
use App\Framework\Middleware\MethodMiddleware;
use App\Framework\Middleware\NotFoundMiddleware;
use App\Framework\Middleware\RouterMiddleware;
use App\Framework\Middleware\TrailingSlashMiddleware;
use Middlewares\Whoops;

$modules = [
    AdminModule::class,
    BlogModule::class
];

$app = (new App('config/config.php'))
    ->addModule(AdminModule::class)
    ->addModule(BlogModule::class)
    ->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class)
;

if (php_sapi_name() !== 'cli') {
    $response = $app->run(ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
