<?php

namespace DioBrando\Design\Field;

use DioBrando\Design\Customize\CustomizeField;

class ContainerWidthField extends CustomizeField
{
    public function getProperties()
    {
        $properties = parent::getProperties();
        $multiplier = $this->Parent->getValueFromRoot('global__sizing__spacer-root');
        $layout_spacing = $this->Parent->getValueFromRoot('global__sizing__layout-spacing', true);
        $padding = $multiplier * $layout_spacing;
        $properties['container-max-width'] = $this->getValue(true) + $padding . 'px';
        return $properties;
    }
}
