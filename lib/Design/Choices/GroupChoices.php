<?php


namespace DioBrando\Design\Choices;


use DioBrando\Design\Customize\CustomizeChoices;
use DioBrando\Design\Customize\CustomizeGroup;

class GroupChoices extends CustomizeChoices
{
    protected $group;

    public function __construct(CustomizeGroup $group, array $static_items = [])
    {
        $this->group = $group;
        parent::__construct($static_items);
    }

    public function getChoices(): array
    {
        $group_choices = array_combine(
            $this->group->mapMethod('getId'),
            $this->group->mapMethod('getLabel')
        );
        return array_merge($this->choices, $group_choices);
    }


}
