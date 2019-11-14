<?php

namespace App\Framework\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function DI\string;

/**
 * Generate a form fields
 * Class FormExtension
 * @package App\Framework\Twig
 */
class FormExtension extends AbstractExtension
{
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'field',
                [$this, 'field'],
                [
                    'is_safe' => ["html"],
                    'needs_context' => true
                ]
            )
        ];
    }

    /**
     * Generates the HTML code of a field
     * @param array $context
     * @param string $key
     * @param $value
     * @param string|null $label
     * @param array $options
     * @return string
     */
    public function field(array $context, string $key, $value, ?string $label = null, array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorsHTML($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? "")),
            'id' => $key,
            'name' => $key
        ];

        if ($error) {
            $class .= ' has_danger';
            $attributes['class'] .= ' is-invalid';
        }
        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } elseif (array_key_exists("options", $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }

        return "
            <div class=\"{$class}\">
                <label for=\"{$key}\">{$label}</label>
                 {$input}
                 {$error}
            </div>                
        ";
    }


    /**
     * Generates HTML based on context errors
     * @param array $context
     * @param string $key
     * @return string
     */
    private function getErrorsHTML(array $context, string $key): string
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class=\"form-text text-muted\">{$error}</small>";
        }
        return "";
    }

    /**
     * Generate a <textarea> field
     * @param string|null $value
     * @param array $attributes
     * @return string
     */
    private function textarea(?string $value, array $attributes): string
    {
        return "<textarea " . $this->getHTMLFromArray($attributes) . ">{$value}</textarea>";
    }

    /**
     * Generate a <input> field
     * @param string|null $value
     * @param array $attributes
     * @return string
     */
    private function input(?string $value, array $attributes): string
    {
        return "<input type=\"text\" " . $this->getHTMLFromArray($attributes) . " value=\"{$value}\">";
    }

    /**
     * Generate a <select> field
     * @param string|null $value
     * @param array $options
     * @param array $attributes
     * @return string
     */
    private function select(?string $value, array $options, array $attributes)
    {
        $htmlOptions = array_reduce(array_keys($options), function (string $html, string $key) use ($options, $value) {
            $params = ['value' => $key, 'selected' => $key === $value];
            return $html . '<option ' . $this->getHTMLFromArray($params) .'>'. $options[$key] .'</option>';
        }, "");
        return "<select " . $this->getHTMLFromArray($attributes) . ">{$htmlOptions}</select>";
    }

    /**
     * Transforms an array $key => $value into an HTML attribute
     * @param array $attributes
     * @return string
     */
    private function getHTMLFromArray(array $attributes)
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string) $key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }

    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }
}
