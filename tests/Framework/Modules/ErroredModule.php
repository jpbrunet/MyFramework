<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 05/11/2019
 * Time: 00:20
 */

namespace Tests\Framework\Modules;

class ErroredModule
{
    public function __construct(\Framework\Router $router)
    {
        $router->get('/demo', function (){
            return new \stdClass();
        }, 'demo');
    }
}