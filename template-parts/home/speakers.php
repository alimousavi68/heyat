<?php
// template-parts/home/speakers.php
$args = array(
    'post_type' => 'persons',
    'posts_per_page' => get_theme_mod('count_speakers_section', -1),
    'orderby' => 'date',
    'order' => 'DESC'
);
$speakers_query = new WP_Query($args);

if ($speakers_query->have_posts()) :
?>
<section id="speakersSection" class="reveal-on-scroll my-12 relative">
    <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-goldAccent/10 text-goldAccent flex items-center justify-center text-xl border border-goldAccent/20">
                <?php heyat_render_section_icon('speakers_section', 'fa-microphone-lines'); ?>
            </div>
            <div class="flex flex-col text-right">
                <h3 class="text-base md:text-lg font-bold text-white tracking-wide"><?php echo esc_html(get_theme_mod('title_speakers_section', 'مادحین و سخنرانان')); ?></h3>
                <span class="hidden md:block text-[10px] text-textMuted font-medium mt-0.5"><?php echo esc_html(get_theme_mod('subtitle_speakers_section', 'ذاکرین و سخنرانان برجسته و میهمانان هیئت ثارالله')); ?></span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <button class="carousel-prev premium-carousel-btn">
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
                <button class="carousel-next premium-carousel-btn">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </button>
            </div>
        </div>
    </div>
    
    <div id="speakersCarousel" class="relative overflow-hidden py-6">
        <div class="carousel-track flex gap-1.5 transition-transform duration-500 ease-in-out">
            <?php 
            while ($speakers_query->have_posts()) : $speakers_query->the_post(); 
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/avatar-placeholder.webp'; 
                $role = get_post_meta(get_the_ID(), '_heyat_person_role', true);
                $role_text = ($role == 'speaker') ? 'سخنران' : 'مداح اهل‌بیت';
            ?>
            <a href="<?php the_permalink(); ?>" class="carousel-item flex-shrink-0 w-32 md:w-[15%] flex flex-col items-center gap-3 cursor-pointer press-effect group">
                <div class="w-28 h-40 md:w-32 md:h-48 rounded-full p-2.5 premium-card glass-hover-effect border border-white/10 group-hover:border-goldAccent/40 shadow-xl transition-all overflow-hidden flex-shrink-0 flex items-center justify-center relative">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full rounded-full object-cover brightness-90 group-hover:brightness-110 transition-all duration-500" alt="<?php the_title_attribute(); ?>">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-goldAccent/40 via-amber-600/20 to-rose-900/40 mix-blend-soft-light opacity-90 group-hover:opacity-50 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="absolute inset-0 rounded-full bg-gradient-to-t from-black/80 via-transparent to-goldAccent/10 opacity-100 group-hover:opacity-70 transition-opacity duration-500 pointer-events-none border border-goldAccent/20"></div>
                </div>
                <span class="text-xs md:text-sm font-medium text-white group-hover:text-goldAccent transition-colors whitespace-nowrap text-center">
                    <?php the_title(); ?>
                    <span class="block text-[10px] text-textMuted mt-1 font-normal"><?php echo esc_html($role_text); ?></span>
                </span>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php 
endif; 
wp_reset_postdata(); 
?>
