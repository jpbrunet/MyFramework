<?php

namespace Tests\Framework\Twig;

use App\Framework\Twig\FormExtension;
use PHPUnit\Framework\TestCase;

class FormExtensionTest extends TestCase
{
    /**
     * @var FormExtension
     */
    private $formExtension;

    /**
     *
     */
    protected function setUp(): void
    {
        $this->formExtension = new FormExtension();
    }

    private function myTrim(string $string){
        $lines = explode("\n", $string);
        $lines = array_map("trim", $lines);
        return implode('', $lines);
    }

    private function assertSimilar(string $expected, string $actual){
        $exp = $this->myTrim($expected);
        $act = $this->myTrim($actual);
        $this->assertEquals($exp, $act);
    }

    public function testField()
    {
        $html = $this->formExtension->field([],'name', 'demo', 'Titre');
        $exp = "<div class=\"form-group\">
                <label for=\"name\">Titre</label>
                <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" value=\"demo\">
            </div>";
        $this->assertSimilar($exp, $html);
    }

    public function testTextarea()
    {
        $html = $this->formExtension->field([],'name', 'demo', 'Titre', ['type' => "textarea"]);
        $this->assertSimilar("
            <div class=\"form-group\">
                <label for=\"name\">Titre</label>
                <textarea class=\"form-control\" id=\"name\" name=\"name\">demo</textarea>
            </div>
        ", $html);
    }

    public function testFieldWithErrors()
    {
        $context = ['errors' => ['name' => 'erreur']];
        $html = $this->formExtension->field($context,'name', 'demo', 'Titre');
        $exp = "<div class=\"form-group has_danger\">
                <label for=\"name\">Titre</label>
                <input type=\"text\" class=\"form-control is-invalid\" id=\"name\" name=\"name\" value=\"demo\">
                <small class=\"form-text text-muted\">erreur</small>
            </div>";
        $this->assertSimilar($exp, $html);
    }
}
