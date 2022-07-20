<?php


namespace DioBrando\Design\Field;


use DioBrando\Design\Customize\CustomizeField;
use DioBrando\Design\Choices\GroupChoices;
use WP_Customize_Manager;

class ColorSelectionField extends CustomizeField
{
    const REFERENCE_ID = 'global__colors';

    public function __construct(string $key, array $args = [])
    {
        $args['choices'] = array_merge([
            'inherit' => '[Inherit]',
            'transparent' => '[Transparent]'
        ], $args['choices'] ?? []);
        if (empty($args['default'])) {
            $args['default'] = 'inherit';
        } elseif (
            !in_array($args['default'], array_keys($args['choices'])) &&
            0 !== strpos($args['default'], 'global__')
        ) {
            $args['default'] = static::REFERENCE_ID . '__' . $args['default'];
        }
        parent::__construct($key, $args);
    }

    public function init()
    {
        if ($global_colors_group = $this->getParent()->getRoot()->get(static::REFERENCE_ID)) {
            $choices = new GroupChoices($global_colors_group);
            $parent_group = $this->getParent()->getParent();
            if ($parent_group && $parent_group->getId() != 'global') {
                $this->args['choices'] = array_merge($this->args['choices'], [
                    'global__body__color' => '[Body Color]',
                    'global__body__color-inverted' => '[Body Color Inverted]',
                    'global__links__color' => '[Links Color]',
                    'global__links__color-hover' => '[Links Color Hover]',
                ]);
            }
            $choices->prepend($this->args['choices']);
            $this->args['choices'] = $choices;
        }
        parent::init();
    }
}
