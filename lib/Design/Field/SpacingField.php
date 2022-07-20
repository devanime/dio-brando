<?php

namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;

class SpacingField extends CustomizeField
{
    public function getProperties()
    {
        $key = $this->getKey();
        $default = $this->getValue(true) / SpacerRootField::BASE;
        return [
            "$key--default" => $default . 'rem',
            "$key--quarter" => $default / 4 . 'rem',
            "$key--half" => $default / 2 . 'rem',
            "$key--double" => $default * 2 . 'rem',
        ];
    }
}
