<?php

namespace DioBrando;

use DioBrando\Design\Customize\CustomizeGroup;
use DioBrando\Design\Customize\CustomizeFactory;


class DesignCustomizer
{

    const COMPONENT = 'design';

    protected $sep;

    /**
     * @var CustomizeGroup
     */
    protected $DesignSystem;

    public function __construct()
    {

        add_filter('meliodas/config_registration_types', function ($types) {
            $types['design_files'] = 'dio-brando/design_config_files';
            return $types;
        });
        add_action('customize_register', [$this, 'register']);
        add_action('customize_register', [$this, 'cleanupCustomize']);
        add_action('wp_head', [$this, 'renderCSSVarsStyles'], 1);
        add_filter('body_class', function ($classes) {
            if (is_customize_preview()) {
                $classes[] = 'customize-preview';
            }
            return $classes;
        });
        add_filter('tiny_mce_before_init', function($init) {
            $init['content_style'] = $this->renderCSSVars(true);
            return $init;
        });
        $this->sep = defined('ENVIRONMENT') && ENVIRONMENT !== 'local' ? '' : PHP_EOL;
    }

    public function renderCSSVarsStyles()
    {
        printf('<style id="design-system-properties">%1$s%2$s%1$s</style>', $this->sep, $this->renderCSSVars());
    }

    public function renderCSSVars($compact = false)
    {
        $sep = $compact ? '' : $this->sep;
        $vars = [];
        $properties = $this->DesignSystem->getProperties();
        foreach ($properties as $key => $value) {
            if (is_string($value) && isset($properties[$value])) {
                $value = sprintf('var(--%s)', $value);
            }
            $vars[] = sprintf('--%s: %s;', $key, $value);
        }
        return sprintf(':root {%1$s%2$s%1$s}', $sep, implode($sep, $vars));
    }

    public function register(\WP_Customize_Manager $wp_customize)
    {
        $this->DesignSystem->register($wp_customize);
        $wp_customize->selective_refresh->add_partial('design-system-properties', [
            'selector' => '#design-system-properties',
            'settings' => $this->DesignSystem->getIds(),
            'render_callback' => [$this, 'renderCSSVars'],
        ]);
    }

    public function build()
    {
        $config_files = array_filter(apply_filters('dio-brando/design_config_files', []));
        $groups = [];
        $Factory = new CustomizeFactory();
        foreach ($config_files as $config_file) {
            $config = json_decode(file_get_contents($config_file), true);
            $config = apply_filters('dio-brando/design_config', $config);

            if (!$config) {
                error_log('Error parsing Design Config JSON file: ' . $config_file);
                continue;
            }
            $groups[] = $Factory->create($config);
        }
        $this->DesignSystem = new CustomizeGroup($groups);
        $this->DesignSystem->init();
    }

    public function cleanupCustomize(\WP_Customize_Manager $wp_customize)
    {
        $wp_customize->remove_section('static_front_page');
        $wp_customize->remove_section('custom_css');
        $wp_customize->remove_panel('themes');
        $wp_customize->remove_control('blogname');
        $wp_customize->remove_control('blogdescription');
        $title_tagline_section = $wp_customize->get_section('title_tagline');
        $title_tagline_section->title = 'Site Icon';
        $title_tagline_section->priority = 999;
    }
}
