<?php
/**
 * Register Custom Meta Boxes
 *
 * @package heyat
 */

function heyat_add_custom_meta_boxes() {
    // 1. Slider Meta Box
    $slider_screens = array('post', 'events', 'heyat_media');
    foreach ($slider_screens as $screen) {
        add_meta_box('heyat_slider_meta', 'تنظیمات اسلایدر صفحه اصلی', 'heyat_slider_meta_callback', $screen, 'normal', 'high');
    }

    // 2. Person Role (Moved to 'normal' context)
    add_meta_box('heyat_person_meta', 'اطلاعات شخص', 'heyat_person_meta_callback', 'persons', 'normal', 'high');

    // 3. Event Details
    add_meta_box('heyat_event_meta', 'اطلاعات مراسم', 'heyat_event_meta_callback', 'events', 'normal', 'high');

    // 4. Campaign Details
    add_meta_box('heyat_campaign_meta', 'اطلاعات پویش', 'heyat_campaign_meta_callback', 'campaigns', 'normal', 'high');

    // 5. Media Details (Repeater)
    add_meta_box('heyat_media_meta', 'فایل‌های رسانه (صوت/تصویر)', 'heyat_media_meta_callback', 'heyat_media', 'normal', 'high');

    // 6. Gallery Details
    add_meta_box('heyat_gallery_meta', 'گالری تصاویر', 'heyat_gallery_meta_callback', 'gallery', 'normal', 'high');
}
add_action('add_meta_boxes', 'heyat_add_custom_meta_boxes');

