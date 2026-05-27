<?php
/**
 * Template part for displaying charity campaigns in home page
 */

$campaigns_args = array(
    'post_type'      => 'campaigns',
    'posts_per_page' => get_theme_mod('count_campaigns_section', 3),
    'post_status'    => 'publish'
);
$campaigns_query = new WP_Query($campaigns_args);

if ($campaigns_query->have_posts()) :
?>
<!-- CHARITY CAMPAIGNS -->
<section id="campaigns" class="reveal-on-scroll my-12">
    <!-- Section Header -->
    <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-goldAccent/10 text-goldAccent flex items-center justify-center text-xl border border-goldAccent/20">
                <?php heyat_render_section_icon('campaigns_section', 'fa-hand-holding-heart'); ?>
            </div>
            <div class="flex flex-col text-right">
                <h3 class="text-base md:text-lg font-bold text-white tracking-wide"><?php echo esc_html(get_theme_mod('title_campaigns_section', 'پویش‌های در حال اجرا')); ?></h3>
                <span class="hidden md:block text-[10px] text-textMuted font-medium mt-0.5"><?php echo esc_html(get_theme_mod('subtitle_campaigns_section', 'بستر همدلی، موسسه خیریه')); ?></span>
            </div>
        </div>
        <a href="<?php echo get_post_type_archive_link('campaigns'); ?>" class="text-xs md:text-sm font-medium text-goldAccent hover:text-white transition-colors duration-300 group press-effect">
            <span>بیشتر</span>
            <i class="fa-solid fa-arrow-left text-xs transition-transform duration-300 group-hover:-translate-x-1"></i>
        </a>
    </div>

    <!-- Active campaigns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php while ($campaigns_query->have_posts()) : $campaigns_query->the_post(); 
            $target       = floatval(get_post_meta(get_the_ID(), '_heyat_campaign_target', true));
            $collected    = floatval(get_post_meta(get_the_ID(), '_heyat_campaign_collected', true));
            $participants = get_post_meta(get_the_ID(), '_heyat_campaign_participants', true);
            $pay_link     = get_post_meta(get_the_ID(), '_heyat_campaign_link', true);
            
            if (!$participants) $participants = 0;
            
            // Calculate percentage
            $percentage = 0;
            if ($target > 0) {
                $percentage = round(($collected / $target) * 100);
                if ($percentage > 100) $percentage = 100;
            }
        ?>
        <div class="premium-card glass-hover-effect p-6 flex flex-col justify-between h-72 shadow-2xl hover:border-goldAccent/30 transition-all duration-300">
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="bg-goldAccent/10 text-goldAccent px-3 py-1 rounded-full text-xs font-medium border border-goldAccent/15">خیریه</span>
                </div>
                <h4 class="text-base md:text-lg font-bold text-white mt-1.5 leading-snug"><?php the_title(); ?></h4>
                <div class="text-xs md:text-sm text-textMuted line-clamp-2"><?php the_excerpt(); ?></div>
            </div>

            <div class="flex flex-col gap-3 mt-6">
                <div class="flex flex-col gap-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-textMuted font-medium">مبلغ کل: <?php echo number_format($target); ?> تومان</span>
                        <span class="text-goldAccent font-bold">تحقق: <?php echo $percentage; ?>٪</span>
                    </div>
                    <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden relative border border-white/5">
                        <div class="bg-goldAccent h-full rounded-full shadow-[0_0_8px_#FFB703] transition-all duration-500" style="width: <?php echo $percentage; ?>%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-white/5 pt-3">
                    <span class="text-xs text-textMuted font-medium">مشارکت‌ها: <strong class="text-white font-bold"><?php echo esc_html($participants); ?> نفر</strong></span>
                    <?php if ($pay_link): ?>
                    <a href="<?php echo esc_url($pay_link); ?>" target="_blank" class="press-effect bg-goldAccent hover:bg-white text-darkMain px-5 py-2.5 rounded-small text-xs md:text-sm font-bold shadow-[0_4px_14px_rgba(255,183,3,0.3)] transition-colors flex items-center gap-2">
                        <span>مشارکت در طرح</span>
                        <i class="fa-solid fa-coins text-xs"></i>
                    </a>
                    <?php endif; ?>
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
