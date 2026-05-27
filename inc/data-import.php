<?php
function heyat_run_one_time_import() {
    if (get_option('heyat_persons_imported_v2')) return;

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $persons = array(
        array('name' => 'حاج محمود کریمی', 'image' => 'maddah-profile-2f50ff0f-9084-446d-9026-e2a584b0a240.webp', 'role' => 'maddah'),
        array('name' => 'کربلایی علی رضایی', 'image' => 'maddah-profile-e2d67c9a-671d-45f4-9c0e-9df1788cdf6f.jpg', 'role' => 'maddah'),
        array('name' => 'استاد علوی', 'image' => 'maddah-profile-4b55da67-658d-42a7-aeb8-476df7466035.webp', 'role' => 'speaker'),
        array('name' => 'سید مهدی حسینی', 'image' => 'maddah-profile-54935a47-1b9c-486a-b7b8-ce5998bc7671.jpg', 'role' => 'maddah'),
        array('name' => 'استاد پناهی', 'image' => 'maddah-profile-a78b2336-c2a6-40b2-9252-6f40166b2948.jpg', 'role' => 'speaker'),
        array('name' => 'حاج رضا طاهری', 'image' => 'mad-4.webp', 'role' => 'maddah'),
        array('name' => 'کربلایی حسین طاهری', 'image' => 'maddah-profile-9ed7ef5e-245c-4f6e-b554-1b645243d481.webp', 'role' => 'maddah'),
        array('name' => 'سید مجید بنی‌فاطمه', 'image' => 'mad-5.jpg', 'role' => 'maddah'),
    );

    foreach ($persons as $p) {
        $existing = get_page_by_title($p['name'], OBJECT, 'persons');
        if ($existing) continue;

        $post_id = wp_insert_post(array(
            'post_title' => $p['name'],
            'post_type'  => 'persons',
            'post_status'=> 'publish',
        ));
        
        if (is_wp_error($post_id)) continue;
        update_post_meta($post_id, '_heyat_person_role', $p['role']);

        $image_path = get_template_directory() . '/images/' . $p['image'];
        if (file_exists($image_path)) {
            $tmp = '/tmp/wp_sideload_' . uniqid() . '_' . $p['image'];
            copy($image_path, $tmp);
            
            $file_array = array('name' => $p['image'], 'tmp_name' => $tmp);
            $id = media_handle_sideload($file_array, $post_id);
            if (!is_wp_error($id)) set_post_thumbnail($post_id, $id);
        }
    }

    update_option('heyat_persons_imported_v2', true);
}
add_action('admin_init', 'heyat_run_one_time_import');
