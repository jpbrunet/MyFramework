<?php
require_once '../vendor/autoload.php';

use Framework\App;

$renderer = new \Framework\Renderer();
$renderer->addPath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views');

$app = new App(
    [
        \App\Blog\BlogModule::class
    ],
    [
        'renderer' => $renderer
    ]
);
$demo = array();
$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);
