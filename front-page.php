<?php
get_header();
?>
<div class="h-20"></div>
<main class="max-w-6xl mx-auto px-4 w-full">

    <?php if ( get_theme_mod( 'show_hero_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/hero'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_speakers_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/speakers'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_events_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/events'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_campaigns_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/campaigns'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_media_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/media'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_speeches_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/speeches'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_gallery_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/gallery'); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_news_section', true ) ) : ?>
        <?php get_template_part('template-parts/home/news'); ?>
    <?php endif; ?>

        <!-- MINIMALIST FOOTER -->
        
<?php
get_footer();
?>
