<?php
// data-import-2.php

function heyat_run_second_data_import() {
    if (get_option('heyat_data_imported_v3')) return;

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $ai_image_1 = '/Users/user/.gemini/antigravity-ide/brain/4b80ab43-fe65-47d6-9c2a-a7b2dfa1574a/heyat_event_1_1779527920621.png';
    $ai_image_2 = '/Users/user/.gemini/antigravity-ide/brain/4b80ab43-fe65-47d6-9c2a-a7b2dfa1574a/heyat_event_2_1779527970789.png';
    $uploaded_images = array();

    // Helper to upload image
    $upload_img = function($path) {
        if (!file_exists($path)) return false;
        $tmp = '/tmp/wp_sideload_' . uniqid() . '_' . basename($path);
        copy($path, $tmp);
        $file_array = array('name' => basename($path), 'tmp_name' => $tmp);
        $id = media_handle_sideload($file_array, 0);
        return is_wp_error($id) ? false : $id;
    };

    $img_id_1 = $upload_img($ai_image_1);
    $img_id_2 = $upload_img($ai_image_2);

    $persons = get_posts(array('post_type' => 'persons', 'posts_per_page' => -1));
    $person_ids = wp_list_pluck($persons, 'ID');
    
    // ----------------------------------------------------
    // 1. Events (10 posts)
    // ----------------------------------------------------
    $event_titles = array(
        'مراسم هفتگی زیارت عاشورا',
        'اقامه عزا سالروز وفات ام‌البنین',
        'جشن بزرگ غدیر خم',
        'مراسم احیای شب‌های قدر',
        'سوگواری دهه اول محرم',
        'جشن میلاد امام رضا (ع)',
        'قرائت دعای کمیل شب جمعه',
        'ویژه برنامه ولادت حضرت زینب (س)',
        'مراسم دعای ندبه صبح جمعه',
        'اقامه سوگواری ایام شهادت حضرت زهرا (س)' // The main one
    );

    foreach ($event_titles as $index => $title) {
        $post_id = wp_insert_post(array('post_title' => $title, 'post_type' => 'events', 'post_status'=> 'publish'));
        if (!is_wp_error($post_id)) {
            // Set random dates
            $days_offset = $index - 5; // some past, some future
            $date = date('Y-m-d', strtotime("$days_offset days"));
            $time = '21:00';
            
            update_post_meta($post_id, '_heyat_event_date', $date);
            update_post_meta($post_id, '_heyat_event_time', $time);
            update_post_meta($post_id, '_heyat_event_location', 'تهران، حسینیه بزرگ ثارالله (ع)');
            
            if (!empty($person_ids)) {
                update_post_meta($post_id, '_heyat_event_speaker', $person_ids[array_rand($person_ids)]);
                update_post_meta($post_id, '_heyat_event_maddah', $person_ids[array_rand($person_ids)]);
            }

            if ($index % 2 == 0 && $img_id_1) set_post_thumbnail($post_id, $img_id_1);
            else if ($img_id_2) set_post_thumbnail($post_id, $img_id_2);
        }
    }

    // ----------------------------------------------------
    // 2. Campaigns (4 posts)
    // ----------------------------------------------------
    $campaigns = array(
        array('title' => 'پویش همگانی تهیه جهیزیه نوعروسان نیازمند', 'cat' => 'جهیزیه', 'target' => 50000000, 'col' => 39000000, 'p' => 312),
        array('title' => 'پویش همدلی مومنانه (ارزاق)', 'cat' => 'ارزاق', 'target' => 30000000, 'col' => 13500000, 'p' => 184),
        array('title' => 'پویش نذر آب برای مناطق محروم', 'cat' => 'عمرانی', 'target' => 100000000, 'col' => 85000000, 'p' => 450),
        array('title' => 'آزادی زندانیان جرائم غیرعمد', 'cat' => 'اجتماعی', 'target' => 200000000, 'col' => 200000000, 'p' => 890),
    );

    foreach ($campaigns as $c) {
        $post_id = wp_insert_post(array('post_title' => $c['title'], 'post_type' => 'campaigns', 'post_status'=> 'publish'));
        if (!is_wp_error($post_id)) {
            wp_set_object_terms($post_id, $c['cat'], 'campaign_category');
            update_post_meta($post_id, '_heyat_campaign_target', $c['target']);
            update_post_meta($post_id, '_heyat_campaign_collected', $c['col']);
            update_post_meta($post_id, '_heyat_campaign_participants', $c['p']);
            update_post_meta($post_id, '_heyat_campaign_link', 'https://zarinp.al/heyat');
        }
    }

    // ----------------------------------------------------
    // 3. Media (10 posts)
    // ----------------------------------------------------
    $media_cats = array('سخنرانی', 'سرود', 'شور', 'روضه', 'زمینه');
    for ($i=1; $i<=10; $i++) {
        $post_id = wp_insert_post(array('post_title' => 'فایل رسانه نمونه شماره ' . $i, 'post_type' => 'heyat_media', 'post_status'=> 'publish'));
        if (!is_wp_error($post_id)) {
            $cat = $media_cats[array_rand($media_cats)];
            wp_set_object_terms($post_id, $cat, 'media_category');
            wp_set_object_terms($post_id, 'صوتی', 'media_format');
            
            // Add repeater files
            $files = array(
                array('quality' => '128kbps', 'duration' => '10:00', 'url' => 'https://example.com/audio1.mp3'),
                array('quality' => '320kbps', 'duration' => '10:00', 'url' => 'https://example.com/audio2.mp3')
            );
            update_post_meta($post_id, '_heyat_media_files', $files);

            if ($img_id_1) set_post_thumbnail($post_id, $img_id_1);
        }
    }

    // ----------------------------------------------------
    // 4. Gallery (3 posts)
    // ----------------------------------------------------
    for ($i=1; $i<=3; $i++) {
        $post_id = wp_insert_post(array('post_title' => 'گزارش تصویری مراسم شماره ' . $i, 'post_excerpt' => 'چکیده گزارش مراسم...', 'post_type' => 'gallery', 'post_status'=> 'publish'));
        if (!is_wp_error($post_id)) {
            if ($img_id_2) set_post_thumbnail($post_id, $img_id_2);
            if ($img_id_1 && $img_id_2) {
                update_post_meta($post_id, '_heyat_gallery_images', "$img_id_1,$img_id_2");
            }
        }
    }

    update_option('heyat_data_imported_v3', true);
}
add_action('admin_init', 'heyat_run_second_data_import');
