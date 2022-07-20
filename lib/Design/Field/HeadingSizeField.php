<?php


namespace DioBrando\Design\Field;


use DioBrando\Design\Customize\CustomizeField;

class HeadingSizeField extends CustomizeField
{
    public function __construct(string $key, array $args = [])
    {
        $args['choices'] = [
            'default' => 'Default',
            'medium' => 'Medium',
            'large' => 'Large',
            'xlarge' => 'X-Large'
        ];
        parent::__construct($key, $args);
    }

    public function getProperties()
    {
        $key = $this->getKey();
        $size = $this->getValue();
        return [
            "{$key}__font-size--default" => "headings__{$size}__font-size--default",
            "{$key}__font-size--max" => "headings__{$size}__font-size--max",
            "{$key}__font-size--diff" => "headings__{$size}__font-size--diff",
            "{$key}__color" => "headings__{$size}__color",
        ];
    }
}
