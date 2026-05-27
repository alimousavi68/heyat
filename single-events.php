<?php
/**
 * The template for displaying single event posts
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-20"></div>

<?php while (have_posts()) : the_post(); 
    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if (!$img_url) $img_url = get_template_directory_uri() . '/images/poster.jpg';
    
    $date     = get_post_meta(get_the_ID(), '_heyat_event_date', true);
    $time     = get_post_meta(get_the_ID(), '_heyat_event_time', true);
    $location = get_post_meta(get_the_ID(), '_heyat_event_location', true);
    $map      = get_post_meta(get_the_ID(), '_heyat_event_map', true);
    $speakers = get_post_meta(get_the_ID(), '_heyat_event_speaker', true);
    $maddahs  = get_post_meta(get_the_ID(), '_heyat_event_maddah', true);
?>
<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Content Area -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            <!-- Event Banner -->
            <div class="w-full aspect-[16/9] md:aspect-[2/1] rounded-large overflow-hidden premium-card border border-white/5 shadow-2xl relative group">
                <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?php the_title_attribute(); ?>">
                <div class="absolute inset-0 bg-gradient-to-t from-darkMain via-darkMain/20 to-transparent"></div>
                <div class="absolute bottom-6 right-6 left-6">
                    <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-[10px] font-bold border border-goldAccent/20 flex items-center gap-2 mb-3 w-max">
                        <i class="fa-solid fa-flag text-xs"></i> اطلاعیه مراسم
                    </span>
                    <h1 class="text-2xl md:text-4xl font-bold text-white leading-tight text-shadow-lg"><?php the_title(); ?></h1>
                </div>
            </div>
            
            <!-- Event Description -->
            <?php 
            $content = get_the_content();
            if (!empty($content)) : 
            ?>
            <article class="premium-card p-6 md:p-10 border border-white/5 text-white/90 leading-loose text-sm md:text-base single-content shadow-xl">
                <h3 class="text-xl font-bold text-goldAccent mb-6 flex items-center gap-2 border-b border-white/10 pb-4"><i class="fa-solid fa-align-right"></i> جزئیات مراسم</h3>
                <style>
                    .single-content p { margin-bottom: 1.5em; text-align: justify; }
                    .single-content h1, .single-content h2, .single-content h3, .single-content h4 { color: #fff; font-weight: bold; margin-top: 2em; margin-bottom: 1em; }
                    .single-content h2 { font-size: 1.5rem; color: #DFB15B; }
                    .single-content ul { list-style-type: disc; margin-right: 1.5em; margin-bottom: 1.5em; }
                </style>
                <?php the_content(); ?>
            </article>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar Details -->
        <div class="lg:col-span-1">
            <div class="premium-card p-6 border border-white/5 shadow-xl sticky top-24 flex flex-col gap-6">
                <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-goldAccent"></i> اطلاعات مراسم
                </h3>
                
                <div class="flex flex-col gap-4 text-sm">
                    <?php if (!empty($date)): ?>
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent flex-shrink-0"><i class="fa-regular fa-calendar"></i></div>
                        <div class="flex flex-col">
                            <span class="text-textMuted text-xs mb-1">تاریخ برگزاری</span>
                            <strong class="text-white"><?php echo esc_html($date); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($time)): ?>
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent flex-shrink-0"><i class="fa-regular fa-clock"></i></div>
                        <div class="flex flex-col">
                            <span class="text-textMuted text-xs mb-1">زمان شروع</span>
                            <strong class="text-white"><?php echo esc_html($time); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($location)): ?>
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-goldAccent flex-shrink-0"><i class="fa-solid fa-map-location-dot"></i></div>
                        <div class="flex flex-col">
                            <span class="text-textMuted text-xs mb-1">مکان برگزاری</span>
                            <strong class="text-white leading-relaxed"><?php echo esc_html($location); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($speakers) || !empty($maddahs)): ?>
                <div class="border-t border-white/10 pt-5 mt-2 flex flex-col gap-4">
                    <?php 
                    if (!empty($speakers) && is_array($speakers)) {
                        foreach ($speakers as $s_id) {
                            $s_img = get_the_post_thumbnail_url($s_id, 'thumbnail');
                            if (!$s_img) $s_img = get_template_directory_uri() . '/images/default-avatar.png'; // fallback
                    ?>
                    <div class="flex items-center gap-3 bg-white/[0.02] p-2 rounded-xl border border-white/5">
                        <img src="<?php echo esc_url($s_img); ?>" class="w-10 h-10 rounded-full object-cover border border-goldAccent/30" alt="<?php echo esc_attr(get_the_title($s_id)); ?>">
                        <div class="flex flex-col">
                            <span class="text-xs text-goldAccent">سخنران</span>
                            <strong class="text-sm text-white"><?php echo esc_html(get_the_title($s_id)); ?></strong>
                        </div>
                    </div>
                    <?php 
                        }
                    }
                    ?>
                    
                    <?php 
                    if (!empty($maddahs) && is_array($maddahs)) {
                        foreach ($maddahs as $m_id) {
                            $m_img = get_the_post_thumbnail_url($m_id, 'thumbnail');
                            if (!$m_img) $m_img = get_template_directory_uri() . '/images/default-avatar.png'; // fallback
                    ?>
                    <div class="flex items-center gap-3 bg-white/[0.02] p-2 rounded-xl border border-white/5">
                        <img src="<?php echo esc_url($m_img); ?>" class="w-10 h-10 rounded-full object-cover border border-goldAccent/30" alt="<?php echo esc_attr(get_the_title($m_id)); ?>">
                        <div class="flex flex-col">
                            <span class="text-xs text-goldAccent">مداح</span>
                            <strong class="text-sm text-white"><?php echo esc_html(get_the_title($m_id)); ?></strong>
                        </div>
                    </div>
                    <?php 
                        }
                    }
                    ?>
                </div>
                <?php endif; ?>
                
                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 mt-4">
                    <?php if (!empty($map)): ?>
                    <a href="<?php echo esc_url($map); ?>" target="_blank" class="w-full press-effect bg-goldAccent hover:bg-white text-darkMain py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg transition-colors">
                        <i class="fa-solid fa-location-arrow"></i> مسیریابی روی نقشه
                    </a>
                    <?php endif; ?>
                    <a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="w-full ios-glass text-white hover:text-goldAccent hover:border-goldAccent py-3 rounded-xl font-medium text-sm flex items-center justify-center gap-2 border border-white/10 transition-colors">
                        <i class="fa-regular fa-paper-plane"></i> ارسال برای دوستان
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
