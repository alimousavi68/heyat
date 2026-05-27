<?php
/**
 * The template for displaying Events Archive
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Page Header -->
    <header class="relative overflow-hidden rounded-large h-48 md:h-64 mb-12 flex flex-col justify-center items-center text-center premium-card border border-white/5 shadow-2xl">
        <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full blur-[90px] bg-goldAccent/10 pointer-events-none z-10"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full blur-[100px] bg-red-600/10 pointer-events-none z-10"></div>
        
        <div class="relative z-20">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-4 mx-auto w-max">
                <i class="fa-solid fa-calendar-days text-xs"></i> تقویم هیئت
            </span>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
                مراسمات و برنامه‌های پیش‌رو
            </h1>
            <div class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mt-2">
                اطلاع‌رسانی زمان، مکان و جزئیات جلسات هفتگی و مناسبت‌های مذهبی
            </div>
        </div>
    </header>

    <?php if (have_posts()) : 
        // We'll show the first post as featured, and the rest as a grid
        $count = 0;
    ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 archive-grid">
            <?php 
            while (have_posts()) : the_post(); 
                $count++;
                $date     = get_post_meta(get_the_ID(), '_heyat_event_date', true);
                $time     = get_post_meta(get_the_ID(), '_heyat_event_time', true);
                $location = get_post_meta(get_the_ID(), '_heyat_event_location', true);
                $speakers = get_post_meta(get_the_ID(), '_heyat_event_speaker', true);
                $maddahs  = get_post_meta(get_the_ID(), '_heyat_event_maddah', true);

                $speaker_names = array();
                if (!empty($speakers) && is_array($speakers)) {
                    foreach ($speakers as $s_id) { $speaker_names[] = get_the_title($s_id); }
                }
                $maddah_names = array();
                if (!empty($maddahs) && is_array($maddahs)) {
                    foreach ($maddahs as $m_id) { $maddah_names[] = get_the_title($m_id); }
                }
                
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/poster.jpg';
                
                // Parse Date to get day and month
                $day = '-'; $month_name = '-';
                if ($date) {
                    $persian_digits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                    $arabic_digits  = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
                    $english_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                    $p_date_en = str_replace(array_merge($persian_digits, $arabic_digits), array_merge($english_digits, $english_digits), $date);
                    $p_date_en = str_replace('-', '/', $p_date_en);
                    $parts = explode('/', $p_date_en);
                    if (count($parts) >= 2) {
                        $day_part = end($parts);
                        $month_part = prev($parts);
                        $day = ltrim($day_part, '0');
                        if (empty($day)) $day = '0';
                        $months = array('', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');
                        $m_index = intval($month_part);
                        if (isset($months[$m_index])) {
                            $month_name = $months[$m_index];
                        }
                    }
                }
                
                if ($count === 1) : 
            ?>
            <!-- Featured Upcoming Event -->
            <div class="col-span-1 md:col-span-2 lg:col-span-3 premium-card glass-hover-effect p-6 mb-4 flex flex-col md:flex-row gap-8 shadow-2xl relative overflow-hidden group cursor-pointer" onclick="window.location.href='<?php the_permalink(); ?>';">
                <div class="w-full md:w-1/3 aspect-[4/3] rounded-large overflow-hidden shadow-lg relative border border-white/5">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-500" alt="<?php the_title_attribute(); ?>">
                    <div class="absolute top-3 right-3 bg-goldAccent text-darkMain px-3 py-1.5 rounded-md text-xs font-bold shadow-lg animate-pulse">برنامه پیش‌رو</div>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 group-hover:text-goldAccent transition-colors"><?php the_title(); ?></h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-textMuted">
                        <?php if (!empty($date)): ?>
                        <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent"><i class="fa-solid fa-calendar"></i></div> <span class="text-white"><?php echo esc_html($date); ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($time)): ?>
                        <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent"><i class="fa-solid fa-clock"></i></div> <span class="text-white"><?php echo esc_html($time); ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($location)): ?>
                        <div class="flex items-center gap-3 sm:col-span-2"><div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent"><i class="fa-solid fa-map-pin"></i></div> <span class="text-white"><?php echo esc_html($location); ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($speaker_names)): ?>
                        <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent"><i class="fa-solid fa-user-tie"></i></div> <span>سخنران: <span class="text-white"><?php echo esc_html(implode('، ', $speaker_names)); ?></span></span></div>
                        <?php endif; ?>
                        <?php if (!empty($maddah_names)): ?>
                        <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent"><i class="fa-solid fa-microphone"></i></div> <span>مداح: <span class="text-white"><?php echo esc_html(implode('، ', $maddah_names)); ?></span></span></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <!-- Regular Event Card (Past) -->
            <a href="<?php the_permalink(); ?>" class="premium-card glass-hover-effect p-5 flex flex-col justify-between h-full shadow-lg border border-white/5 hover:border-goldAccent/30 transition-all duration-300 group relative opacity-80 hover:opacity-100">
                <div class="absolute top-3 left-3 bg-white/5 text-textMuted px-2 py-1 rounded text-[10px] border border-white/5">برگزار شده</div>
                <div class="flex gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-goldAccent/10 text-goldAccent flex flex-col items-center justify-center text-center border border-goldAccent/20 flex-shrink-0 shadow-inner">
                        <span class="text-xl font-black leading-none mb-1"><?php echo esc_html($day); ?></span>
                        <span class="text-[10px] font-bold"><?php echo esc_html($month_name); ?></span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white group-hover:text-goldAccent transition-colors line-clamp-2 leading-tight"><?php the_title(); ?></h3>
                        <?php if (!empty($speaker_names) || !empty($maddah_names)): ?>
                        <div class="text-xs text-textMuted mt-2 flex flex-col gap-1">
                            <?php if (!empty($speaker_names)) echo '<span><i class="fa-solid fa-user-tie text-[10px]"></i> ' . esc_html($speaker_names[0]) . '</span>'; ?>
                            <?php if (!empty($maddah_names)) echo '<span><i class="fa-solid fa-microphone text-[10px]"></i> ' . esc_html($maddah_names[0]) . '</span>'; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="border-t border-white/5 pt-3 flex justify-between items-center text-xs text-textMuted">
                    <span><i class="fa-regular fa-clock text-goldAccent"></i> <?php echo esc_html($time ? $time : 'اعلام نشده'); ?></span>
                    <span class="text-white group-hover:translate-x-1 transition-transform">جزئیات <i class="fa-solid fa-arrow-left text-[10px]"></i></span>
                </div>
            </a>
            <?php endif; endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php 
        $pagination = paginate_links(array(
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
            <i class="fa-regular fa-calendar-xmark text-4xl mb-4 opacity-50"></i>
            <p class="text-lg">در حال حاضر مراسمی ثبت نشده است.</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
