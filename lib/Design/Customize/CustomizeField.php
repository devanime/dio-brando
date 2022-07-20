<?php

namespace DioBrando\Design\Customize;

use WP_Customize_Manager;

/**
 * Class CustomizeField
 * @package DioBrando\Design\Customize
 */
class CustomizeField implements CustomizeItem
{
    const CONTROL_CLASSES = [
        'color' => \WP_Customize_Color_Control::class,
        'image' => \WP_Customize_Image_Control::class,
    ];
    const DEFAULT_CONTROL_TYPE = 'select';
    const CHOICES_TYPES = [
        self::DEFAULT_CONTROL_TYPE,
        'radio'
    ];

    protected $key;
    protected $label;
    protected $args = [
        'type' => self::DEFAULT_CONTROL_TYPE,
        'choices' => [],
        'transport' => 'postMessage'
    ];

    protected $value;
    protected $properties;

    /**
     * @var CustomizeItemCollection
     */
    protected $Parent;

    public function __construct(string $key, array $args = [])
    {
        $this->key = $key;
        if (isset($args['control-type'])) {
            $args['type'] = $args['control-type'];
        }
        $this->label = $label = $args['label'] ?? static::createLabel($key);
        if (!empty($args['choices'])) {
            if (empty($args['type']) || !in_array($args['type'], static::CHOICES_TYPES)) {
                $args['type'] = static::DEFAULT_CONTROL_TYPE;
            }
        }
        $this->args = array_merge($this->args, compact('label'), $args);
    }

    public function init()
    {
        if ($this->args['choices'] instanceof CustomizeChoices) {
            $this->args['choices'] = $this->args['choices']->getChoices();
        }
        if (is_array($this->args['choices']) && !isset($this->args['default'])) {
            $this->args['default'] = key($this->args['choices']);
        }
    }

    /**
     * @return CustomizeItemCollection
     */
    public function getParent(): CustomizeItemCollection
    {
        return $this->Parent;
    }

    /**
     * @param CustomizeItemCollection $Parent
     */
    public function setParent(CustomizeItemCollection $Parent)
    {
        $this->Parent = $Parent;
    }

    public function getId($modify = false)
    {
        return $this->Parent->getId($this->key);
    }

    public function getIds(): array
    {
        return [$this->getId()];
    }

    public function getSectionId()
    {
        return $this->Parent->getSectionId();
    }


    public function getKey()
    {
        return $this->key;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getValue($unitless = false)
    {
        if (!isset($this->value)) {
            $this->value = get_theme_mod($this->getId(), $this->args['default']);
        }
        return $unitless ? intval($this->value) : $this->value;
    }

    /**
     * @return CustomizeItemCollection
     */
    public function getRoot()
    {
        $root = $this;
        while($parent = $root->getParent()) {
            $root = $parent;
        }
        return $root;
    }

    public function getValueFromRoot($id, $unitless = false)
    {
        $value = null;
        try {
            $value = $this->getRoot()->get($id)->getValue($unitless);
        } catch (\Throwable $e) {
            error_log('Could not find value from root using id: ' . var_export($id, true));
        }
        return $value;
    }

    public function getProperties()
    {
        return [$this->getKey() => $this->getValue()];
    }

    public function register(\WP_Customize_Manager $wp_customize)
    {
        $setting_args = $control_args = [];
        foreach ($this->args as $key => $value) {
            if (property_exists(\WP_Customize_Control::class, $key)) {
                $control_args[$key] = $value;
            } elseif (property_exists(\WP_Customize_Setting::class, $key)) {
                $setting_args[$key] = $value;
            }
        }
        $control_args['section'] = $this->getSectionId();

        $this->registerSettings($wp_customize, $setting_args);

        $this->registerControls($wp_customize, $control_args);
    }

    protected function registerSettings(WP_Customize_Manager $wp_customize, $setting_args)
    {
        $wp_customize->add_setting($this->getId(), $setting_args);
    }

    protected function registerControls(WP_Customize_Manager $wp_customize, $control_args)
    {
        $control_class = static::CONTROL_CLASSES[$control_args['type']] ?? \WP_Customize_Control::class;
        $wp_customize->add_control(new $control_class($wp_customize, $this->getId(), $control_args));
    }

    public static function createLabel($key)
    {
        return ucwords(str_replace(['_', '-'], ' ', $key));
    }

}
