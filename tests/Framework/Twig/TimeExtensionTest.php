<?php

namespace Tests\Framework\Twig;

use App\Framework\Twig\TextExtension;
use App\Framework\Twig\TimeExtension;
use PHPUnit\Framework\TestCase;

class TimeExtensionTest extends TestCase
{

    /**
     * @var TimeExtension
     */
    private $timeExtension;

    public function setUp(): void
    {
        $this->timeExtension = new TimeExtension();
    }

    public function testDateFormat()
    {
        $date = new \DateTime();
        $format = 'd/m/Y H:i';
        $result = '<span class="need_to_be_rendered" datetime="' . $date->format(\DateTime::ISO8601) . '">'. $date->format($format) .'</span>';
        $this->assertEquals($result, $this->timeExtension->ago($date));
    }
}
