<?php


namespace DioBrando\Design\Field;


use DioBrando\Design\Choices\RangeChoices;
use DioBrando\Design\Customize\CustomizeField;

class BorderField extends ParentField
{
    protected function getChildFields(array $args = []): array
    {

        return [
            new CustomizeField('width', $this->appendLabel(array_merge($args, [
                'description' => 'Set the border width',
                'default' => $args['default']['width'] ?? '0px',
                'choices' => RangeChoices::pixels(0, 5, 1)
            ]), 'Width')),
            new ColorSelectionField('color', $this->appendLabel(array_merge($args, [
                'description' => 'Set the border color',
                'default' => $args['default']['color'] ?? 'inherit',
            ]), 'Color')),
            new CustomizeField('radius', $this->appendLabel(array_merge($args, [
                'description' => 'Corner curvature (1em = 1x font size)',
                'default' => $args['default']['radius'] ?? '0.25em',
                'choices' => RangeChoices::ems(0, 2, 0.25)
            ]), 'Radius'))

        ];
    }
}
