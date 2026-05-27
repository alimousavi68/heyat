<?php
/**
 * Heyat Event Reminders Admin functionality
 */

// 1. Create Database Table
function heyat_create_reminders_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'heyat_reminders';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        event_id bigint(20) NOT NULL,
        mobile varchar(20) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
add_action( 'after_switch_theme', 'heyat_create_reminders_table' );
// Also run on init to ensure it's there (cached so it doesn't hurt)
add_action( 'init', 'heyat_check_reminders_table' );
function heyat_check_reminders_table() {
    if ( ! get_option( 'heyat_reminders_table_created' ) ) {
        heyat_create_reminders_table();
        update_option( 'heyat_reminders_table_created', true );
    }
}

// 2. Add Submenu Page
function heyat_reminders_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=events',
        'یادآورها',
        'یادآورهای پیامکی',
        'manage_options',
        'heyat-reminders',
        'heyat_reminders_admin_page'
    );
}
add_action( 'admin_menu', 'heyat_reminders_admin_menu' );

// 3. Render Admin Page
function heyat_reminders_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'heyat_reminders';
    
    // Check if table exists (safety)
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        heyat_create_reminders_table();
    }

    // Get filter (if any)
    $event_filter = isset($_GET['event_filter']) ? intval($_GET['event_filter']) : 0;
    
    $where = "1=1";
    if ($event_filter > 0) {
        $where .= $wpdb->prepare(" AND event_id = %d", $event_filter);
    }
    
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE $where ORDER BY created_at DESC");
    
    // Get all events for dropdown
    $events = get_posts(array(
        'post_type' => 'events',
        'numberposts' => -1,
        'post_status' => 'any'
    ));
    ?>
    <div class="wrap" style="font-family: Tahoma, Arial, sans-serif;">
        <h1 class="wp-heading-inline">مدیریت شماره‌های یادآور پیامکی</h1>
        <hr class="wp-header-end">
        
        <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px;">
            <form method="GET" action="edit.php">
                <input type="hidden" name="post_type" value="events">
                <input type="hidden" name="page" value="heyat-reminders">
                
                <div class="tablenav top">
                    <div class="alignleft actions">
                        <select name="event_filter">
                            <option value="0">همه مراسمات</option>
                            <?php foreach ($events as $evt): ?>
                                <option value="<?php echo esc_attr($evt->ID); ?>" <?php selected($event_filter, $evt->ID); ?>>
                                    <?php echo esc_html($evt->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" class="button" value="فیلتر">
                        
                        <a href="<?php echo admin_url('admin-post.php?action=export_heyat_reminders&event_id=' . $event_filter); ?>" class="button button-primary" style="margin-right: 10px;">
                            خروجی گرفتن (CSV)
                        </a>
                    </div>
                </div>
            </form>
            
            <table class="wp-list-table widefat fixed striped table-view-list" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th style="width: 50px;">ردیف</th>
                        <th>مراسم</th>
                        <th>شماره موبایل</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">هیچ یادآوری ثبت نشده است.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($results as $index => $row): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><strong><?php echo esc_html(get_the_title($row->event_id)); ?></strong></td>
                            <td style="font-family: monospace; font-size: 14px;"><?php echo esc_html($row->mobile); ?></td>
                            <td dir="ltr" style="text-align: right;"><?php echo esc_html($row->created_at); ?></td>
                            <td>
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=delete_heyat_reminder&id=' . $row->id), 'delete_reminder_' . $row->id); ?>" class="button button-small" style="color: #a00; border-color: #a00;" onclick="return confirm('آیا از حذف این شماره مطمئن هستید؟');">حذف</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

// 4. Handle CSV Export
function heyat_export_reminders_csv() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'شما اجازه دسترسی ندارید.' );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'heyat_reminders';
    $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
    
    $where = "1=1";
    if ($event_id > 0) {
        $where .= $wpdb->prepare(" AND event_id = %d", $event_id);
    }
    
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE $where ORDER BY created_at DESC", ARRAY_A);
    
    $filename = 'heyat-reminders-export-' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    // Add BOM for proper UTF-8 Excel support
    fputs($output, "\xEF\xBB\xBF");
    
    fputcsv($output, array('شناسه', 'نام مراسم', 'شماره موبایل', 'تاریخ ثبت'));
    
    if (!empty($results)) {
        foreach ($results as $row) {
            $event_name = get_the_title($row['event_id']);
            fputcsv($output, array(
                $row['id'],
                $event_name,
                $row['mobile'],
                $row['created_at']
            ));
        }
    }
    
    fclose($output);
    exit;
}
add_action( 'admin_post_export_heyat_reminders', 'heyat_export_reminders_csv' );

// 5. Handle Delete Action
function heyat_delete_reminder_action() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'شما اجازه دسترسی ندارید.' );
    }
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    check_admin_referer('delete_reminder_' . $id);
    
    if ($id > 0) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'heyat_reminders';
        $wpdb->delete($table_name, array('id' => $id), array('%d'));
    }
    
    wp_redirect( admin_url('edit.php?post_type=events&page=heyat-reminders') );
    exit;
}
add_action( 'admin_post_delete_heyat_reminder', 'heyat_delete_reminder_action' );
