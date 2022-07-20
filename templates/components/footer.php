<?php
use DevAnime\Estarossa\Modal\ModalView;
use DevAnime\Estarossa\SocialIcons\SocialIconsView;
use DevAnime\View\Element;
use DevAnime\View\Link;
use DevAnime\Util;
use DioBrando\GlobalOptions;
?>
<footer class="footer-nav" data-gtm="footer">
    <div class="<?= Util::componentClasses('footer-nav__sections', [get_theme_mod('footer__common__layout', 'vertical')]); ?>">
        <?php foreach (GlobalOptions::footerSections() as $index => $elements): ?>
            <?php
                $number = $index + 1;
                $design_base = sprintf('footer__section-%s__', $number);
                $section_classes = [
                    $number,
                    get_theme_mod($design_base . 'margin-bottom', 'spacing-default'),
                    'element-' . get_theme_mod($design_base . 'element-alignment', 'center'),
                    'element-' . get_theme_mod($design_base . 'element-direction', 'horizontal'),
                    'element-' . get_theme_mod($design_base . 'element-spacing', 'spacing-default')
                ];
            ?>
        <div class="<?= Util::componentClasses('footer-nav__section', $section_classes) ?>">
            <div class="footer-nav__section-inner">
            <?php foreach ($elements as $element): ?>
                <?php
                    $layout = $element['acf_fc_layout'];
                    $content = $element['content'];

                    $element_classes = [
                        sanitize_html_class($layout),
                        $layout == 'predefined' ? sanitize_html_class($content) : ''
                    ];
                ?>
                <div class="<?= Util::componentClasses('footer-nav__element', $element_classes) ?>">
                    <?php
                        switch ($layout) {
                            case 'predefined':
                                switch ($content) {
                                    case 'nav_menu':
                                        if (has_nav_menu('footer_navigation')) {
                                            wp_nav_menu(apply_filters('dio-brando/footer-nav-config', ['theme_location' => 'footer_navigation', 'menu_class' => 'nav list--inline']));
                                        };
                                        break;
                                    case 'social_icons':
                                        echo SocialIconsView::create(GlobalOptions::socialIcons());
                                        break;
                                    case 'contact_address':
                                        echo Element::create('address', GlobalOptions::contactAddress());
                                        break;
                                    default:
                                        echo GlobalOptions::get($content);
                                        break;
                                }
                                break;
                            case 'images':
                                foreach ($content as $row) {
                                    $image_output = WP_Image::get_by_attachment_id($row['image']);
                                    if (!empty($row['link']['url'])) {
                                        $link = Link::createFromField($row['link']);
                                        $image_output = $link->content($image_output);
                                    }
                                    echo $image_output;
                                }
                                break;
                            case 'block':
                                $content = get_post_field('post_content', $element['block']);
                                echo apply_filters('the_content', get_post_field('post_content', $element['block']));
                                break;
                            default:
                                echo $content;
                                break;
                        }
                    ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</footer>
<?php
$newsletter_form = do_shortcode(apply_filters('dio-brando/newsletter-shortcode', '[gravityform id="1" title=true description=true ajax=true]'));
if (!empty($newsletter_form)) {
    ModalView::load('newsletter','box', $newsletter_form);
}
echo ModalView::unloadAll();
