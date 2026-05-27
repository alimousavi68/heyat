<?php
/**
 * The template for displaying archive pages
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Page Header (Premium Glassmorphism Style) -->
    <header class="relative overflow-hidden rounded-large h-48 md:h-64 mb-10 flex flex-col justify-center items-center text-center premium-card border border-white/5 shadow-2xl">
        <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full blur-[90px] bg-goldAccent/10 pointer-events-none z-10"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full blur-[100px] bg-purple-600/10 pointer-events-none z-10"></div>
        
        <div class="relative z-20">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-4 mx-auto w-max">
                <i class="fa-solid fa-folder-open text-xs"></i> آرشیو مطالب
            </span>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
                <?php 
                if (is_category()) {
                    single_cat_title();
                } elseif (is_tag()) {
                    single_tag_title();
                } elseif (is_author()) {
                    the_author();
                } else {
                    echo 'اخبار و اطلاع‌رسانی‌ها';
                }
                ?>
            </h1>
            <?php if (get_the_archive_description()) : ?>
                <div class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mt-2">
                    <?php echo wp_strip_all_tags(get_the_archive_description()); ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 archive-grid">
            <?php 
            while (have_posts()) : the_post(); 
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/b-1.webp';
                
                $categories = get_the_category();
                $cat_name = !empty($categories) ? $categories[0]->name : 'بدون دسته';
                
                $time_diff = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' پیش';
                
                $content = get_post_field('post_content', get_the_ID());
                $word_count = count(explode(' ', strip_tags($content)));
                $read_time = max(1, ceil($word_count / 200));
            ?>
            <div class="premium-card glass-hover-effect p-4 md:p-5 flex gap-4 md:gap-5 items-center cursor-pointer hover:border-goldAccent/30 transition-all press-effect group" onclick="window.location.href='<?php the_permalink(); ?>';">
                <div class="w-28 h-28 md:w-32 md:h-32 rounded-small overflow-hidden flex-shrink-0 border border-white/5 relative">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?php the_title_attribute(); ?>">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                </div>
                <div class="flex flex-col justify-between py-1 h-full text-right w-full">
                    <div>
                        <span class="bg-goldAccent/10 text-goldAccent px-2.5 py-1 rounded-full text-xs font-bold border border-goldAccent/20 inline-block mb-2"><?php echo esc_html($cat_name); ?></span>
                        <h2 class="text-sm md:text-base font-bold text-white line-clamp-2 leading-relaxed group-hover:text-goldAccent transition-colors"><?php the_title(); ?></h2>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-textMuted mt-3">
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-clock text-goldAccent/70"></i> <?php echo $read_time; ?> دقیقه مطالعه</span>
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar-alt text-goldAccent/70"></i> <?php echo esc_html($time_diff); ?></span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Custom Tailwind Pagination & Infinite Scroll -->
        <?php 
        $pagination = paginate_links(array(
            'prev_text' => '<i class="fa-solid fa-arrow-right"></i>',
            'next_text' => '<i class="fa-solid fa-arrow-left"></i>',
            'type'      => 'array'
        ));
        
        if ($pagination) {
            echo '<div class="pagination-container hidden" dir="ltr">';
            foreach ($pagination as $page) {
                $is_current = strpos($page, 'current') !== false;
                $is_next = strpos($page, 'next') !== false;
                
                $base_classes = 'ios-glass w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold transition-all';
                if ($is_next) $base_classes .= ' next-page-btn';
                
                if ($is_current) {
                    $classes = $base_classes . ' bg-goldAccent/20 border-goldAccent text-goldAccent pointer-events-none';
                } else {
                    $classes = $base_classes . ' text-white hover:text-darkMain hover:bg-goldAccent border-white/10';
                }
                
                $page = preg_replace('/class="[^"]*"/', 'class="' . $classes . '"', $page);
                echo $page;
            }
            echo '</div>';

            // Infinite Scroll Loading Trigger
            echo '<div class="infinite-scroll-trigger flex justify-center items-center mt-12 mb-8 py-8 w-full">';
            echo '<i class="fa-solid fa-circle-notch fa-spin text-4xl text-goldAccent opacity-0 transition-opacity duration-300"></i>';
            echo '</div>';
        }
        ?>

    <?php else : ?>
        <div class="premium-card p-12 text-center text-textMuted">
            <i class="fa-solid fa-box-open text-4xl mb-4 opacity-50"></i>
            <p class="text-lg">هیچ مطلبی یافت نشد.</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
