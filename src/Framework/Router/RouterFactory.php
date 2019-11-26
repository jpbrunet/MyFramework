<?php

namespace App\Framework\Router;

use Framework\Router;
use Psr\Container\ContainerInterface;

class RouterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $cache_folder = 'tmp/routes/';
        $cache = null;
        if (trim($container->get("env")) === 'production') {
            if (!file_exists($cache_folder)) {
                mkdir($cache_folder, 0777, true);
            }
            $cache = $cache_folder . 'fastroute.php.cache';
        }
        return new Router($cache);
    }
}
