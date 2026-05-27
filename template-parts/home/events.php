<?php
/**
 * Template part for displaying events in home page
 */

// Query for Events
$events_args = array(
    'post_type'      => 'events',
    'posts_per_page' => get_theme_mod('count_heyat_events_section', 4),
    'meta_key'       => '_heyat_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    // In a real scenario with Persian dates, you'd filter by >= today's Persian date.
    // Assuming the date is stored as YYYY/MM/DD, a string comparison meta_query could work if we had the current Persian date.
    // For simplicity, we just order them by date and take the first 4.
);

$events_query = new WP_Query($events_args);

if ($events_query->have_posts()) :
    $events_posts = $events_query->get_posts();
    $first_event = array_shift($events_posts); // The first event is the "Upcoming" one
?>
<!-- RESPONSIVE EVENTS GRID (Upcoming & Calendar) -->
<div id="events" class="reveal-on-scroll grid grid-cols-1 md:grid-cols-3 gap-6 my-10">
    <!-- Upcoming Event Card -->
    <div class="md:col-span-2 premium-card glass-hover-effect flex flex-col justify-between">
        <div>
            <!-- Header of Upcoming Card -->
            <div class="pb-3 border-b border-white/10 mb-4 flex items-center justify-between p-4 ">
                <span class="text-sm font-bold text-white flex items-center gap-2">
                    <?php heyat_render_section_icon('events_upcoming', 'fa-calendar-check', 'text-goldAccent text-base animate-pulse'); ?>
                    <span><?php echo esc_html(get_theme_mod('title_events_upcoming', 'برنامه پیش‌رو هیئت')); ?></span>
                </span>
                <i class="fa-solid fa-calendar-day text-sm text-textMuted"></i>
            </div>

            <?php
            // Get Meta for First Event
            $date     = get_post_meta($first_event->ID, '_heyat_event_date', true);
            $time     = get_post_meta($first_event->ID, '_heyat_event_time', true);
            $location = get_post_meta($first_event->ID, '_heyat_event_location', true);
            $map      = get_post_meta($first_event->ID, '_heyat_event_map', true);
            $speakers = get_post_meta($first_event->ID, '_heyat_event_speaker', true);
            $maddahs  = get_post_meta($first_event->ID, '_heyat_event_maddah', true);

            $speaker_names = array();
            if (!empty($speakers) && is_array($speakers)) {
                foreach ($speakers as $s_id) {
                    $speaker_names[] = get_the_title($s_id);
                }
            }
            $maddah_names = array();
            if (!empty($maddahs) && is_array($maddahs)) {
                foreach ($maddahs as $m_id) {
                    $maddah_names[] = get_the_title($m_id);
                }
            }
            
            $img_url = get_the_post_thumbnail_url($first_event->ID, 'large');
            if (!$img_url) $img_url = get_template_directory_uri() . '/images/poster.jpg';
            ?>

            <!-- Main details content -->
            <div class="p-5 flex flex-col sm:flex-row gap-6">
                <div class="w-full sm:w-44 h-52 rounded-large overflow-hidden flex-shrink-0 relative border border-white/5 shadow-lg">
                    <img src="<?php echo esc_url($img_url); ?>" class="w-full h-full object-cover" alt="<?php echo esc_attr($first_event->post_title); ?>">
                </div>
                <div class="flex flex-col justify-center gap-3.5 text-right">
                    <a href="<?php echo get_permalink($first_event->ID); ?>" class="hover:text-goldAccent transition-colors">
                        <h4 class="text-xl md:text-2xl font-bold text-white hover:text-goldAccent leading-relaxed"><?php echo esc_html($first_event->post_title); ?></h4>
                    </a>
                    <div class="text-sm text-textMuted flex flex-col gap-2 mt-1">
                        <?php if (!empty($speaker_names)): ?>
                        <span class="flex items-center gap-2.5"><i class="fa-solid fa-circle-user text-goldAccent/85 text-xs"></i> <span><strong class="text-white font-medium">سخنران:</strong> <?php echo esc_html(implode('، ', $speaker_names)); ?></span></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($maddah_names)): ?>
                        <span class="flex items-center gap-2.5"><i class="fa-solid fa-microphone text-goldAccent/85 text-xs"></i> <span><strong class="text-white font-medium">مداحان:</strong> <?php echo esc_html(implode('، ', $maddah_names)); ?></span></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($date) || !empty($time)): ?>
                        <span class="flex items-center gap-2.5"><i class="fa-solid fa-clock text-goldAccent/85 text-xs"></i> <span><strong class="text-white font-medium">زمان:</strong> <?php echo esc_html($date . ($time ? ' - شروع از ساعت ' . $time : '')); ?></span></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($location)): ?>
                        <span class="flex items-center gap-2.5"><i class="fa-solid fa-map-pin text-goldAccent/85 text-xs"></i> <span><strong class="text-white font-medium">مکان:</strong> <?php echo esc_html($location); ?></span></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dual Action Buttons -->
        <div class="p-4 bg-white/[0.02] border-t border-white/10 flex gap-4">
            <button id="openReminderBtn" class="flex-1 press-effect bg-goldAccent text-darkMain py-3 rounded-small font-bold text-xs md:text-sm flex items-center justify-center gap-2 shadow-[0_4px_15px_rgba(255,183,3,0.15)] hover:bg-white transition-all">
                <i class="fa-solid fa-bell text-xs"></i>
                <span class="md:hidden">ثبت یادآور</span>
                <span class="hidden md:inline">ثبت یادآور مستقیم مراسم</span>
            </button>
            <?php if (!empty($map)): ?>
            <a href="<?php echo esc_url($map); ?>" target="_blank" class="flex-1 ios-glass press-effect text-white py-3 rounded-small font-medium text-xs md:text-sm flex items-center justify-center gap-2 border border-white/10 hover:bg-white/5 transition-colors">
                <i class="fa-solid fa-location-dot text-goldAccent"></i>
                <span>مسیریابی روی نقشه</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Calendar Side card -->
    <div class="premium-card glass-hover-effect p-4 flex flex-col justify-between">
        <div>
            <div class="pb-3 border-b border-white/10 mb-4 flex items-center justify-between">
                <span class="text-sm font-bold text-white flex items-center gap-2"><?php heyat_render_section_icon('events_calendar', 'fa-list-ul', 'text-goldAccent text-base'); ?> <?php echo esc_html(get_theme_mod('title_events_calendar', 'تقویم مناسبت‌ها')); ?></span>
                <i class="fa-solid fa-calendar-alt text-sm text-textMuted"></i>
            </div>
            <div class="flex flex-col gap-3.5">
                <?php if (!empty($events_posts)): foreach($events_posts as $post): 
                    $p_date = get_post_meta($post->ID, '_heyat_event_date', true);
                    $p_speaker = get_post_meta($post->ID, '_heyat_event_speaker', true);
                    $p_maddah  = get_post_meta($post->ID, '_heyat_event_maddah', true);
                    
                    // Try to parse day and month from Persian date like 1403/05/12 or 1403-05-12
                    $day = '';
                    $month_name = '';
                    if ($p_date) {
                        // Convert Persian/Arabic digits to English digits
                        $persian_digits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                        $arabic_digits  = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
                        $english_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                        $p_date_en = str_replace($persian_digits, $english_digits, $p_date);
                        $p_date_en = str_replace($arabic_digits, $english_digits, $p_date_en);
                        $p_date_en = str_replace('-', '/', $p_date_en);
                        
                        $parts = explode('/', $p_date_en);
                        if (count($parts) >= 2) {
                            $day_part = end($parts);
                            $month_part = prev($parts);
                            
                            // Remove leading zeros for display, but keep original for month index
                            $day = ltrim($day_part, '0');
                            if (empty($day)) $day = '0';
                            
                            $months = array('', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');
                            $m_index = intval($month_part);
                            if (isset($months[$m_index])) {
                                $month_name = $months[$m_index];
                            }
                        }
                    }

                    $subtitle = '';
                    if (!empty($p_speaker) && is_array($p_speaker)) {
                        $subtitle = 'سخنرانی ' . get_the_title($p_speaker[0]);
                    } elseif (!empty($p_maddah) && is_array($p_maddah)) {
                        $subtitle = 'مداحی ' . get_the_title($p_maddah[0]);
                    }
                ?>
                <div class="flex items-center gap-3.5 p-3 rounded-large bg-white/[0.02] border border-white/5 hover:border-goldAccent/20 hover:bg-white/[0.04] transition-all duration-300">
                    <div class="w-12 h-12 rounded-large bg-goldAccent/10 text-goldAccent flex flex-col items-center justify-center text-xs font-bold border border-goldAccent/20 flex-shrink-0">
                        <span class="text-sm font-black"><?php echo esc_html($day ? $day : '-'); ?></span>
                        <span><?php echo esc_html($month_name ? $month_name : '-'); ?></span>
                    </div>
                    <div class="flex flex-col text-right">
                        <span class="text-sm font-bold text-white"><?php echo esc_html($post->post_title); ?></span>
                        <?php if ($subtitle): ?>
                        <span class="text-xs text-textMuted mt-0.5"><?php echo esc_html($subtitle); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <div class="text-center text-sm text-textMuted py-4">برنامه دیگری ثبت نشده است.</div>
                <?php endif; ?>
            </div>
        </div>
        <button class="w-full mt-4 py-3 rounded-small bg-white/[0.03] text-xs md:text-sm font-bold border border-white/10 text-textMuted hover:text-white hover:border-white/20 transition-all">
            مشاهده تقویم مذهبی سالانه
        </button>
    </div>
</div>

<!-- Reminder Modal -->
<?php
// Generate Google Calendar Link
$gcal_title = urlencode($first_event->post_title);
$gcal_details = urlencode("سخنران: " . implode('، ', $speaker_names) . "\nمداحان: " . implode('، ', $maddah_names) . "\nزمان: " . $date . " " . $time);
$gcal_location = urlencode($location);
$gcal_url = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$gcal_title}&details={$gcal_details}&location={$gcal_location}";
?>
<div id="reminderModalOverlay" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center p-4">
    <div id="reminderModal" class="bg-darkMain/95 border border-white/10 rounded-2xl p-6 w-full max-w-md scale-95 opacity-0 transition-all duration-300 transform shadow-2xl relative">
        <button id="closeReminderBtn" class="absolute top-4 left-4 text-textMuted hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-goldAccent/10 text-goldAccent rounded-full flex items-center justify-center text-2xl mx-auto mb-4 border border-goldAccent/20">
                <i class="fa-solid fa-bell"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">ثبت یادآور مراسم</h3>
            <p class="text-sm text-textMuted">از طریق پیامک یا تقویم گوگل یادآور ثبت کنید.</p>
        </div>

        <div class="flex flex-col gap-4">
            <!-- SMS Form -->
            <form id="reminderForm" class="flex flex-col gap-3">
                <input type="hidden" id="reminderEventId" value="<?php echo esc_attr($first_event->ID); ?>">
                <div class="relative">
                    <input type="tel" id="reminderMobile" placeholder="شماره موبایل (مانند 0912...)" dir="ltr" class="w-full bg-black/30 border border-white/10 rounded-lg px-4 py-3 text-white text-left placeholder-white/30 focus:outline-none focus:border-goldAccent/50 transition-colors">
                    <i class="fa-solid fa-mobile-screen absolute top-1/2 right-4 -translate-y-1/2 text-white/30"></i>
                </div>
                <button type="submit" id="reminderSubmitBtn" class="w-full bg-goldAccent text-darkMain font-bold py-3 rounded-lg hover:bg-white transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>ثبت برای یادآور پیامکی</span>
                </button>
                <div id="reminderMessage" class="text-xs text-center mt-2 hidden"></div>
            </form>

            <div class="flex items-center gap-4 py-2">
                <div class="h-px bg-white/10 flex-1"></div>
                <span class="text-xs text-textMuted font-medium">یا</span>
                <div class="h-px bg-white/10 flex-1"></div>
            </div>

            <!-- Google Calendar -->
            <a href="<?php echo esc_url($gcal_url); ?>" target="_blank" class="w-full bg-white/[0.05] border border-white/10 text-white font-medium py-3 rounded-lg hover:bg-white/10 transition-colors flex items-center justify-center gap-2">
                <i class="fa-brands fa-google text-lg"></i>
                <span>افزودن به تقویم گوگل</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openReminderBtn');
    const closeBtn = document.getElementById('closeReminderBtn');
    const modalOverlay = document.getElementById('reminderModalOverlay');
    const modalContent = document.getElementById('reminderModal');
    const form = document.getElementById('reminderForm');
    const msgDiv = document.getElementById('reminderMessage');
    const submitBtn = document.getElementById('reminderSubmitBtn');

    function openModal() {
        modalOverlay.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }

    function closeModal() {
        modalOverlay.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
    }

    if(openBtn) openBtn.addEventListener('click', openModal);
    if(closeBtn) closeBtn.addEventListener('click', closeModal);
    if(modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) closeModal();
        });
    }

    if(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const mobile = document.getElementById('reminderMobile').value;
            const eventId = document.getElementById('reminderEventId').value;

            if(!mobile || !/^09[0-9]{9}$/.test(mobile)) {
                msgDiv.innerHTML = '<span class="text-red-400">شماره موبایل معتبر نیست.</span>';
                msgDiv.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>در حال ثبت...</span>';

            const formData = new URLSearchParams();
            formData.append('action', 'heyat_add_reminder');
            formData.append('nonce', '<?php echo wp_create_nonce("heyat_reminder_nonce"); ?>');
            formData.append('event_id', eventId);
            formData.append('mobile', mobile);

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(data => {
                msgDiv.classList.remove('hidden');
                if(data.success) {
                    msgDiv.innerHTML = '<span class="text-green-400">' + data.data.message + '</span>';
                    form.reset();
                    setTimeout(closeModal, 3000);
                } else {
                    msgDiv.innerHTML = '<span class="text-red-400">' + data.data.message + '</span>';
                }
            })
            .catch(err => {
                msgDiv.classList.remove('hidden');
                msgDiv.innerHTML = '<span class="text-red-400">خطا در برقراری ارتباط با سرور.</span>';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> <span>ثبت برای یادآور پیامکی</span>';
            });
        });
    }
});
</script>

<?php 
endif; 
wp_reset_postdata(); 
?>
