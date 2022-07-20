<?php

namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;

class ColorPickerField extends CustomizeField
{
    public function getProperties()
    {
        $hex_value = str_replace('#', '', $this->getValue());
        if (strlen($hex_value) == 3) {
            $hex_value = $hex_value . $hex_value;
        }
        $hex_value_parts = str_split($hex_value, 2);
        $rgb_parts = array_map('hexdec', $hex_value_parts);
        return [$this->getKey() => implode(',', $rgb_parts)];
    }
}
