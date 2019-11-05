<?php
require_once '../vendor/autoload.php';

use Framework\App;

$modules = [
    \App\Blog\BlogModule::class
];

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) . '/config/config.php');

foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

$builder->addDefinitions(dirname(__DIR__) . '/config.php');
$container = $builder->build();

$app = new App($container, $modules);

$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

\Http\Response\send($response);
