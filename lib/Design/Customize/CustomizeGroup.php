<?php

namespace DioBrando\Design\Customize;

/**
 * Class CustomizeGroup
 * @package DioBrando\Design\Customize
 */
class CustomizeGroup extends CustomizeItemCollection
{
    protected static $object_class_name = CustomizeItem::class;

    protected $value;

    public static function create(string $key, array $items = [])
    {
        $group = new static($items);
        $group->key = $key;
        return $group;
    }

    public static function createSection(string $key, array $items = [], array $section_args = []) {
        $group = static::create($key, $items);
        $title = $section_args['title'] ?? ucwords(str_replace(['_', '-'], ' ', $key));
        $group->args = array_merge($group->args, compact('title') , $section_args);
        return $group;
    }

    public function getProperties()
    {
        return array_reduce($this->items, function($properties, CustomizeItem $item) {
            $item_properties = $item->getProperties();
            if ($this->Parent && !$this->Parent->isRoot()) {
                $item_properties = array_combine(array_map(function ($key) {
                    return $this->getId($key);
                }, array_keys($item_properties)), array_values($item_properties));
            }
            return array_merge($properties, $item_properties);
        }, []);
    }

    public function getIds(): array
    {
        $ids = [];
        foreach ($this->items as $item) { /* @var CustomizeItem $item */
            $ids = array_merge($ids, $item->getIds());
        }
        return $ids;
    }

    /**
     * @param string $id
     * @return CustomizeItem|false
     */
    public function get(string $id) {
        $id_parts = explode('__', $id);
        $match = $this;
        while($match instanceof static && count($id_parts)) {
            $key = array_shift($id_parts);
            $match = $match->find($key);
        }
        return $match;
    }

    public function getId($append = false)
    {
        $id = $this->Parent && $this->Parent->getId() ? $this->Parent->getId($this->key) : $this->key;
        if ($append) {
            $id .= '__' . $append;
        }
        return $id;
    }

    public function getLabel()
    {
        return $this->args['title'];
    }

    public function getValue($unitless = false)
    {
        $values = [];
        foreach ($this->items as $item) { /* @var CustomizeItem $item */
            $value[$item->getKey()] = $item->getValue($unitless);
        }
        return $values;
    }

    public function register(\WP_Customize_Manager $wp_customize)
    {
        if (!$this->isRoot()) {
            $args = $this->args;
            if (!$this->Parent->isRoot()) {
                $args['panel'] = $this->Parent->getSectionId();
                $wp_customize->add_section($this->getId(), $args);
            } elseif (!(defined('DISABLE_CUSTOMIZER') && DISABLE_CUSTOMIZER)) {
                $wp_customize->add_panel($this->getId(), $args);
            }
        }
        parent::register($wp_customize);
    }

}