// Enqueue Admin Scripts for Uploader
function heyat_admin_scripts() {
    global $typenow;
    $allowed_types = array('post', 'events', 'heyat_media', 'gallery', 'campaigns', 'persons');
    
    if (in_array($typenow, $allowed_types)) {
        wp_enqueue_media();
        
        $deps = array('jquery');
        
        // Load Select2 and Persian Datepicker only for events
        if ($typenow == 'events') {
            wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
            wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), null, true);
            
            // Local persian-datepicker files
            wp_enqueue_style('persian-datepicker-css', get_template_directory_uri() . '/css/persian-datepicker.min.css');
            wp_enqueue_script('persian-date-js', get_template_directory_uri() . '/js/vendor/persian-date.min.js', array('jquery'), null, true);
            wp_enqueue_script('persian-datepicker-js', get_template_directory_uri() . '/js/vendor/persian-datepicker.min.js', array('jquery', 'persian-date-js'), null, true);
            
            $deps[] = 'select2-js';
            $deps[] = 'persian-datepicker-js';
        }

        wp_enqueue_script('heyat-admin-js', get_template_directory_uri() . '/js/admin-scripts.js', $deps, filemtime(get_template_directory() . '/js/admin-scripts.js'), true);
        
        // Basic CSS for repeater and gallery
        wp_add_inline_style('wp-admin', "
            .heyat-repeater-row { background:#f9f9f9; border:1px solid #ddd; padding:15px; margin-bottom:10px; display:flex; gap:10px; align-items:center; }
            .heyat-repeater-row input { flex:1; }
            .heyat-gallery-preview { display:flex; gap:10px; flex-wrap:wrap; margin-top:15px; }
            .heyat-gallery-preview img { width:100px; height:100px; object-fit:cover; border:1px solid #ccc; border-radius:4px; }
            .select2-container .select2-selection--single { height: 40px !important; display: flex; align-items: center; }
            .select2-selection__arrow { top: 7px !important; }
            .select2-results__option { display: flex; align-items: center; gap: 10px; }
            .select2-results__option img { width: 30px; height: 30px; object-fit: cover; border-radius: 50%; }
            .select2-selection__rendered img { width: 24px; height: 24px; object-fit: cover; border-radius: 50%; display: inline-block; vertical-align: middle; margin-left: 8px; }
        ");
    }
}
add_action('admin_enqueue_scripts', 'heyat_admin_scripts');

/* ==========================================================================
   Callbacks
   ========================================================================== */

// 1. Slider Callback
function heyat_slider_meta_callback($post) {
    wp_nonce_field('heyat_slider_save', 'heyat_slider_nonce');
    $is_in_slider = get_post_meta($post->ID, '_heyat_is_in_slider', true);
    $slider_img   = get_post_meta($post->ID, '_heyat_slider_image', true);
    $slider_btn   = get_post_meta($post->ID, '_heyat_slider_btn_text', true);
    $slider_link  = get_post_meta($post->ID, '_heyat_slider_btn_link', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="heyat_is_in_slider" value="1" <?php checked($is_in_slider, '1'); ?> />
            <strong>نمایش این پست در اسلایدر اصلی سایت</strong>
        </label>
    </p>
    <div style="margin-top: 15px;">
        <label style="display:block; margin-bottom:5px;">تصویر عریض اسلایدر:</label>
        <div style="display:flex; gap:10px; align-items:center;">
            <input type="text" id="heyat_slider_image_url" name="heyat_slider_image" value="<?php echo esc_attr($slider_img); ?>" style="width:70%;" />
            <button type="button" class="button heyat-upload-btn" data-target="#heyat_slider_image_url">انتخاب از گالری وردپرس</button>
        </div>
        <p class="description">پیشنهاد ابعاد: <strong>1920x1080 پیکسل</strong></p>
    </div>
    <div style="margin-top: 15px; display:flex; gap:20px;">
        <div style="flex:1;">
            <label style="display:block; margin-bottom:5px;">متن دکمه (مثال: مشاهده کامل):</label>
            <input type="text" name="heyat_slider_btn_text" value="<?php echo esc_attr($slider_btn); ?>" style="width:100%;" />
        </div>
        <div style="flex:1;">
            <label style="display:block; margin-bottom:5px;">لینک دکمه (اختیاری - پیش‌فرض لینک همین صفحه است):</label>
            <input type="url" name="heyat_slider_btn_link" value="<?php echo esc_attr($slider_link); ?>" style="width:100%;" placeholder="https://..." />
        </div>
    </div>
    <?php
}

// 2. Person Role Callback
function heyat_person_meta_callback($post) {
    wp_nonce_field('heyat_person_save', 'heyat_person_nonce');
    $role = get_post_meta($post->ID, '_heyat_person_role', true);
    if (empty($role)) $role = 'maddah';
    ?>
    <p><strong>نقش شخص را انتخاب کنید:</strong></p>
    <label style="margin-left:20px;"><input type="radio" name="heyat_person_role" value="maddah" <?php checked($role, 'maddah'); ?>> مداح</label>
    <label><input type="radio" name="heyat_person_role" value="speaker" <?php checked($role, 'speaker'); ?>> سخنران</label>
    <?php
}

// 3. Event Details Callback
function heyat_event_meta_callback($post) {
    wp_nonce_field('heyat_event_save', 'heyat_event_nonce');
    $date     = get_post_meta($post->ID, '_heyat_event_date', true);
    $time     = get_post_meta($post->ID, '_heyat_event_time', true);
    $location = get_post_meta($post->ID, '_heyat_event_location', true);
    $map      = get_post_meta($post->ID, '_heyat_event_map', true);
    $speaker  = get_post_meta($post->ID, '_heyat_event_speaker', true);
    $maddah   = get_post_meta($post->ID, '_heyat_event_maddah', true);
    
    // Ensure they are arrays for multi-select
    if (!is_array($speaker)) $speaker = empty($speaker) ? array() : array($speaker);
    if (!is_array($maddah)) $maddah = empty($maddah) ? array() : array($maddah);

    $persons = get_posts(array('post_type' => 'persons', 'posts_per_page' => -1));
    ?>
    <table style="width:100%; border-spacing: 0 15px;">
        <tr>
            <td style="width:20%;"><label>زمان برگزاری:</label></td>
            <td>
                <div style="display:flex; gap:10px; align-items:center;">
                    <div style="flex:1;">
                        <input type="text" id="heyat_event_date_picker" name="heyat_event_date" value="<?php echo esc_attr($date); ?>" style="width:100%; direction:ltr; text-align:left;" placeholder="تاریخ (مثال: 1403/05/12)" title="تاریخ برگزاری" />
                    </div>
                    <div style="width:120px;">
                        <input type="time" name="heyat_event_time" value="<?php echo esc_attr($time); ?>" style="width:100%; direction:ltr; text-align:left;" title="ساعت برگزاری" />
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><label>مکان (آدرس متنی):</label></td>
            <td><input type="text" name="heyat_event_location" value="<?php echo esc_attr($location); ?>" style="width:100%;" /></td>
        </tr>
        <tr>
            <td><label>لینک مسیریابی (نشان/گوگل):</label></td>
            <td><input type="url" name="heyat_event_map" value="<?php echo esc_attr($map); ?>" style="width:100%; direction:ltr; text-align:left;" /></td>
        </tr>
        <tr>
            <td><label>سخنران مراسم:</label></td>
            <td>
                <select class="heyat-select2-person" name="heyat_event_speaker[]" multiple="multiple" style="width:100%;">
                    <?php foreach ($persons as $p) : 
                        $role = get_post_meta($p->ID, '_heyat_person_role', true);
                        if ($role == 'speaker' || empty($role)) :
                            $img_url = get_the_post_thumbnail_url($p->ID, 'thumbnail');
                            if (!$img_url) $img_url = 'https://via.placeholder.com/50';
                            $selected = in_array($p->ID, $speaker) ? 'selected="selected"' : '';
                    ?>
                        <option value="<?php echo $p->ID; ?>" data-image="<?php echo esc_url($img_url); ?>" <?php echo $selected; ?>><?php echo esc_html($p->post_title); ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>مداح مراسم:</label></td>
            <td>
                <select class="heyat-select2-person" name="heyat_event_maddah[]" multiple="multiple" style="width:100%;">
                    <?php foreach ($persons as $p) : 
                        $role = get_post_meta($p->ID, '_heyat_person_role', true);
                        if ($role == 'maddah' || empty($role)) :
                            $img_url = get_the_post_thumbnail_url($p->ID, 'thumbnail');
                            if (!$img_url) $img_url = 'https://via.placeholder.com/50';
                            $selected = in_array($p->ID, $maddah) ? 'selected="selected"' : '';
                    ?>
                        <option value="<?php echo $p->ID; ?>" data-image="<?php echo esc_url($img_url); ?>" <?php echo $selected; ?>><?php echo esc_html($p->post_title); ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// 4. Campaign Details Callback
function heyat_campaign_meta_callback($post) {
    wp_nonce_field('heyat_campaign_save', 'heyat_campaign_nonce');
    $target      = get_post_meta($post->ID, '_heyat_campaign_target', true);
    $collected   = get_post_meta($post->ID, '_heyat_campaign_collected', true);
    $participants= get_post_meta($post->ID, '_heyat_campaign_participants', true);
    $pay_link    = get_post_meta($post->ID, '_heyat_campaign_link', true);
    ?>
    <table style="width:100%; border-spacing: 0 15px;">
        <tr>
            <td style="width:20%;"><label>مبلغ هدف (تومان):</label></td>
            <td><input type="number" name="heyat_campaign_target" value="<?php echo esc_attr($target); ?>" style="width:100%;" /></td>
        </tr>
        <tr>
            <td><label>مبلغ جمع‌آوری شده (تومان):</label></td>
            <td><input type="number" name="heyat_campaign_collected" value="<?php echo esc_attr($collected); ?>" style="width:100%;" /></td>
        </tr>
        <tr>
            <td><label>تعداد مشارکت‌کنندگان:</label></td>
            <td><input type="number" name="heyat_campaign_participants" value="<?php echo esc_attr($participants); ?>" style="width:100%;" /></td>
        </tr>
        <tr>
            <td><label>لینک پرداخت اختصاصی:</label></td>
            <td><input type="url" name="heyat_campaign_link" value="<?php echo esc_attr($pay_link); ?>" style="width:100%;" /></td>
        </tr>
    </table>
    <?php
}

// 5. Media Details (Repeater) Callback
function heyat_media_meta_callback($post) {
    wp_nonce_field('heyat_media_save', 'heyat_media_nonce');
    $files = get_post_meta($post->ID, '_heyat_media_files', true);
    if (!is_array($files)) $files = array();
    ?>
    <div id="heyat-media-repeater">
        <?php if (!empty($files)) : foreach ($files as $index => $file) : ?>
            <div class="heyat-repeater-row">
                <input type="text" name="heyat_media_quality[]" value="<?php echo esc_attr($file['quality']); ?>" placeholder="کیفیت (مثال: 128kbps یا 1080p)" />
                <input type="text" name="heyat_media_duration[]" value="<?php echo esc_attr($file['duration']); ?>" placeholder="مدت زمان (مثال: 12:45)" />
                <input type="text" name="heyat_media_url[]" class="media-url-input" value="<?php echo esc_attr($file['url']); ?>" placeholder="آدرس فایل" />
                <button type="button" class="button heyat-repeater-upload">انتخاب فایل</button>
                <button type="button" class="button button-danger heyat-repeater-remove" style="color:red; border-color:red;">حذف</button>
            </div>
        <?php endforeach; else : ?>
            <div class="heyat-repeater-row">
                <input type="text" name="heyat_media_quality[]" value="" placeholder="کیفیت (مثال: 128kbps یا 1080p)" />
                <input type="text" name="heyat_media_duration[]" value="" placeholder="مدت زمان (مثال: 12:45)" />
                <input type="text" name="heyat_media_url[]" class="media-url-input" value="" placeholder="آدرس فایل" />
                <button type="button" class="button heyat-repeater-upload">انتخاب فایل</button>
                <button type="button" class="button button-danger heyat-repeater-remove" style="color:red; border-color:red;">حذف</button>
            </div>
        <?php endif; ?>
    </div>
    <button type="button" id="heyat-add-media-row" class="button button-primary" style="margin-top:10px;">افزودن فایل جدید</button>
    <?php
}

// 6. Gallery Meta Callback
function heyat_gallery_meta_callback($post) {
    wp_nonce_field('heyat_gallery_save', 'heyat_gallery_nonce');
    $gallery_ids = get_post_meta($post->ID, '_heyat_gallery_images', true);
    ?>
    <p>
        <input type="hidden" id="heyat_gallery_images_input" name="heyat_gallery_images" value="<?php echo esc_attr($gallery_ids); ?>" />
        <button type="button" id="heyat_gallery_upload_btn" class="button button-primary">انتخاب تصاویر از گالری وردپرس (چندتایی)</button>
        <button type="button" id="heyat_gallery_clear_btn" class="button">پاک کردن گالری</button>
    </p>
    <div id="heyat_gallery_preview" class="heyat-gallery-preview">
        <?php
        if (!empty($gallery_ids)) {
            $ids = explode(',', $gallery_ids);
            foreach ($ids as $id) {
                $img = wp_get_attachment_image_src($id, 'thumbnail');
                if ($img) {
                    echo '<img src="' . esc_url($img[0]) . '" alt="" />';
                }
            }
        }
        ?>
    </div>
    <?php
}

/* ==========================================================================
   Save Routines
   ========================================================================== */

function heyat_save_custom_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // 1. Slider
    if (isset($_POST['heyat_slider_nonce']) && wp_verify_nonce($_POST['heyat_slider_nonce'], 'heyat_slider_save')) {
        $is_in_slider = isset($_POST['heyat_is_in_slider']) ? '1' : '0';
        update_post_meta($post_id, '_heyat_is_in_slider', $is_in_slider);
        
        if (isset($_POST['heyat_slider_image'])) update_post_meta($post_id, '_heyat_slider_image', esc_url_raw($_POST['heyat_slider_image']));
        if (isset($_POST['heyat_slider_btn_text'])) update_post_meta($post_id, '_heyat_slider_btn_text', sanitize_text_field($_POST['heyat_slider_btn_text']));
        if (isset($_POST['heyat_slider_btn_link'])) update_post_meta($post_id, '_heyat_slider_btn_link', esc_url_raw($_POST['heyat_slider_btn_link']));
    }

    // 2. Person
    if (isset($_POST['heyat_person_nonce']) && wp_verify_nonce($_POST['heyat_person_nonce'], 'heyat_person_save')) {
        if (isset($_POST['heyat_person_role'])) update_post_meta($post_id, '_heyat_person_role', sanitize_text_field($_POST['heyat_person_role']));
    }

    // 3. Event
    if (isset($_POST['heyat_event_nonce']) && wp_verify_nonce($_POST['heyat_event_nonce'], 'heyat_event_save')) {
        $fields = array('heyat_event_date', 'heyat_event_time', 'heyat_event_location', 'heyat_event_map');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
        
        // Multi-select arrays
        $arrays = array('heyat_event_speaker', 'heyat_event_maddah');
        foreach ($arrays as $arr) {
            if (isset($_POST[$arr]) && is_array($_POST[$arr])) {
                $sanitized = array_map('sanitize_text_field', $_POST[$arr]);
                update_post_meta($post_id, '_' . $arr, $sanitized);
            } else {
                update_post_meta($post_id, '_' . $arr, array());
            }
        }
    }

    // 4. Campaign
    if (isset($_POST['heyat_campaign_nonce']) && wp_verify_nonce($_POST['heyat_campaign_nonce'], 'heyat_campaign_save')) {
        $fields = array('heyat_campaign_target', 'heyat_campaign_collected', 'heyat_campaign_participants', 'heyat_campaign_link');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // 5. Media Repeater
    if (isset($_POST['heyat_media_nonce']) && wp_verify_nonce($_POST['heyat_media_nonce'], 'heyat_media_save')) {
        $files = array();
        if (isset($_POST['heyat_media_url']) && is_array($_POST['heyat_media_url'])) {
            $count = count($_POST['heyat_media_url']);
            for ($i = 0; $i < $count; $i++) {
                if (!empty($_POST['heyat_media_url'][$i])) {
                    $files[] = array(
                        'quality'  => sanitize_text_field($_POST['heyat_media_quality'][$i]),
                        'duration' => sanitize_text_field($_POST['heyat_media_duration'][$i]),
                        'url'      => esc_url_raw($_POST['heyat_media_url'][$i])
                    );
                }
            }
        }
        update_post_meta($post_id, '_heyat_media_files', $files);
    }

    // 6. Gallery
    if (isset($_POST['heyat_gallery_nonce']) && wp_verify_nonce($_POST['heyat_gallery_nonce'], 'heyat_gallery_save')) {
        if (isset($_POST['heyat_gallery_images'])) {
            $gallery_ids = sanitize_text_field($_POST['heyat_gallery_images']);
            update_post_meta($post_id, '_heyat_gallery_images', $gallery_ids);
        }
    }
}
add_action('save_post', 'heyat_save_custom_meta');
