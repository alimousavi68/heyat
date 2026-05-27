<?php
/**
 * The template for displaying Persons (Speakers & Maddahs) Archive
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Page Header -->
    <header class="relative overflow-hidden rounded-large h-48 md:h-64 mb-16 flex flex-col justify-center items-center text-center premium-card border border-white/5 shadow-2xl">
        <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full blur-[90px] bg-goldAccent/10 pointer-events-none z-10"></div>
        
        <div class="relative z-20">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-4 mx-auto w-max">
                <i class="fa-solid fa-microphone-lines text-xs"></i> اشخاص
            </span>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
                مادحین و سخنرانان هیئت
            </h1>
            <div class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mt-2">
                ذاکرین اهل‌بیت (ع)، سخنرانان برجسته و میهمانان گرامی جلسات ثارالله
            </div>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <!-- Filters -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="person-filter-btn active px-6 py-2 rounded-full text-sm font-bold transition-all bg-goldAccent text-darkMain" data-filter="all">همه اشخاص</button>
            <button class="person-filter-btn px-6 py-2 rounded-full text-sm font-bold transition-all ios-glass border border-white/10 text-white hover:border-goldAccent hover:text-goldAccent" data-filter="speaker">سخنرانان</button>
            <button class="person-filter-btn px-6 py-2 rounded-full text-sm font-bold transition-all ios-glass border border-white/10 text-white hover:border-goldAccent hover:text-goldAccent" data-filter="maddah">مادحین</button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-y-12 gap-x-6 justify-items-center archive-grid" id="persons-grid">
            <?php 
            while (have_posts()) : the_post(); 
                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$img_url) $img_url = get_template_directory_uri() . '/images/avatar-placeholder.webp'; 
                $role = get_post_meta(get_the_ID(), '_heyat_person_role', true);
                $role_text = ($role == 'speaker') ? 'سخنران' : 'مداح اهل‌بیت';
            ?>
            <a href="<?php the_permalink(); ?>" class="person-card flex flex-col items-center gap-4 cursor-pointer press-effect group" data-role="<?php echo esc_attr($role); ?>">
                <div class="w-32 h-48 md:w-40 md:h-56 rounded-full p-2.5 premium-card glass-hover-effect border border-white/10 group-hover:border-goldAccent/40 shadow-xl transition-all overflow-hidden flex-shrink-0 flex items-center justify-center relative">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full rounded-full object-cover brightness-90 group-hover:brightness-110 group-hover:scale-105 transition-all duration-500" alt="<?php the_title_attribute(); ?>">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-goldAccent/40 via-amber-600/20 to-rose-900/40 mix-blend-soft-light opacity-90 group-hover:opacity-50 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="absolute inset-0 rounded-full bg-gradient-to-t from-black/80 via-transparent to-goldAccent/10 opacity-100 group-hover:opacity-70 transition-opacity duration-500 pointer-events-none border border-goldAccent/20"></div>
                </div>
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-bold text-white group-hover:text-goldAccent transition-colors">
                        <?php the_title(); ?>
                    </h2>
                    <span class="block text-xs text-textMuted mt-1 font-medium bg-white/5 px-3 py-1 rounded-full border border-white/5 mx-auto w-max"><?php echo esc_html($role_text); ?></span>
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
            <i class="fa-solid fa-users-slash text-4xl mb-4 opacity-50"></i>
            <p class="text-lg">در حال حاضر شخصی ثبت نشده است.</p>
        </div>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.person-filter-btn');
    
    window.applyPersonFilter = function() {
        const activeBtn = document.querySelector('.person-filter-btn.bg-goldAccent');
        if (!activeBtn) return;
        const filterValue = activeBtn.getAttribute('data-filter');
        const personCards = document.querySelectorAll('.person-card');
        
        personCards.forEach(card => {
            if (filterValue === 'all' || card.getAttribute('data-role') === filterValue) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    };

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state of buttons
            filterBtns.forEach(b => {
                b.classList.remove('bg-goldAccent', 'text-darkMain');
                b.classList.add('ios-glass', 'border-white/10', 'text-white');
            });
            this.classList.remove('ios-glass', 'border-white/10', 'text-white');
            this.classList.add('bg-goldAccent', 'text-darkMain');

            window.applyPersonFilter();
        });
    });
});
</script>

<?php get_footer(); ?>
