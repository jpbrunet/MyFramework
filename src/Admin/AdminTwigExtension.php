<?php

namespace App\Admin;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminTwigExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $widgets;

    /**
     * AdminTwigExtension constructor.
     * @param array $widgets
     */
    public function __construct(array $widgets)
    {
        $this->widgets = $widgets;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('admin_menu', [$this, 'renderMenu'], ["is_safe" => ['html']])
        ];
    }

    public function renderMenu(): string
    {
        return array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget) {
            return $html . $widget->renderMenu();
        }, '');
    }
}
