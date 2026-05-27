<?php
/**
 * The template for displaying Gallery Archive
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Page Header -->
    <header class="relative overflow-hidden rounded-large h-48 md:h-64 mb-12 flex flex-col justify-center items-center text-center premium-card border border-white/5 shadow-2xl">
        <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full blur-[90px] bg-goldAccent/10 pointer-events-none z-10"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full blur-[100px] bg-cyan-600/10 pointer-events-none z-10"></div>
        
        <div class="relative z-20">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-4 mx-auto w-max">
                <i class="fa-solid fa-camera-retro text-xs"></i> رسانه تصویری
            </span>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
                گزارش‌های تصویری مراسمات
            </h1>
            <div class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mt-2">
                مستندنگاری تصویری و ثبت جلوه‌های معنوی و هنری جلسات هیئت عاشقان ثارالله
            </div>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <!-- Grid layout: 2 cols on mobile, 3 on tablet, 4 on desktop -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 archive-grid" id="gallery-archive-grid">
            <?php 
            while (have_posts()) : the_post(); 
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/g-1.jpg';
            ?>
            <a href="<?php the_permalink(); ?>" class="premium-card glass-hover-effect relative rounded-large overflow-hidden aspect-square md:aspect-[4/3] group cursor-pointer press-effect block border border-white/5 shadow-xl hover:border-goldAccent/30 hover:shadow-[0_10px_30px_rgba(223,177,91,0.15)] transition-all duration-300">
                <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-700" alt="<?php the_title_attribute(); ?>">
                <div class="absolute inset-0 bg-gradient-to-t from-darkMain via-darkMain/50 to-transparent p-5 flex flex-col justify-end text-right z-10">
                    <span class="ios-glass w-max text-white/80 px-2.5 py-1 rounded-md text-[10px] mb-2 flex items-center gap-1">
                        <i class="fa-regular fa-calendar"></i> <?php echo get_the_date('j F Y'); ?>
                    </span>
                    <div class="flex justify-between items-center w-full">
                        <h2 class="text-base font-bold text-white group-hover:text-goldAccent transition-colors truncate w-4/5"><?php the_title(); ?></h2>
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center group-hover:bg-goldAccent group-hover:text-darkMain transition-all">
                            <i class="fa-solid fa-camera text-xs"></i>
                        </div>
                    </div>
                </div>
            </a>
            <?php endwhile; ?>
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
            <i class="fa-solid fa-images text-4xl mb-4 opacity-50"></i>
            <p class="text-lg">در حال حاضر گزارشی وجود ندارد.</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
