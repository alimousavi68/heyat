<?php
/**
 * The template for displaying single campaigns
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-20"></div>

<?php while (have_posts()) : the_post(); 
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
<main class="max-w-4xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Campaign Dashboard Box -->
    <div class="premium-card p-8 md:p-12 mb-10 border border-goldAccent/20 shadow-[0_10px_40px_rgba(223,177,91,0.05)] relative overflow-hidden">
        <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full blur-[100px] bg-goldAccent/10 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-6">
                <i class="fa-solid fa-hand-holding-dollar text-sm"></i> پویش نیکوکاری
            </span>
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight"><?php the_title(); ?></h1>
            <p class="text-sm md:text-base text-textMuted max-w-2xl mx-auto mb-10 leading-relaxed"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
            
            <!-- Progress Bar -->
            <div class="w-full max-w-2xl mx-auto mb-8">
                <div class="flex justify-between items-end mb-3 text-sm md:text-base">
                    <span class="text-textMuted font-medium">هدف: <span class="text-white font-bold"><?php echo number_format($target); ?> تومان</span></span>
                    <span class="text-goldAccent font-bold text-xl md:text-2xl"><?php echo $percentage; ?>٪</span>
                </div>
                <div class="w-full bg-black/50 h-4 md:h-5 rounded-full overflow-hidden border border-white/10 p-0.5">
                    <div class="bg-gradient-to-l from-goldAccent to-yellow-600 h-full rounded-full shadow-[0_0_15px_#FFB703] transition-all duration-1000 ease-out" style="width: <?php echo $percentage; ?>%"></div>
                </div>
                <div class="flex justify-between items-center mt-3 text-xs md:text-sm">
                    <span class="text-textMuted">جمع‌آوری شده: <span class="text-white font-bold"><?php echo number_format($collected); ?> تومان</span></span>
                    <span class="text-textMuted">مشارکت‌کنندگان: <span class="text-white font-bold"><?php echo number_format($participants); ?> نفر</span></span>
                </div>
            </div>
            
            <!-- Call to Action -->
            <?php if ($pay_link): ?>
            <a href="<?php echo esc_url($pay_link); ?>" target="_blank" class="w-full max-w-sm mx-auto press-effect bg-goldAccent hover:bg-white text-darkMain px-8 py-4 rounded-xl text-lg font-bold shadow-[0_4px_25px_rgba(255,183,3,0.4)] transition-all flex items-center justify-center gap-3 mt-4">
                <span>مشارکت در این طرح</span>
                <i class="fa-solid fa-heart"></i>
            </a>
            <?php else: ?>
            <div class="w-full max-w-sm mx-auto ios-glass px-8 py-4 rounded-xl text-textMuted text-center font-bold border border-white/5 mt-4">
                لینک پرداخت هنوز تنظیم نشده است.
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Campaign Details Content -->
    <?php 
    $content = get_the_content();
    if (!empty($content)) : 
    ?>
    <article class="premium-card p-6 md:p-10 border border-white/5 text-white/90 leading-loose text-sm md:text-base single-content shadow-xl">
        <h3 class="text-xl font-bold text-goldAccent mb-8 flex items-center gap-2 border-b border-white/10 pb-4"><i class="fa-solid fa-file-lines"></i> جزئیات کامل طرح</h3>
        <style>
            .single-content p { margin-bottom: 1.5em; text-align: justify; }
            .single-content h1, .single-content h2, .single-content h3, .single-content h4 { color: #fff; font-weight: bold; margin-top: 2em; margin-bottom: 1em; }
            .single-content h2 { font-size: 1.5rem; color: #DFB15B; }
            .single-content ul { list-style-type: disc; margin-right: 1.5em; margin-bottom: 1.5em; }
            .single-content ol { list-style-type: decimal; margin-right: 1.5em; margin-bottom: 1.5em; }
            .single-content img { border-radius: 14px; max-width: 100%; height: auto; margin: 2em auto; display: block; }
        </style>
        <?php the_content(); ?>
    </article>
    <?php endif; ?>
    
    <!-- Share -->
    <div class="mt-8 flex items-center justify-center gap-3">
        <span class="text-textMuted text-sm ml-2">اشتراک‌گذاری برای کار خیر:</span>
        <a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="w-10 h-10 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all"><i class="fa-brands fa-telegram"></i></a>
        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" class="w-10 h-10 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all"><i class="fa-brands fa-whatsapp"></i></a>
    </div>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
