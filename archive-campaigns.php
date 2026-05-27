<?php
/**
 * The template for displaying Campaigns Archive
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Page Header -->
    <header class="relative overflow-hidden rounded-large h-48 md:h-64 mb-12 flex flex-col justify-center items-center text-center premium-card border border-white/5 shadow-2xl">
        <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full blur-[90px] bg-goldAccent/10 pointer-events-none z-10"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full blur-[100px] bg-emerald-600/10 pointer-events-none z-10"></div>
        
        <div class="relative z-20">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-4 mx-auto w-max">
                <i class="fa-solid fa-hand-holding-heart text-xs"></i> مرکز نیکوکاری
            </span>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
                پویش‌های جمع‌سپاری و خیریه
            </h1>
            <div class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mt-2">
                مشارکت در طرح‌های جهادی، کمک‌های مومنانه و توسعه زیرساخت‌های هیئت
            </div>
        </div>
    </header>

    <?php 
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $filter_cat = isset($_GET['filter_cat']) ? sanitize_text_field($_GET['filter_cat']) : '';

    $args = array(
        'post_type' => 'campaigns',
        'paged' => $paged,
    );

    if ($filter_cat) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'campaign_category',
                'field'    => 'slug',
                'terms'    => $filter_cat,
            ),
        );
    }
    $campaigns_query = new WP_Query($args);
    ?>

    <!-- Categories Filter -->
    <div class="flex flex-wrap justify-center gap-3 mb-10">
        <a href="<?php echo get_post_type_archive_link('campaigns'); ?>" class="px-6 py-2 rounded-full text-sm font-bold transition-all <?php echo empty($filter_cat) ? 'bg-goldAccent text-darkMain' : 'ios-glass border border-white/10 text-white hover:border-goldAccent hover:text-goldAccent'; ?>">همه پویش‌ها</a>
        <?php 
        $terms = get_terms(array('taxonomy' => 'campaign_category', 'hide_empty' => true));
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $is_active = ($filter_cat === $term->slug);
                $classes = $is_active ? 'bg-goldAccent text-darkMain' : 'ios-glass border border-white/10 text-white hover:border-goldAccent hover:text-goldAccent';
                echo '<a href="?filter_cat=' . esc_attr($term->slug) . '" class="px-6 py-2 rounded-full text-sm font-bold transition-all ' . $classes . '">' . esc_html($term->name) . '</a>';
            }
        }
        ?>
    </div>

    <?php if ($campaigns_query->have_posts()) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 archive-grid">
            <?php 
            while ($campaigns_query->have_posts()) : $campaigns_query->the_post(); 
                $target       = floatval(get_post_meta(get_the_ID(), '_heyat_campaign_target', true));
                $collected    = floatval(get_post_meta(get_the_ID(), '_heyat_campaign_collected', true));
                $participants = get_post_meta(get_the_ID(), '_heyat_campaign_participants', true);
                
                if (!$participants) $participants = 0;
                
                // Calculate percentage
                $percentage = 0;
                if ($target > 0) {
                    $percentage = round(($collected / $target) * 100);
                    if ($percentage > 100) $percentage = 100;
                }
            ?>
            <div class="premium-card glass-hover-effect p-6 flex flex-col justify-between h-80 shadow-2xl hover:border-goldAccent/30 transition-all duration-300 relative group cursor-pointer" onclick="window.location.href='<?php the_permalink(); ?>';">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="bg-goldAccent/10 text-goldAccent px-3 py-1 rounded-full text-xs font-medium border border-goldAccent/15">پویش فعال</span>
                    </div>
                    <h4 class="text-base md:text-lg font-bold text-white mt-1.5 leading-snug group-hover:text-goldAccent transition-colors"><?php the_title(); ?></h4>
                    <div class="text-xs md:text-sm text-textMuted line-clamp-3 mt-1"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></div>
                </div>

                <div class="flex flex-col gap-3 mt-6 border-t border-white/5 pt-4">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-textMuted font-medium">هدف: <?php echo number_format($target); ?> تومان</span>
                            <span class="text-goldAccent font-bold"><?php echo $percentage; ?>٪</span>
                        </div>
                        <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden relative border border-white/5">
                            <div class="bg-goldAccent h-full rounded-full shadow-[0_0_8px_#FFB703] transition-all duration-500" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs text-textMuted font-medium">مشارکت‌ها: <strong class="text-white font-bold"><?php echo esc_html($participants); ?> نفر</strong></span>
                        <span class="text-goldAccent text-xs font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            مشاهده <i class="fa-solid fa-arrow-left"></i>
                        </span>
                    </div>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Pagination -->
        <?php 
        $pagination = paginate_links(array(
            'total'     => $campaigns_query->max_num_pages,
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
            <i class="fa-solid fa-box-open text-4xl mb-4 opacity-50"></i>
            <p class="text-lg">در حال حاضر پویش فعالی وجود ندارد.</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
