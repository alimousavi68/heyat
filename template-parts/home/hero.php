<?php
// template-parts/home/hero.php
$args = array(
    'post_type' => array('post', 'events', 'heyat_media'),
    'posts_per_page' => get_theme_mod('count_hero_section', 5),
    'meta_query' => array(
        array(
            'key' => '_heyat_is_in_slider',
            'value' => '1',
            'compare' => '='
        )
    )
);
$hero_query = new WP_Query($args);

if ($hero_query->have_posts()) :
?>
<section id="heroSliderContainer" class="reveal-on-scroll glass-hover-effect my-6 relative overflow-hidden rounded-large h-[520px] md:h-[450px] group press-effect border border-white/5 shadow-2xl">
    <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full float-particle-1 blur-[90px] bg-goldAccent/5 pointer-events-none z-10"></div>
    <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full float-particle-2 blur-[100px] bg-goldAccent/5 pointer-events-none z-10"></div>
    <div class="relative w-full h-full">
        <?php 
        $index = 0;
        while ($hero_query->have_posts()) : $hero_query->the_post(); 
            // Meta values
            $slider_cover = get_post_meta(get_the_ID(), '_heyat_slider_cover', true);
            $bg_url = $slider_cover ? esc_url($slider_cover) : get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$bg_url) $bg_url = get_template_directory_uri() . '/images/slider-0.webp'; // Fallback
            $slider_btn_text = get_post_meta(get_the_ID(), '_heyat_slider_btn_text', true);
            if (!$slider_btn_text) $slider_btn_text = 'مشاهده و بررسی';
            $active_class = ($index === 0) ? 'active' : '';
            
            // Generate some tags based on post type
            $post_type = get_post_type();
            $badge_text = 'مطلب ویژه';
            $badge_icon = 'fa-star';
            if ($post_type == 'heyat_media') {
                $badge_text = 'اثر رسانه‌ای';
                $badge_icon = 'fa-play';
            } elseif ($post_type == 'events') {
                $badge_text = 'مراسم پیش‌رو';
                $badge_icon = 'fa-calendar';
            }
        ?>
        <div class="hero-slide <?php echo $active_class; ?> absolute inset-0" data-slide="<?php echo $index; ?>">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo $bg_url; ?>');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-darkMain via-darkMain/35 to-transparent"></div>
            <div class="absolute inset-0 p-6 pb-14 md:p-10 flex flex-col justify-end items-start gap-4 md:gap-6 z-10">
                <span class="ios-glass text-goldAccent px-3.5 py-1.5 rounded-full text-[10px] md:text-xs font-bold border border-goldAccent/15 flex items-center gap-2 shadow-md">
                    <i class="fa-solid <?php echo $badge_icon; ?> text-[10px] md:text-xs"></i> <?php echo $badge_text; ?>
                </span>
                <h2 class="text-xl md:text-4xl font-bold text-white leading-snug drop-shadow-md">
                    <?php the_title(); ?>
                </h2>
                <?php if (has_excerpt()) : ?>
                <p class="text-xs md:text-base text-textMuted max-w-xl font-normal leading-relaxed">
                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                </p>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="mt-2 px-5 py-2.5 md:px-6 md:py-3 rounded-full bg-goldAccent text-darkMain font-bold text-xs md:text-sm flex items-center gap-2 hover:bg-white transition-all duration-300 shadow-[0_4px_16px_rgba(223,177,91,0.2)] press-effect">
                    <i class="fa-solid fa-link text-[10px] md:text-xs"></i>
                    <span><?php echo esc_html($slider_btn_text); ?></span>
                </a>
            </div>
        </div>
        <?php 
        $index++;
        endwhile; 
        ?>
    </div>
    
    <?php if ($hero_query->post_count > 1) : ?>
    <!-- Highly Premium Top-Left Corner Controls -->
    <div class="absolute top-6 left-6 z-30 flex gap-3">
        <button id="heroPrevBtn" class="ios-glass w-11 h-11 rounded-xl flex items-center justify-center text-white/80 hover:text-goldAccent border border-white/10 hover:border-goldAccent/30 transition-all press-effect">
            <i class="fa-solid fa-chevron-right text-sm"></i>
        </button>
        <button id="heroNextBtn" class="ios-glass w-11 h-11 rounded-xl flex items-center justify-center text-white/80 hover:text-goldAccent border border-white/10 hover:border-goldAccent/30 transition-all press-effect">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </button>
    </div>

    <!-- Slider Dots Control -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
        <?php for($i=0; $i<$hero_query->post_count; $i++) : ?>
            <span class="hero-dot <?php echo ($i==0)?'w-6 bg-goldAccent':'w-2 bg-white/30'; ?> h-2 rounded-full cursor-pointer transition-all duration-300" data-index="<?php echo $i; ?>"></span>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</section>
<?php
endif;
wp_reset_postdata();
?>
