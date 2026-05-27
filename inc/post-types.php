<?php
/**
 * Register Custom Post Types and Taxonomies
 *
 * @package heyat
 */

function heyat_register_post_types() {
    // 1. Persons (اشخاص)
    register_post_type('persons', array(
        'labels'      => array(
            'name'          => 'اشخاص',
            'singular_name' => 'شخص',
            'add_new'       => 'افزودن شخص جدید',
            'add_new_item'  => 'افزودن شخص جدید',
            'edit_item'     => 'ویرایش شخص',
            'all_items'     => 'همه اشخاص'
        ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array('title', 'thumbnail'),
        'menu_icon'   => 'dashicons-groups',
        'menu_position' => 6
    ));

    // 2. Events (مراسمات)
    register_post_type('events', array(
        'labels'      => array(
            'name'          => 'مراسمات',
            'singular_name' => 'مراسم',
            'add_new'       => 'افزودن مراسم',
            'add_new_item'  => 'افزودن مراسم جدید',
            'edit_item'     => 'ویرایش مراسم',
            'all_items'     => 'همه مراسمات'
        ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array('title', 'editor', 'thumbnail'),
        'menu_icon'   => 'dashicons-calendar-alt',
        'menu_position' => 5
    ));

    // 3. Campaigns (پویش‌ها)
    register_post_type('campaigns', array(
        'labels'      => array(
            'name'          => 'پویش‌ها',
            'singular_name' => 'پویش',
            'add_new'       => 'افزودن پویش',
            'add_new_item'  => 'افزودن پویش جدید',
            'edit_item'     => 'ویرایش پویش',
            'all_items'     => 'همه پویش‌ها'
        ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array('title', 'editor', 'thumbnail'),
        'menu_icon'   => 'dashicons-heart',
        'menu_position' => 7
    ));
    
    // Taxonomy for Campaigns
    register_taxonomy('campaign_category', 'campaigns', array(
        'labels'       => array('name' => 'دسته بندی پویش‌ها', 'singular_name' => 'دسته بندی پویش'),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));

    // 4. Media (رسانه -> آرشیو آثار)
    register_post_type('heyat_media', array(
        'labels'      => array(
            'name'          => 'آرشیو آثار',
            'singular_name' => 'اثر',
            'add_new'       => 'افزودن اثر',
            'add_new_item'  => 'افزودن اثر جدید',
            'edit_item'     => 'ویرایش اثر',
            'all_items'     => 'همه آثار'
        ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array('title', 'editor', 'thumbnail'),
        'menu_icon'   => 'dashicons-video-alt3',
        'menu_position' => 8
    ));

    // Taxonomies for Media
    register_taxonomy('media_format', 'heyat_media', array(
        'labels'       => array('name' => 'فرمت رسانه', 'singular_name' => 'فرمت'),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));
    register_taxonomy('media_category', 'heyat_media', array(
        'labels'       => array('name' => 'دسته بندی رسانه', 'singular_name' => 'دسته بندی'),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));

    // 5. Gallery (گزارش تصویری -> گزارش مراسمات)
    register_post_type('gallery', array(
        'labels'      => array(
            'name'          => 'گزارش مراسمات',
            'singular_name' => 'گزارش',
            'add_new'       => 'افزودن گزارش',
            'add_new_item'  => 'افزودن گزارش جدید',
            'edit_item'     => 'ویرایش گزارش',
            'all_items'     => 'همه گزارش‌ها'
        ),
        'public'      => true,
        'has_archive' => true,
        'supports'    => array('title', 'thumbnail', 'excerpt'),
        'menu_icon'   => 'dashicons-format-gallery',
        'menu_position' => 9
    ));
}
add_action('init', 'heyat_register_post_types');
