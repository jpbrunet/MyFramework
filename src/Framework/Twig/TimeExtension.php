<?php

namespace App\Framework\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('ago', [$this, 'ago'], ['is_safe' => ['html']])
        ];
    }

    public function ago(\DateTime $date, string $format = 'd/m/Y H:i')
    {
        return '<span class="need_to_be_rendered" datetime="' . $date->format(\DateTime::ISO8601) . '">'.
            $date->format($format) .
            '</span>';
    }
}
