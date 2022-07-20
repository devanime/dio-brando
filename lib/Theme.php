<?php

namespace DioBrando;

use DevAnime\Html\EditorStyles;
use DevAnime\Estarossa\SocialIcons\SocialIconsView;
use DevAnime\Theme as ThemeBase;

/**
 * Class Theme
 *
 * Configure settings by overriding parent class constants
 *
 * @package Theme
 */
class Theme extends ThemeBase
{
    const INCLUDE_ADMIN_EDITOR = false;
    const PLATFORM_THEME_SUPPORT = [
        'estarossa/nav-menu',
        'talent-producer',
        'faq-producer',
        'message-producer',
        'video-producer',
        'media-gallery-producer',
        'estarossa/tag-manager'
    ];

    protected $EditorStyles;
    protected $Customizer;

    /**
     * Add theme-specific hooks
     */
    public function __construct()
    {
        $this->Customizer = new DesignCustomizer();
        parent::__construct();


        $this->EditorStyles = new EditorStyles();
        $this->EditorStyles
            ->addInline('Default Heading Style', 'heading', 'strong')
            ->addInline('Heading Medium', 'heading heading--medium', 'strong')
            ->addInline('Heading Large', 'heading heading--large', 'strong')
            ->addInline('Heading Extra Large', 'heading heading--xlarge', 'strong')

            ->addInline('Emphasis Primary', 'emphasis emphasis--primary', 'span')
            ->addInline('Emphasis Secondary', 'emphasis emphasis--secondary', 'span')

            ->addInline('Text Small', 'text--small', 'span')
            ->addInline('Text Large', 'text--large', 'span')

            ->addSelector('Unstyled List (no bullets)', 'list--unstyled', 'ul')
            ->addSelector('Inline List (horizontal)', 'list--inline', 'ul')

            ->addSelector('Default Button', 'button', 'a')
            ->addSelector('Secondary Button', 'button button--secondary', 'a')
            ->addSelector('Inverted Button', 'button button--inverted', 'a');

        add_filter('estarossa/accordion/icon-properties', function($icon){
            $icon['icon_name'] = 'arrow';
            return $icon;
        });
        add_shortcode('social-icons', function () {
            return SocialIconsView::create(GlobalOptions::socialIcons());
        });
    }

    /**
     * Fires on after_setup_theme
     */
    public function setup()
    {
        parent::setup();
    }

    public function config() {
        parent::config();
        $this->Customizer->build();
    }

    /**
     * Add theme-specific style and script enqueues
     */
    public function assets()
    {
        parent::assets();
    }

    protected function getConfigPaths()
    {
        $config_paths = parent::getConfigPaths();
        $config_paths['design_files'] = 'design-json';
        return $config_paths;
    }

}
