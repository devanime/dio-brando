<?php

use DevAnime\Estarossa\Message\MessageView;
use DevAnime\Estarossa\NavMenu\NavMenuView;
use DioBrando\GlobalOptions;

$has_container = apply_filters('dio-brando/header-nav-container', false);

?>
<?php if (GlobalOptions::enableMessaging()): ?>
    <?= MessageView::createGlobal(); ?>
<?php endif; ?>
<header class="header-nav<?=$has_container ? ' header-nav--has-container' : '' ?> sticky-header sticky-header--top" data-gtm="Header">
    <?php if ($has_container): ?>
    <div class="header-nav__container">
    <?php endif; ?>
    <?php if (has_nav_menu('primary_navigation')): ?>
    <div class="header-nav__menu">
        <?= new NavMenuView(apply_filters('dio-brando/header-nav-menu-properties', [
            'menu_name' => 'primary_navigation',
            'menu_options' => ['theme_location' => 'primary_navigation'],
            'config' => []
        ])); ?>
    </div>
    <?php endif; ?>
    <div class="header-nav__brand">
        <a class="header-nav__brand-link" href="<?= home_url() ?>">
            <?php if ($header_image = apply_filters('dio-brando/header-image', GlobalOptions::headerBrandImage())): ?>
            <?= $header_image instanceof WP_Image ? $header_image->css_class('header-nav__brand-image') : $header_image; ?>
            <?php else: ?>
            <strong><?php bloginfo('name'); ?></strong>
            <?php endif; ?>
        </a>
    </div>
    <?php if ($header_cta_link = GlobalOptions::headerCTALink()) :
        $classes = ['header-nav__cta-link', get_theme_mod('header__cta__link-style', 'button')];
        ?>
        <div class="header-nav__cta">
            <?= $header_cta_link->class($classes); ?>
        </div>
    <?php endif; ?>
    <?php if ($has_container): ?>
    </div>
    <?php endif; ?>
</header>
