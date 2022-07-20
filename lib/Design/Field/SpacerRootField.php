<?php

namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;

class SpacerRootField extends CustomizeField
{
    const BASE = 10;

    public function getProperties()
    {
        $key = $this->getKey();
        $max = self::BASE * floatval($this->getValue());
        $properties = [
            "$key--default" => static::BASE . 'px',
            "$key--max" => $max . 'px',
        ];

        $properties["$key--diff"] = $max - static::BASE;
        return $properties;
    }
}
