<?php

namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;

class MarkupField extends CustomizeField
{

    public function __construct(string $key, array $args = [])
    {
        $args['transport'] = 'refresh';
        parent::__construct($key, $args);
    }

    public function getProperties()
    {
        return [];
    }
}

