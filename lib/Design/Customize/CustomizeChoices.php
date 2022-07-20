<?php


namespace DioBrando\Design\Customize;


class CustomizeChoices
{
    protected $choices = [];

    public function __construct(array $choices = [])
    {
        $this->choices = $choices;
    }

    public function getChoices(): array
    {
        return $this->choices;
    }

    public function prepend($choices)
    {
        $this->choices = $choices + $this->choices;
        return $this;
    }

    public function append($choices)
    {
        $this->choices = $this->choices + $choices;
        return $this;
    }

}
