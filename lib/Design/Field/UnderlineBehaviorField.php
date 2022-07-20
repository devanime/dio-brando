<?php

namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;

class UnderlineBehaviorField extends CustomizeField
{
    public function __construct(string $key, array $args = [])
    {
        $args['type'] = 'radio';
        $args['choices'] = [
            'hover' => 'Underlines on hover only',
            'hover_off' => 'Underlines on non-hover only',
            'on' => 'Underlines always on'
        ];
        parent::__construct($key, $args);
    }

    public function getProperties()
    {
        $value = $this->getValue();
        return [
            'decoration' => $value == 'hover' ? 'none' : 'underline',
            'decoration-hover' => $value == 'hover_off' ? 'none' : 'underline'
        ];
    }
}
