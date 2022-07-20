<?php


namespace DioBrando\Design\Customize;


interface CustomizeItem
{
    function init();

    function getId($modify = false);

    function getIds(): array;

    function getSectionId();

    function getKey();

    function getLabel();

    function getValue($unitless = false);

    function setParent(CustomizeItemCollection $item);

    function getParent();

    function getProperties();

    function register(\WP_Customize_Manager $wp_customize);
}
