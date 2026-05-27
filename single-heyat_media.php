<?php
/**
 * The template for displaying single media posts
 */
get_header(); 
?>

<!-- Spacer -->
<div class="h-20"></div>

<?php while (have_posts()) : the_post(); 
    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if (!$img_url) $img_url = get_template_directory_uri() . '/images/slider-0.webp';
    
    $is_video = has_term('video', 'media_format');
    $format_icon = $is_video ? 'fa-video' : 'fa-headphones';
    $format_text = $is_video ? 'ویدیویی' : 'صوتی';
    
    $person_name = get_the_excerpt();
    if (empty($person_name)) $person_name = get_the_author();
    
    $files = get_post_meta(get_the_ID(), '_heyat_media_files', true);
    // Determine main file for the player
    $main_url = '';
    if (!empty($files) && is_array($files)) {
        $main_url = !empty($files[0]['url']) ? $files[0]['url'] : '';
    }
?>
<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[50vh]">
    
    <!-- Player & Details Box -->
    <div class="premium-card p-6 md:p-10 mb-8 flex flex-col md:flex-row gap-8 items-center border border-white/10 shadow-2xl relative overflow-hidden">
        <!-- Glow effect -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full blur-[120px] bg-goldAccent/10 pointer-events-none"></div>
        
        <!-- Cover -->
        <div class="w-48 h-48 md:w-64 md:h-64 rounded-large overflow-hidden flex-shrink-0 shadow-2xl relative border border-white/10 z-10">
            <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover" alt="<?php the_title_attribute(); ?>">
            <span class="absolute top-3 right-3 ios-glass text-[10px] font-bold text-goldAccent px-3 py-1 rounded-full flex items-center gap-1 border border-goldAccent/20">
                <i class="fa-solid <?php echo esc_attr($format_icon); ?>"></i> <?php echo esc_html($format_text); ?>
            </span>
        </div>
        
        <!-- Info & Controls -->
        <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-right w-full z-10">
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 leading-tight"><?php the_title(); ?></h1>
            <span class="text-lg text-goldAccent font-medium mb-8 flex items-center gap-2"><i class="fa-solid fa-microphone"></i> <?php echo esc_html($person_name); ?></span>
            
            <!-- Real HTML5 Player -->
            <div class="w-full max-w-2xl bg-black/30 rounded-xl p-4 md:p-6 border border-white/5">
                <?php if ($is_video && !empty($main_url)): ?>
                    <video controls class="w-full rounded-lg outline-none shadow-lg" poster="<?php echo esc_url($img_url); ?>">
                        <source src="<?php echo esc_url($main_url); ?>" type="video/mp4">
                        مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                    </video>
                <?php elseif (!empty($main_url)): ?>
                    <!-- Custom CSS to make audio player look slightly better -->
                    <style>
                        audio::-webkit-media-controls-panel {
                            background-color: #DFB15B;
                        }
                    </style>
                    <audio controls class="w-full outline-none rounded-full shadow-lg">
                        <source src="<?php echo esc_url($main_url); ?>" type="audio/mpeg">
                        مرورگر شما از پخش صدا پشتیبانی نمی‌کند.
                    </audio>
                <?php else: ?>
                    <div class="text-center text-textMuted py-4">فایل رسانه برای پخش آپلود نشده است.</div>
                <?php endif; ?>
            </div>
            
            <!-- Download Buttons -->
            <?php if (!empty($files) && is_array($files)) : ?>
            <div class="mt-8 flex flex-wrap gap-3 justify-center md:justify-start w-full">
                <?php foreach($files as $file) : 
                    if (empty($file['url'])) continue;
                    $quality = !empty($file['quality']) ? $file['quality'] : 'لینک دانلود';
                ?>
                <a href="<?php echo esc_url($file['url']); ?>" download class="ios-glass px-6 py-3 rounded-full text-sm font-bold text-white hover:text-darkMain hover:bg-goldAccent transition-all border border-white/10 flex items-center gap-2 shadow-lg hover:shadow-goldAccent/20">
                    <i class="fa-solid fa-download"></i> دانلود (<?php echo esc_html($quality); ?>)
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Content / Lyrics -->
    <?php 
    $content = get_the_content();
    if (!empty($content)) : 
    ?>
    <div class="max-w-4xl mx-auto premium-card p-6 md:p-10 border border-white/5 text-white/90 leading-loose text-sm md:text-base single-content shadow-xl mt-12">
        <h3 class="text-xl font-bold text-goldAccent mb-8 flex items-center gap-2 border-b border-white/10 pb-4"><i class="fa-solid fa-align-right"></i> متن / توضیحات تکمیلی</h3>
        <style>
            .single-content p { margin-bottom: 1.5em; text-align: center; }
            .single-content h1, .single-content h2, .single-content h3, .single-content h4 { color: #fff; font-weight: bold; margin-top: 2em; margin-bottom: 1em; text-align: right; }
            .single-content h2 { font-size: 1.5rem; color: #DFB15B; }
        </style>
        <?php the_content(); ?>
    </div>
    <?php endif; ?>

</main>
<?php endwhile; ?>

<?php get_footer(); ?>
