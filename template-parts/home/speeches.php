<?php
/**
 * Template part for displaying speeches
 */

$args = array(
    'post_type' => 'heyat_media',
    'posts_per_page' => get_theme_mod('count_speeches_section', 6),
    'tax_query' => array(
        array(
            'taxonomy' => 'media_category',
            'field'    => 'name',
            'terms'    => 'سخنرانی',
        ),
    ),
);
$speeches_query = new WP_Query($args);

if ($speeches_query->have_posts()) :
?>
<!-- 4. SPEECHES CAROUSEL -->
<section id="speechesSection" class="reveal-on-scroll my-10 relative">
    <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-goldAccent/10 text-goldAccent flex items-center justify-center text-xl border border-goldAccent/20">
                <?php heyat_render_section_icon('speeches_section', 'fa-book-open'); ?>
            </div>
            <div class="flex flex-col text-right">
                <h3 class="text-base md:text-lg font-bold text-white tracking-wide"><?php echo esc_html(get_theme_mod('title_speeches_section', 'سخنرانی‌های برگزیده')); ?></h3>
                <span class="hidden md:block text-[10px] text-textMuted font-medium mt-0.5"><?php echo esc_html(get_theme_mod('subtitle_speeches_section', 'مباحث معرفتی، فلسفی و اخلاقی اساتید بنام')); ?></span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="<?php echo get_post_type_archive_link('heyat_media'); ?>" class="text-xs md:text-sm font-medium text-goldAccent hover:text-white transition-colors duration-300 group press-effect">
                <span>بیشتر</span>
            </a>
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

    <div id="speechesCarousel" class="relative overflow-hidden py-6">
        <div class="carousel-track flex gap-4 transition-transform duration-500 ease-in-out">
            <?php 
            while ($speeches_query->have_posts()) : $speeches_query->the_post(); 
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/s-1.jpg';
                
                $files = get_post_meta(get_the_ID(), '_heyat_media_files', true);
                $duration = '--:--';
                if (!empty($files) && is_array($files)) {
                    $duration = !empty($files[0]['duration']) ? $files[0]['duration'] : $duration;
                }
                
                $is_video = has_term('video', 'media_format');
                
                $person_name = get_the_excerpt();
                if (empty($person_name)) $person_name = get_the_author();
                
                // Randomize icon slightly just for aesthetics (or map from tags if exists)
                $topic_icons = array('fa-book-open', 'fa-shield-heart', 'fa-users', 'fa-heart', 'fa-hands-praying', 'fa-landmark');
                $random_icon = $topic_icons[array_rand($topic_icons)];
            ?>
            <div onclick="window.location.href='<?php the_permalink(); ?>'"
                class="carousel-item flex-shrink-0 w-44 md:w-[22%] premium-card glass-hover-effect p-3 cursor-pointer transition-all press-effect relative group">
                <div class="relative rounded-small overflow-hidden aspect-square mb-3">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover" alt="<?php the_title_attribute(); ?>">
                    <div class="absolute top-2 right-2 z-10 flex gap-1">
                        <span class="ios-glass text-[9px] font-bold text-goldAccent px-2.5 py-0.5 rounded-full flex items-center gap-1 border border-goldAccent/20">
                            <i class="fa-solid <?php echo esc_attr($random_icon); ?> text-[9px]"></i> موضوعات
                        </span>
                    </div>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <?php if (!$is_video) : ?>
                            <!-- Direct Play Button -->
                            <button type="button" onclick="event.stopPropagation(); playAudio('<?php echo esc_js(get_the_title()); ?>', '<?php echo esc_js($person_name); ?>', '<?php echo esc_js($img_url); ?>', '<?php echo esc_js($duration); ?>')" class="w-9 h-9 rounded-full bg-goldAccent text-darkMain flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                <i class="fa-solid fa-play ml-0.5"></i>
                            </button>
                            <!-- Go to Single Page Button -->
                            <a href="<?php the_permalink(); ?>" onclick="event.stopPropagation();" class="w-9 h-9 rounded-full bg-white/20 text-white flex items-center justify-center shadow-lg hover:bg-white/40 hover:scale-110 transition-all border border-white/30" title="مشاهده پست">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        <?php else : ?>
                            <span class="w-10 h-10 rounded-full bg-goldAccent text-darkMain flex items-center justify-center shadow-lg hover:scale-110 transition-transform"><i class="fa-solid fa-play ml-0.5"></i></span>
                        <?php endif; ?>
                    </div>
                </div>
                <h4 class="text-sm font-bold text-white truncate"><?php the_title(); ?></h4>
                <span class="text-xs text-textMuted block mt-1 font-medium"><?php echo esc_html($person_name); ?></span>
                <div class="flex justify-between items-center mt-2.5 pt-2.5 border-t border-white/5 text-xs text-textMuted">
                    <span><i class="fa-solid fa-clock"></i> <?php echo esc_html($duration); ?></span>
                    <span class="text-goldAccent font-bold"><i class="fa-solid <?php echo esc_attr($random_icon); ?> text-xs"></i> مبحث</span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php 
endif; 
wp_reset_postdata(); 
?>
