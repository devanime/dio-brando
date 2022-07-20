<?php


namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;
use DioBrando\Design\Customize\CustomizeItemCollection;

abstract class ParentField extends CustomizeItemCollection
{
    protected static $object_class_name = CustomizeField::class;

    protected $label;
    protected $args;

    public function __construct(string $key, array $args = [])
    {
        $this->key = $key;
        $this->args = $args;
        $this->label = $label = $args['label'] ?? CustomizeField::createLabel($key);
        parent::__construct($this->getChildFields($args));
    }

    /**
     * @param array $args
     * @return CustomizeField[]
     */
    protected abstract function getChildFields(array $args = []): array;

    public function getValue($unitless = false)
    {
        if (!isset($this->value)) {
            $this->value = get_theme_mod($this->getId(), []);
        }
        return $unitless ? $this->value + 0 : $this->value;
    }

    public function getLabel()
    {
        return $this->args['label'];
    }

    public function getSectionId()
    {
        return $this->Parent->getSectionId();
    }

    public function getId($append = false)
    {
        $id = $this->Parent->getId($this->key);
        if ($append) {
            $id .= "[$append]";
        }
        return $id;
    }

    public function getIds($modify = false): array
    {
        return $this->mapMethod('getId');
    }

    public function get(string $id)
    {
        return $this->find($id);
    }

    public function getProperties()
    {
        $merged_child_properties = call_user_func_array('array_merge', $this->mapMethod('getProperties'));
        $properties = array_merge($merged_child_properties, $this->getValue() ?: []);
        return array_combine(array_map(function ($key) {
                return "$this->key--$key";
        }, array_keys($properties)), array_values($properties));
    }

    protected function appendLabel(array $args, string $label)
    {
        $args['label'] = $this->label . ' - ' . $label;
        return $args;
    }

}