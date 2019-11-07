<?php

namespace Tests\Framework\Twig;

use App\Framework\Twig\TextExtension;
use PHPUnit\Framework\TestCase;

class TextExtensionTest extends TestCase
{

    /**
     * @var TextExtension
     */
    private $textExtension;

    public function setUp(): void
    {
        $this->textExtension = new TextExtension();
    }

    public function testExtensionWithShortText()
    {
        $text = 'Salut';
        $this->assertEquals($text, $this->textExtension->excerpt($text, 10));
    }

    public function testExtensionWithLongText()
    {
        $text = 'Salut les gens';
        $this->assertEquals("Salut...", $this->textExtension->excerpt($text, 7));
        $this->assertEquals("Salut les...", $this->textExtension->excerpt($text, 12));
    }
}
