<?php
/**
 * Admin Panel Tweaks (Menu Order, Renaming, Columns)
 *
 * @package heyat
 */

// 1. Rename 'Posts' to 'اخبار'
function heyat_rename_posts_menu() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'اخبار';
    $submenu['edit.php'][5][0]  = 'همه اخبار';
    $submenu['edit.php'][10][0] = 'افزودن خبر';
}
add_action('admin_menu', 'heyat_rename_posts_menu');

function heyat_rename_posts_labels() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'اخبار';
    $labels->singular_name = 'خبر';
    $labels->add_new = 'افزودن خبر';
    $labels->add_new_item = 'افزودن خبر جدید';
    $labels->edit_item = 'ویرایش خبر';
    $labels->new_item = 'خبر جدید';
    $labels->view_item = 'نمایش خبر';
    $labels->search_items = 'جستجوی اخبار';
    $labels->not_found = 'خبری پیدا نشد';
    $labels->not_found_in_trash = 'خبری در زباله‌دان نیست';
    $labels->all_items = 'همه اخبار';
    $labels->menu_name = 'اخبار';
    $labels->name_admin_bar = 'اخبار';
}
add_action('init', 'heyat_rename_posts_labels');

// 2. Custom Menu Order
function heyat_custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
    
    return array(
        'index.php', // پیشخوان
        'edit.php?post_type=events', // مراسمات
        'edit.php?post_type=persons', // اشخاص
        'edit.php?post_type=campaigns', // پویش‌ها
        'edit.php?post_type=heyat_media', // آرشیو آثار
        'edit.php?post_type=gallery', // گزارش مراسمات
        'edit.php', // اخبار (Posts)
        'upload.php', // رسانه (Media)
        'edit-comments.php', // دیدگاه‌ها
        'edit.php?post_type=page', // برگه‌ها
        'themes.php', // نمایش
        'plugins.php', // افزونه‌ها
        'users.php', // کاربران
        'tools.php', // ابزارها
        'options-general.php', // تنظیمات
    );
}
add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', 'heyat_custom_menu_order');

// 3. Add Featured Image Columns
function heyat_add_thumbnail_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $title) {
        if ($key == 'title') {
            $new_columns['heyat_thumb'] = 'تصویر شاخص';
        }
        $new_columns[$key] = $title;
    }
    return $new_columns;
}
add_filter('manage_persons_posts_columns', 'heyat_add_thumbnail_columns');
add_filter('manage_heyat_media_posts_columns', 'heyat_add_thumbnail_columns');
add_filter('manage_gallery_posts_columns', 'heyat_add_thumbnail_columns');

function heyat_thumbnail_columns_content($column, $post_id) {
    if ($column == 'heyat_thumb') {
        if (has_post_thumbnail($post_id)) {
            echo get_the_post_thumbnail($post_id, array(50, 50), array('style' => 'border-radius:4px; max-width:50px; height:auto;'));
        } else {
            echo '<span style="color:#999;">بدون تصویر</span>';
        }
    }
}
add_action('manage_persons_posts_custom_column', 'heyat_thumbnail_columns_content', 10, 2);
add_action('manage_heyat_media_posts_custom_column', 'heyat_thumbnail_columns_content', 10, 2);
add_action('manage_gallery_posts_custom_column', 'heyat_thumbnail_columns_content', 10, 2);

function heyat_admin_thumbnail_css() {
    echo '<style>.column-heyat_thumb { width: 70px !important; text-align: center; }</style>';
}
add_action('admin_head', 'heyat_admin_thumbnail_css');

// 4. Featured Image Dimension Hints
function heyat_thumbnail_html_hint($content, $post_id, $thumbnail_id) {
    $post = get_post($post_id);
    if ($post->post_type == 'persons') {
        $content .= '<p class="description">پیشنهاد ابعاد: <strong>500x500 پیکسل</strong> (مربع)</p>';
    } elseif ($post->post_type == 'events' || $post->post_type == 'post') {
        $content .= '<p class="description">پیشنهاد ابعاد: <strong>1200x800 پیکسل</strong> (مستطیل)</p>';
    }
    return $content;
}
add_filter('admin_post_thumbnail_html', 'heyat_thumbnail_html_hint', 10, 3);
