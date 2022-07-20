<?php
use DevAnime\Util\TemplateWrapper;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
if (apply_filters('show_site_header', true)){
    do_action('get_header');
    get_template_part('templates/components/header');
}
?>
<div class="wrap" role="document">
    <main class="main">
        <?php TemplateWrapper::include(); ?>
    </main><!-- /.main -->
</div><!-- /.wrap -->
<?php
if (apply_filters('show_site_footer', true)){
    do_action('get_footer');
    get_template_part('templates/components/footer');
}
wp_footer();
?>
</body>
</html>
