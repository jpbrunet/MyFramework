<?php

use App\Blog\BlogModule;
use function DI\create;
use function DI\get;

return [
    'blog.prefix' => '/blog',
    'twig.extensions' => \DI\add([
        get(\App\Blog\DemoExtension::class)
    ]),
    BlogModule::class => \DI\autowire()->constructorParameter('prefix', get('blog.prefix'))
];
