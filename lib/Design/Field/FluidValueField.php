<?php


namespace DioBrando\Design\Field;


use DioBrando\Design\Customize\CustomizeField;

class FluidValueField extends ParentField
{
    protected function getChildFields(array $args = []): array
    {
        return [
            new CustomizeField('default', $this->appendLabel(array_merge([
                //
            ],$args), 'Default')),
            new CustomizeField('max', $this->appendLabel(array_merge([
                //
            ], $args), 'Max'))
        ];
    }

    public function getProperties()
    {
        $key = $this->getKey();
        $values = $this->getValue();
        $default = $values['default'] ?? $this->args['default'];
        $max = $values['max'] ?? $this->args['default'];
        $properties = parent::getProperties();
        $default_unitless = preg_replace('/[^0-9.]/', '', $default);
        $max_unitless = preg_replace('/[^0-9.]/', '', $max);
        $default_real_value = is_numeric($default_unitless) ? $default_unitless + 0:
            $this->getValueFromRoot($default, true);
        $max_real_value = is_numeric($max_unitless) ? $max_unitless + 0:
            $this->getValueFromRoot($max, true);
        $properties["$key--diff"] = $max_real_value - $default_real_value;
        return $properties;
    }
}
