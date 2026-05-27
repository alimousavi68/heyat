<?php
/**
 * Add Custom Icon Field to Nav Menu Items
 *
 * @package heyat
 */

// 1. نمایش فیلد در بخش مدیریت منوها (پیشخوان وردپرس)
function heyat_nav_menu_item_custom_fields( $item_id, $item, $depth, $args, $id ) {
    $icon_class = get_post_meta( $item_id, '_heyat_menu_icon', true );
    ?>
    <p class="field-heyat-menu-icon description description-wide">
        <label for="edit-menu-item-heyat-icon-<?php echo esc_attr( $item_id ); ?>">
            <?php _e( 'کلاس آیکون FontAwesome (مثال: fa-solid fa-house)', 'heyat' ); ?><br />
            <input type="text" id="edit-menu-item-heyat-icon-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-custom" name="heyat_menu_icon[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $icon_class ); ?>" placeholder="fa-solid fa-house" />
        </label>
    </p>
    <?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'heyat_nav_menu_item_custom_fields', 10, 5 );

// 2. ذخیره کردن مقدار فیلد
function heyat_save_nav_menu_item_custom_fields( $menu_id, $menu_item_db_id, $args ) {
    if ( isset( $_POST['heyat_menu_icon'][ $menu_item_db_id ] ) ) {
        $icon_class = sanitize_text_field( $_POST['heyat_menu_icon'][ $menu_item_db_id ] );
        update_post_meta( $menu_item_db_id, '_heyat_menu_icon', $icon_class );
    } else {
        delete_post_meta( $menu_item_db_id, '_heyat_menu_icon' );
    }
}
add_action( 'wp_update_nav_menu_item', 'heyat_save_nav_menu_item_custom_fields', 10, 3 );

// 3. نمایش آیکون در کنار عنوان منو در سایت (Frontend)
function heyat_nav_menu_item_title( $title, $item, $args, $depth ) {
    $icon_class = get_post_meta( $item->ID, '_heyat_menu_icon', true );
    
    if ( ! empty( $icon_class ) ) {
        // ترکیب تگ i با کلاس آیکون و عنوان اصلی منو
        $icon_html = '<i class="' . esc_attr( $icon_class ) . ' text-xs"></i> ';
        $title = $icon_html . $title;
    }
    
    return $title;
}
add_filter( 'nav_menu_item_title', 'heyat_nav_menu_item_title', 10, 4 );
