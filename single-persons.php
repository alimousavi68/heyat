<?php
/**
 * The template for displaying single person (speaker/maddah)
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-24"></div>

<?php while (have_posts()) : the_post(); 
    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
    if (!$img_url) $img_url = get_template_directory_uri() . '/images/avatar-placeholder.webp'; 
    $role = get_post_meta(get_the_ID(), '_heyat_person_role', true);
    $role_text = ($role == 'speaker') ? 'سخنران' : 'مداح اهل‌بیت';
    
    // Find upcoming events for this person
    $meta_query_key = ($role == 'speaker') ? '_heyat_event_speaker' : '_heyat_event_maddah';
    $events_args = array(
        'post_type' => 'events',
        'posts_per_page' => 3,
        'meta_query' => array(
            array(
                'key' => $meta_query_key,
                'value' => '"' . get_the_ID() . '"', // serialized array match roughly
                'compare' => 'LIKE'
            )
        )
    );
    $events_query = new WP_Query($events_args);
?>
<main class="max-w-4xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    
    <!-- Profile Card -->
    <div class="premium-card p-8 md:p-12 mb-10 border border-white/5 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center gap-8 md:gap-12">
        <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full blur-[100px] bg-goldAccent/10 pointer-events-none"></div>
        
        <!-- Avatar -->
        <div class="w-48 h-48 md:w-64 md:h-64 rounded-full p-2.5 bg-white/5 border border-white/10 shadow-[0_0_40px_rgba(223,177,91,0.15)] flex-shrink-0 relative z-10">
            <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full rounded-full object-cover border border-goldAccent/30" alt="<?php the_title_attribute(); ?>">
        </div>
        
        <!-- Info -->
        <div class="flex flex-col items-center md:items-start text-center md:text-right z-10">
            <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-sm font-bold border border-goldAccent/20 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-user-check"></i> <?php echo esc_html($role_text); ?>
            </span>
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-6"><?php the_title(); ?></h1>
            
            <div class="flex gap-3">
                <a href="#works" class="press-effect bg-goldAccent text-darkMain px-6 py-2.5 rounded-full text-sm font-bold shadow-lg hover:bg-white transition-colors">مشاهده آثار</a>
                <?php if ($events_query->have_posts()): ?>
                <a href="#events" class="ios-glass text-white px-6 py-2.5 rounded-full text-sm font-bold border border-white/10 hover:border-goldAccent hover:text-goldAccent transition-colors">مراسمات پیش‌رو</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="works">
        <!-- Person's Works (Media) -->
        <div class="md:col-span-2">
            <div class="premium-card p-6 md:p-10 border border-white/5 shadow-xl">
                <h3 class="text-xl font-bold text-goldAccent mb-6 flex items-center gap-2 border-b border-white/10 pb-4"><i class="fa-solid fa-compact-disc"></i> آثار ثبت شده</h3>
                <?php 
                $media_args = array(
                    'post_type' => 'heyat_media',
                    'posts_per_page' => 10,
                    's' => get_the_title() // Search for media mentioning this person
                );
                $media_query = new WP_Query($media_args);
                
                if ($media_query->have_posts()) :
                ?>
                <div class="flex flex-col gap-4">
                    <?php 
                    while ($media_query->have_posts()) : $media_query->the_post(); 
                        $is_video = has_term('video', 'media_format');
                        $icon = $is_video ? 'fa-video' : 'fa-headphones';
                    ?>
                    <a href="<?php the_permalink(); ?>" class="flex items-center gap-4 bg-white/[0.02] p-4 rounded-xl border border-white/5 hover:border-goldAccent/30 hover:bg-white/[0.05] transition-all group">
                        <div class="w-12 h-12 rounded-full bg-goldAccent/10 text-goldAccent flex items-center justify-center flex-shrink-0 group-hover:bg-goldAccent group-hover:text-darkMain transition-colors">
                            <i class="fa-solid <?php echo esc_attr($icon); ?>"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm md:text-base font-bold text-white group-hover:text-goldAccent transition-colors"><?php the_title(); ?></h4>
                            <span class="text-xs text-textMuted mt-1 block"><?php echo get_the_date('j F Y'); ?></span>
                        </div>
                        <i class="fa-solid fa-chevron-left text-textMuted group-hover:text-goldAccent group-hover:-translate-x-2 transition-all"></i>
                    </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <div class="mt-6 text-center">
                    <a href="<?php echo get_post_type_archive_link('heyat_media'); ?>?s=<?php echo urlencode(get_the_title()); ?>" class="text-xs font-bold text-goldAccent hover:text-white transition-colors border border-goldAccent/20 px-4 py-2 rounded-full ios-glass inline-block">مشاهده همه آثار این شخص</a>
                </div>
                <?php else : ?>
                    <p class="text-textMuted text-center py-8">اثری از ایشان در سایت ثبت نشده است.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Related Events -->
        <div class="md:col-span-1" id="events">
            <div class="premium-card p-6 border border-white/5 shadow-xl sticky top-24">
                <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2 mb-6">
                    <i class="fa-solid fa-calendar-check text-goldAccent"></i> مراسمات پیش‌رو
                </h3>
                
                <?php if ($events_query->have_posts()): ?>
                    <div class="flex flex-col gap-4">
                    <?php 
                    while ($events_query->have_posts()) : $events_query->the_post(); 
                        $date = get_post_meta(get_the_ID(), '_heyat_event_date', true);
                    ?>
                        <a href="<?php the_permalink(); ?>" class="group bg-white/[0.02] p-3 rounded-xl border border-white/5 hover:border-goldAccent/30 hover:bg-white/[0.05] transition-all">
                            <h4 class="text-sm font-bold text-white group-hover:text-goldAccent transition-colors mb-2"><?php the_title(); ?></h4>
                            <div class="flex items-center justify-between text-xs text-textMuted">
                                <span><i class="fa-regular fa-calendar"></i> <?php echo esc_html($date ? $date : 'بدون تاریخ'); ?></span>
                                <i class="fa-solid fa-arrow-left text-[10px] group-hover:-translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-sm text-textMuted py-4">
                        <i class="fa-regular fa-calendar-xmark block text-3xl mb-2 opacity-50 mx-auto"></i>
                        برنامه‌ای در پیش نیست
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
