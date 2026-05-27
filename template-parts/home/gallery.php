<?php
/**
 * Template part for displaying photo gallery
 */

$args = array(
    'post_type' => 'gallery',
    'posts_per_page' => get_theme_mod('count_gallery_section', 4),
);
$gallery_query = new WP_Query($args);

if ($gallery_query->have_posts()) :
?>
<!-- 4. PHOTO GALLERY CAROUSEL -->
<section id="gallerySection" class="reveal-on-scroll my-10 relative">
    <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-goldAccent/10 text-goldAccent flex items-center justify-center text-xl border border-goldAccent/20">
                <?php heyat_render_section_icon('gallery_section', 'fa-images'); ?>
            </div>
            <div class="flex flex-col text-right">
                <h3 class="text-base md:text-lg font-bold text-white tracking-wide"><?php echo esc_html(get_theme_mod('title_gallery_section', 'گزارش‌های تصویری')); ?></h3>
                <span class="hidden md:block text-[10px] text-textMuted font-medium mt-0.5"><?php echo esc_html(get_theme_mod('subtitle_gallery_section', 'مستندنگاری تصویری و ثبت جلوه‌های معنوی و هنری جلسات ثارالله')); ?></span>
            </div>
        </div>

        <a href="<?php echo get_post_type_archive_link('gallery'); ?>" class="text-xs md:text-sm font-medium text-goldAccent hover:text-white transition-colors duration-300 group press-effect">
            <span>بیشتر</span>
            <i class="fa-solid fa-arrow-left text-xs transition-transform duration-300 group-hover:-translate-x-1"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 py-2">
        <?php 
        $i = 0;
        while ($gallery_query->have_posts()) : $gallery_query->the_post(); 
            $i++;
            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if (!$img_url) $img_url = get_template_directory_uri() . '/images/g-' . (($i%3)+1) . '.jpg';
            
            // The third item typically takes full width on small screens
            $extra_class = ($i == 3) ? 'sm:col-span-2 md:col-span-1' : '';
        ?>
        <div class="premium-card glass-hover-effect relative rounded-large overflow-hidden aspect-[4/3] group cursor-pointer press-effect <?php echo $extra_class; ?>">
            <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-500" alt="<?php the_title_attribute(); ?>">
            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black via-black/85 to-transparent p-4 flex items-center justify-between text-right z-10">
                <span class="text-sm font-bold text-white truncate w-4/5"><?php the_title(); ?></span>
                <i class="fa-solid fa-camera text-goldAccent text-xs"></i>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>
<?php 
endif; 
wp_reset_postdata(); 
?>
