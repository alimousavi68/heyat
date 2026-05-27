<?php
/**
 * The template for displaying Media Archive (heyat_media)
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Page Header -->
    <header class="relative overflow-hidden rounded-large h-48 md:h-64 mb-8 flex flex-col justify-center items-center text-center premium-card border border-white/5 shadow-2xl">
        <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full blur-[90px] bg-goldAccent/10 pointer-events-none z-10"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full blur-[100px] bg-redAccent/10 pointer-events-none z-10"></div>
        
        <div class="relative z-20">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-4 mx-auto w-max">
                <i class="fa-solid fa-compact-disc text-xs"></i> آرشیو رسانه‌ها
            </span>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
                <?php 
                if (is_tax('media_category')) {
                    single_term_title();
                } else {
                    echo 'بانک جامع صوت و تصویر';
                }
                ?>
            </h1>
            <div class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mt-2">
                دسترسی به تمامی سخنرانی‌ها، مداحی‌ها و نماهنگ‌های هیئت عاشقان ثارالله
            </div>
        </div>
    </header>

    <?php 
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $m_format = isset($_GET['m_format']) ? sanitize_text_field($_GET['m_format']) : '';
    $m_cat    = isset($_GET['m_cat']) ? sanitize_text_field($_GET['m_cat']) : '';
    $m_year   = isset($_GET['m_year']) ? intval($_GET['m_year']) : '';
    $m_month  = isset($_GET['m_month']) ? intval($_GET['m_month']) : '';
    $search_q = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    $args = array(
        'post_type'      => 'heyat_media',
        'paged'          => $paged,
        'posts_per_page' => 16,
    );
    
    if ($search_q) {
        $args['s'] = $search_q;
    }

    $tax_queries = array('relation' => 'AND');
    if ($m_format) {
        $tax_queries[] = array('taxonomy' => 'media_format', 'field' => 'slug', 'terms' => $m_format);
    }
    if ($m_cat) {
        $tax_queries[] = array('taxonomy' => 'media_category', 'field' => 'slug', 'terms' => $m_cat);
    }
    if (count($tax_queries) > 1) {
        $args['tax_query'] = $tax_queries;
    }

    if ($m_year || $m_month) {
        $date_query = array();
        if ($m_year) $date_query['year'] = $m_year;
        if ($m_month) $date_query['month'] = $m_month;
        $args['date_query'] = array($date_query);
    }

    $media_query = new WP_Query($args);
    ?>

    <!-- Advanced Filters -->
    <form method="GET" action="<?php echo get_post_type_archive_link('heyat_media'); ?>" class="premium-card ios-glass p-4 md:p-6 mb-10 border border-white/10 shadow-lg rounded-2xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <?php if ($search_q): ?><input type="hidden" name="s" value="<?php echo esc_attr($search_q); ?>"><?php endif; ?>
        
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold text-goldAccent"><i class="fa-solid fa-layer-group"></i> دسته‌بندی</label>
            <select name="m_cat" class="w-full bg-black/40 border border-white/10 rounded-lg p-2.5 text-sm text-white focus:border-goldAccent outline-none appearance-none">
                <option value="">همه موضوعات</option>
                <?php 
                $terms = get_terms(array('taxonomy' => 'media_category', 'hide_empty' => true));
                if (!is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '" ' . selected($m_cat, $term->slug, false) . '>' . esc_html($term->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold text-goldAccent"><i class="fa-solid fa-photo-film"></i> نوع رسانه</label>
            <select name="m_format" class="w-full bg-black/40 border border-white/10 rounded-lg p-2.5 text-sm text-white focus:border-goldAccent outline-none appearance-none">
                <option value="">همه</option>
                <?php 
                $formats = get_terms(array('taxonomy' => 'media_format', 'hide_empty' => true));
                if (!is_wp_error($formats)) {
                    foreach ($formats as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '" ' . selected($m_format, $term->slug, false) . '>' . esc_html($term->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold text-goldAccent"><i class="fa-regular fa-calendar-days"></i> سال انتشار</label>
            <select name="m_year" class="w-full bg-black/40 border border-white/10 rounded-lg p-2.5 text-sm text-white focus:border-goldAccent outline-none appearance-none">
                <option value="">همه سال‌ها</option>
                <?php 
                $current_shamsi = function_exists('jdate') ? jdate('Y') : (date('Y') - 621);
                for ($y = $current_shamsi; $y >= $current_shamsi - 10; $y--) {
                    echo '<option value="' . esc_attr($y) . '" ' . selected($m_year, $y, false) . '>سال ' . esc_html($y) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold text-goldAccent"><i class="fa-solid fa-calendar-day"></i> ماه انتشار</label>
            <select name="m_month" class="w-full bg-black/40 border border-white/10 rounded-lg p-2.5 text-sm text-white focus:border-goldAccent outline-none appearance-none">
                <option value="">همه ماه‌ها</option>
                <?php 
                $shamsi_months = array(
                    1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
                    4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
                    7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
                    10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
                );
                foreach ($shamsi_months as $num => $name) {
                    echo '<option value="' . esc_attr($num) . '" ' . selected($m_month, $num, false) . '>' . esc_html($name) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div>
            <button type="submit" class="w-full press-effect bg-goldAccent text-darkMain rounded-lg p-2.5 text-sm font-bold shadow-lg hover:bg-white transition-colors">
                <i class="fa-solid fa-filter"></i> اعمال فیلتر
            </button>
        </div>
    </form>

    <?php if ($media_query->have_posts()) : ?>
        <!-- Grid layout: 2 cols on mobile, 3 on tablet, 4 on desktop -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 archive-grid">
            <?php 
            while ($media_query->have_posts()) : $media_query->the_post(); 
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/mad-1.webp';
                
                $is_video = has_term('video', 'media_format');
                $format_icon = $is_video ? 'fa-video' : 'fa-headphones';
                $format_text = $is_video ? 'ویدیویی' : 'صوتی';
                $play_icon = $is_video ? 'fa-circle-play' : 'fa-waveform';

                $files = get_post_meta(get_the_ID(), '_heyat_media_files', true);
                $duration = '--:--';
                if (!empty($files) && is_array($files)) {
                    $duration = !empty($files[0]['duration']) ? $files[0]['duration'] : $duration;
                }
                
                $person_name = get_the_excerpt();
                if (empty($person_name)) $person_name = get_the_author();
            ?>
            <div onclick="window.location.href='<?php the_permalink(); ?>';" class="premium-card glass-hover-effect p-3 cursor-pointer transition-all press-effect relative group block">
                <div class="relative rounded-small overflow-hidden aspect-square mb-3">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?php the_title_attribute(); ?>">
                    <div class="absolute top-2 right-2 z-10 flex gap-1">
                        <span class="ios-glass text-[9px] font-bold text-goldAccent px-2.5 py-0.5 rounded-full flex items-center gap-1 border border-goldAccent/20">
                            <i class="fa-solid <?php echo esc_attr($format_icon); ?> text-[9px]"></i> <?php echo esc_html($format_text); ?>
                        </span>
                    </div>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <?php if (!$is_video) : ?>
                            <!-- Direct Play Button -->
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); playAudio('<?php echo esc_js(get_the_title()); ?>', '<?php echo esc_js($person_name); ?>', '<?php echo esc_js($img_url); ?>', '<?php echo esc_js($duration); ?>')" class="w-9 h-9 rounded-full bg-goldAccent text-darkMain flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                <i class="fa-solid fa-play ml-0.5"></i>
                            </button>
                            <!-- Go to Single Page Button -->
                            <a href="<?php the_permalink(); ?>" class="w-9 h-9 rounded-full bg-white/20 text-white flex items-center justify-center shadow-lg hover:bg-white/40 hover:scale-110 transition-all border border-white/30" title="مشاهده پست">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        <?php else : ?>
                            <span class="w-10 h-10 rounded-full bg-goldAccent text-darkMain flex items-center justify-center shadow-lg hover:scale-110 transition-transform"><i class="fa-solid fa-play ml-0.5"></i></span>
                        <?php endif; ?>
                    </div>
                </div>
                <h4 class="text-sm md:text-base font-bold text-white truncate" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></h4>
                <span class="text-[11px] md:text-xs text-textMuted block mt-1 font-medium truncate"><?php echo esc_html($person_name); ?></span>
                <div class="flex justify-between items-center mt-2.5 pt-2.5 border-t border-white/5 text-[10px] md:text-xs text-textMuted">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-clock text-goldAccent/70"></i> <?php echo esc_html($duration); ?></span>
                    <span class="text-goldAccent font-bold flex items-center gap-1"><i class="fa-solid <?php echo esc_attr($play_icon); ?>"></i> <?php echo esc_html($format_text); ?></span>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Pagination -->
        <?php 
        $pagination = paginate_links(array(
            'total'     => $media_query->max_num_pages,
            'current'   => $paged,
            'prev_text' => '<i class="fa-solid fa-arrow-right"></i>',
            'next_text' => '<i class="fa-solid fa-arrow-left"></i>',
            'type'      => 'array'
        ));
        
        if ($pagination) {
            echo '<div class="infinite-scroll-trigger w-full flex justify-center py-8"><i class="fa-solid fa-circle-notch fa-spin text-3xl text-goldAccent opacity-0 transition-opacity duration-300"></i></div>';
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
        }
        ?>

    <?php else : ?>
        <div class="premium-card p-12 text-center text-textMuted">
            <i class="fa-solid fa-microphone-slash text-4xl mb-4 opacity-50"></i>
            <p class="text-lg">هیچ اثری در این دسته‌بندی یافت نشد.</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
