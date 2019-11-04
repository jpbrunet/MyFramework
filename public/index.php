<?php
require_once '../vendor/autoload.php';

use Framework\App;

$app = new App();
$demo = array();
$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);
