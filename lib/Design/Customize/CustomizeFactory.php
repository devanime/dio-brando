<?php

namespace DioBrando\Design\Customize;

use DioBrando\Design\Choices\RangeChoices;
use DioBrando\Design\Field\BorderField;
use DioBrando\Design\Field\ColorPickerField;
use DioBrando\Design\Field\ColorSelectionField;
use DioBrando\Design\Field\ContainerWidthField;
use DioBrando\Design\Field\FluidValueField;
use DioBrando\Design\Field\HeadingSizeField;
use DioBrando\Design\Field\MarkupField;
use DioBrando\Design\Field\SpacerRootField;
use DioBrando\Design\Field\SpacingField;
use DioBrando\Design\Field\UnderlineBehaviorField;

class CustomizeFactory
{
    const DEFAULT_SECTION_KEY = 'common';
    const DEFAULT_SECTION_TITLE = 'Common';

    protected static $field_class_map = [
        'default' => CustomizeField::class,
        'spacer-root' => SpacerRootField::class,
        'spacing' => SpacingField::class,
        'container-width' => ContainerWidthField::class,
        'color' => ColorPickerField::class,
        'color-selection' => ColorSelectionField::class,
        'underline-behavior' => UnderlineBehaviorField::class,
        'markup' => MarkupField::class,
        'fluid-value' => FluidValueField::class,
        'border' => BorderField::class,
        'heading-size' => HeadingSizeField::class
    ];

    protected static $choices_factory_map = [
        'default' => CustomizeChoices::class,
        'range' => RangeChoices::class,
        'pixel-range' => [RangeChoices::class, 'pixels'],
        'em-range' => [RangeChoices::class, 'ems'],
    ];

    public function create(array $config)
    {
        $fields_by_section = [];
        $groups = [];
        $use_default_section = false;
        foreach ($config['fields'] as $key => $field_config) {
            $field_key = $field_config['name'] ?? $key;
            if (empty($field_config['section'])) {
                $field_config['section'] = [static::DEFAULT_SECTION_KEY];
                $use_default_section = true;
            }
            foreach ((array) $field_config['section'] as $index => $field_section) {
                $field_config_for_section = $field_config;
                if (!empty($field_config_for_section['default']) && is_array($field_config_for_section['default']) && !empty($field_config_for_section['default'][$index])) {
                    $field_config_for_section['default'] = $field_config_for_section['default'][$index];
                }
                $fields_by_section[$field_section][] = $this->createField($field_key, $field_config_for_section);

            }
        }
        if (empty($config['sections'][static::DEFAULT_SECTION_KEY]) && $use_default_section) {
            $config['sections'] = array_merge(
                [static::DEFAULT_SECTION_KEY => static::DEFAULT_SECTION_TITLE],
                $config['sections']
            );
        }
        foreach ($config['sections'] as $key => $title) {
            if (!empty($fields_by_section[$key])) {
                $groups[] = CustomizeGroup::createSection($key, $fields_by_section[$key], compact('title'));
            }
        }
        return CustomizeGroup::createSection($config['name'], $groups, ['title' => $config['title']]);
    }

    protected function createField(string $key, array $field_config)
    {
        $class_map = apply_filters('meliodas/design-producers/field-type-classes', static::$field_class_map);
        $field_class = $class_map[$field_config['type'] ?? 'default'] ?? $class_map['default'];
        if (!empty($field_config['choices'])) {
            $choices = $this->createChoices($field_config['choices']);
            if (!empty($field_config['choices']['inherit'])) {
                $choices->prepend([
                    'inherit' => '[Inherit]'
                ]);
            }
            $field_config['choices'] = $choices;
        }
        return new $field_class($key, $field_config);
    }

    protected function createChoices(array $choices_config): CustomizeChoices
    {
        $choices_factory_map = apply_filters('meliodas/design-producers/choices-factory',  static::$choices_factory_map);
        $factory_method = $choices_factory_map['default'];
        $factory_args = [$choices_config];
        foreach (array_keys($choices_config) as $key) {
            if (isset($choices_factory_map[$key])) {
                $factory_method = $choices_factory_map[$key];
                $factory_args = $choices_config[$key];
            }
        }
        return is_callable($factory_method) ?
            call_user_func_array($factory_method, $factory_args):
            new $factory_method(...$factory_args);
    }
}
