<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); global $post; ?>
<article <?php post_class(); ?>>
    <?php if (is_singular()): ?>
        <?php if (preg_match( '/vc_row/', $post->post_content )): ?>
            <?php the_content(); ?>
        <?php else: ?>
        <div class="container">
            <header><h1 class="heading--xlarge"><?php the_title() ?></h1></header>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </div>
        <?php endif; ?>
    <?php else: ?>
    <header><h2 class="heading--medium"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2></header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>
    <?php endif; ?>
</article>
    <?php endwhile; ?>
<?php else: ?>
<div class="container">
    <div class="alert alert-warning">
    <?php _e(is_404() ?
        'Sorry, but the page you were trying to view does not exist.' :
        'Sorry, no results were found.', 'devanime');
    ?>
    </div>
</div>
<?php endif; ?>
<?php the_posts_navigation(); ?>
