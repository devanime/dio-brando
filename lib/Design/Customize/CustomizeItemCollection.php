<?php

namespace DioBrando\Design\Customize;

use DevAnime\Models\ObjectCollection;

/**
 * Class CustomizeItemCollection
 * @package DioBrando\Design\Customize
 */
abstract class CustomizeItemCollection extends ObjectCollection implements CustomizeItem
{
    protected static $object_class_name = CustomizeItem::class;

    protected $key = '';
    protected $args = [];

    protected $value;

    /**
     * @var CustomizeItemCollection
     */
    protected $Parent;

    public function init()
    {
        $this->walkMethod('init');
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

    /**
     * @return CustomizeItemCollection
     */
    public function getParent()
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

    public function getSectionId()
    {
        return $this->getId();
    }

    /**
     * @param CustomizeItem $item
     * @return $this
     */
    protected function addItem($item)
    {
        parent::addItem($item);
        $item->setParent($this);
        return $this;
    }

    /**
     * @param string $id
     * @return CustomizeItem|false
     */
    abstract public function get(string $id);

    public function getKey()
    {
        return $this->key;
    }

    public function isRoot() {
        return !$this->Parent;
    }

    public function register(\WP_Customize_Manager $wp_customize)
    {
        $this->walkMethod('register', $wp_customize);
    }

    /**
     * @param CustomizeItem $item
     * @return string
     */
    protected function getObjectHash($item)
    {
        return md5($item->getKey());
    }

}
