<?php


namespace DioBrando\Design\Choices;

use DioBrando\Design\Customize\CustomizeChoices;

class RangeChoices extends CustomizeChoices
{
    protected $format = '%s';

    public function __construct($start, $end, $step = 1, $format = '%s', $format_labels_only = false)
    {
        $values = range($start, $end, $step);
        $this->format = $format;
        $labels = $this->format($values);
        if (!$format_labels_only) {
            $values = $this->format($values);
        }
        parent::__construct(array_combine($values, $labels));
    }

    public static function pixels($start, $end, $step = 1)
    {
        return new static($start, $end, $step, '%spx');
    }

    public static function ems($start, $end, $step = 1)
    {
        return new static($start, $end, $step, '%sem');
    }

    protected function format($array)
    {
        return array_map(function($value) {
            return sprintf($this->format, $value);
        }, $array);
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
