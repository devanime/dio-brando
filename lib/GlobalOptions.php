<?php

namespace DioBrando;

use DevAnime\Models\OptionsBase;
use DevAnime\Estarossa\SocialIcons\SocialIcon;
use DevAnime\View\Link;
use WP_Image;

/**
 * Class GlobalOptions
 * @package DioBrando\Options
 *
 * @method static WP_Image headerBrandImage()
 * @method static Link headerCTALink()
 * @method static footerSections()
 * @method static socialIcons()
 * @method static contactAddress()
 * @method static enableMessaging()
 */
class GlobalOptions extends OptionsBase
{
    protected $default_values = [
        'header__brand_image' => null,
        'header__cta_link' => null,
        'footer_sections' => [],
        'social_icons' => [],
        'contact_address' => ''
    ];

    protected function getHeaderBrandImage()
    {
        $header_image = $this->get('header__brand_image');
        return WP_Image::get_by_attachment_id($header_image);
    }

    protected function getHeaderCTALink()
    {
        $header_cta_link = $this->get('header__cta_link');
        return Link::createFromField($header_cta_link);
    }

    protected function getFooterSections()
    {
        return array_column($this->get('footer_sections'), 'section');
    }

    protected function getSocialIcons()
    {
        return array_map(function($row) {
            $label = isset($row['link']['title']) ? $row['link']['title'] : ucfirst($row['icon']);
            return new SocialIcon($row['icon'], $row['link']['url'] ?: '', $label, []);
        }, $this->get('social_icons'));
    }

}
