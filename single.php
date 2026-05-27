<?php
/**
 * The template for displaying all single posts
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-20"></div>

<?php while (have_posts()) : the_post(); 
    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if (!$img_url) $img_url = get_template_directory_uri() . '/images/slider-0.webp';
    
    $categories = get_the_category();
    $cat_name = !empty($categories) ? $categories[0]->name : 'بدون دسته';
    $cat_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#';
    
    $time_diff = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' پیش';
    
    $content = get_post_field('post_content', get_the_ID());
    $word_count = count(explode(' ', strip_tags($content)));
    $read_time = max(1, ceil($word_count / 200));
?>
<!-- Full-width Hero Banner for Single Post -->
<div class="relative w-full h-[400px] md:h-[500px] bg-darkMain flex items-end justify-center mb-12">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url($img_url); ?>');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-darkMain via-darkMain/80 to-darkMain/20"></div>
    
    <div class="relative z-10 max-w-4xl w-full px-4 text-center pb-12">
        <a href="<?php echo esc_url($cat_link); ?>" class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 inline-block mb-6 hover:bg-goldAccent hover:text-darkMain transition-colors">
            <?php echo esc_html($cat_name); ?>
        </a>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight drop-shadow-lg">
            <?php the_title(); ?>
        </h1>
        
        <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-textMuted">
            <span class="flex items-center gap-2"><i class="fa-solid fa-user-pen text-goldAccent"></i> <?php the_author(); ?></span>
            <span class="flex items-center gap-2"><i class="fa-regular fa-calendar text-goldAccent"></i> <?php echo get_the_date('j F Y'); ?> (<?php echo esc_html($time_diff); ?>)</span>
            <span class="flex items-center gap-2"><i class="fa-regular fa-clock text-goldAccent"></i> <?php echo $read_time; ?> دقیقه مطالعه</span>
        </div>
    </div>
</div>

<main class="max-w-4xl mx-auto px-4 w-full mb-20 min-h-[40vh]">
    <!-- Content Wrapper with custom typography styling -->
    <article class="premium-card p-6 md:p-10 border border-white/5 text-white/90 leading-loose text-sm md:text-base single-content">
        <?php 
        // We'll wrap the content to apply some default spacing to p, h2, h3 etc since Tailwind resets them.
        ?>
        <style>
            .single-content p { margin-bottom: 1.5em; text-align: justify; }
            .single-content h1, .single-content h2, .single-content h3, .single-content h4 { color: #fff; font-weight: bold; margin-top: 2em; margin-bottom: 1em; }
            .single-content h2 { font-size: 1.5rem; color: #DFB15B; }
            .single-content h3 { font-size: 1.25rem; }
            .single-content ul { list-style-type: disc; margin-right: 1.5em; margin-bottom: 1.5em; }
            .single-content ol { list-style-type: decimal; margin-right: 1.5em; margin-bottom: 1.5em; }
            .single-content a { color: #DFB15B; text-decoration: underline; }
            .single-content a:hover { color: #fff; }
            .single-content img { border-radius: 14px; max-width: 100%; height: auto; margin: 2em auto; display: block; border: 1px solid rgba(255,255,255,0.1); }
            .single-content blockquote { border-right: 4px solid #DFB15B; padding-right: 1em; margin-right: 0; color: #969BA9; font-style: italic; background: rgba(255,255,255,0.02); padding: 1em; border-radius: 8px; }
        </style>
        
        <?php the_content(); ?>
    </article>

    <!-- Tags & Share -->
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-white/10 pt-8">
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-textMuted text-sm ml-2"><i class="fa-solid fa-tags"></i> برچسب‌ها:</span>
            <?php 
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="bg-white/[0.03] hover:bg-goldAccent hover:text-darkMain border border-white/5 text-white/80 text-xs px-3 py-1.5 rounded-md transition-colors">' . esc_html($tag->name) . '</a>';
                }
            } else {
                echo '<span class="text-xs text-white/50">ندارد</span>';
            }
            ?>
        </div>
        
        <!-- Social Share (Static UI) -->
        <div class="flex items-center gap-3">
            <span class="text-textMuted text-sm ml-2">اشتراک‌گذاری:</span>
            <a href="#" class="w-9 h-9 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all"><i class="fa-brands fa-telegram"></i></a>
            <a href="#" class="w-9 h-9 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="#" class="w-9 h-9 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
    </div>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
