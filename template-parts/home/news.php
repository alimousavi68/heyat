<?php
/**
 * Template part for displaying latest news/posts
 */

$args = array(
    'post_type' => 'post',
    'posts_per_page' => get_theme_mod('count_news_section', 3),
);
$news_query = new WP_Query($args);

if ($news_query->have_posts()) :
?>
<!-- NEWS & ARTICLES SECTION -->
<section id="news" class="reveal-on-scroll my-10">
    <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-goldAccent/10 text-goldAccent flex items-center justify-center text-xl border border-goldAccent/20">
                <?php heyat_render_section_icon('news_section', 'fa-newspaper'); ?>
            </div>
            <div class="flex flex-col text-right">
                <h3 class="text-base md:text-lg font-bold text-white tracking-wide"><?php echo esc_html(get_theme_mod('title_news_section', 'اخبار و اطلاعیه‌ها')); ?></h3>
                <span class="hidden md:block text-[10px] text-textMuted font-medium mt-0.5"><?php echo esc_html(get_theme_mod('subtitle_news_section', 'مطالب علمی، گزارش کارها و بیانیه‌های رسمی هیئت')); ?></span>
            </div>
        </div>
        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="text-xs md:text-sm font-medium text-goldAccent hover:text-white transition-colors duration-300 group press-effect flex items-center gap-1">
            <span>بیشتر</span>
            <i class="fa-solid fa-arrow-left text-xs transition-transform duration-300 group-hover:-translate-x-1"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php 
        $i = 0;
        while ($news_query->have_posts()) : $news_query->the_post(); 
            $i++;
            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            if (!$img_url) $img_url = get_template_directory_uri() . '/images/b-' . (($i%4)+1) . '.webp';
            
            // Category
            $categories = get_the_category();
            $cat_name = !empty($categories) ? $categories[0]->name : 'اخبار';
            
            // Date (human readable)
            $time_diff = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' پیش';
            
            // Read time estimation
            $content = get_post_field('post_content', get_the_ID());
            $word_count = count(explode(' ', strip_tags($content)));
            $read_time = max(1, ceil($word_count / 200));
        ?>
        <div class="premium-card glass-hover-effect p-4 flex gap-4 items-center cursor-pointer hover:border-goldAccent/20 transition-all press-effect" onclick="window.location.href='<?php the_permalink(); ?>';">
            <div class="w-24 h-24 rounded-small overflow-hidden flex-shrink-0 border border-white/5">
                <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover" alt="<?php the_title_attribute(); ?>">
            </div>
            <div class="flex flex-col justify-between py-1 h-full text-right w-full">
                <div>
                    <span class="bg-goldAccent/10 text-goldAccent px-2.5 py-1 rounded-full text-xs font-bold border border-goldAccent/15"><?php echo esc_html($cat_name); ?></span>
                    <h4 class="text-sm font-bold text-white mt-2 line-clamp-2"><?php the_title(); ?></h4>
                </div>
                <div class="flex gap-3 text-xs text-textMuted mt-2.5">
                    <span><i class="fa-regular fa-clock"></i> <?php echo $read_time; ?> دقیقه زمان مطالعه</span>
                    <span>• <?php echo esc_html($time_diff); ?></span>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>
<?php 
endif; 
wp_reset_postdata(); 
?>
