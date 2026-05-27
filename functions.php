<?php
/**
 * Heyat Theme Functions
 * 
 * @package heyat
 */

if ( ! function_exists( 'heyat_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function heyat_theme_setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Add custom logo support
		add_theme_support( 'custom-logo', array(
			'height'      => 120,
			'width'       => 120,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Register Menus
		register_nav_menus(
			array(
				'primary'       => esc_html__( 'منوی اصلی', 'heyat' ),
				'footer'        => esc_html__( 'منوی فوتر', 'heyat' ),
				'mobile_bottom' => esc_html__( 'منوی نوار پایین موبایل', 'heyat' ),
				'mobile_drawer' => esc_html__( 'منوی کشویی موبایل', 'heyat' ),
			)
		);

		// Add theme support for HTML5 markup.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'heyat_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function heyat_scripts() {
	// FontAwesome
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/fonts/fontawesome/css/all.min.css', array(), '6.0.0' );

	// Custom CSS
	wp_enqueue_style( 'heyat-style', get_template_directory_uri() . '/css/style.css', array(), filemtime( get_template_directory() . '/css/style.css' ) );

	// Main Theme Style
	wp_enqueue_style( 'heyat-main-style', get_stylesheet_uri(), array(), '1.0.0' );

	// Tailwind Stylesheet (Statically Compiled for performance)
	wp_enqueue_style( 'tailwind-style', get_template_directory_uri() . '/css/tailwind.css', array(), filemtime( get_template_directory() . '/css/tailwind.css' ) );
}
add_action( 'wp_enqueue_scripts', 'heyat_scripts' );

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Menu Icons addition.
 */
require get_template_directory() . '/inc/menu-icons.php';

/**
 * Custom Post Types & Taxonomies.
 */
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/reminders-admin.php';

if ( is_admin() ) {
    require_once get_template_directory() . '/inc/admin-setup.php';
}

// AJAX Handler for Reminders
add_action( 'wp_ajax_heyat_add_reminder', 'heyat_ajax_add_reminder' );
add_action( 'wp_ajax_nopriv_heyat_add_reminder', 'heyat_ajax_add_reminder' );

/**
 * PWA Manifest & Service Worker Endpoints
 */
add_action('template_redirect', function() {
    if (isset($_GET['heyat_sw'])) {
        header('Content-Type: application/javascript; charset=utf-8');
        header('Service-Worker-Allowed: /');
        echo "
        const CACHE_NAME = 'heyat-cache-v1';
        self.addEventListener('install', (e) => { self.skipWaiting(); });
        self.addEventListener('activate', (e) => { e.waitUntil(self.clients.claim()); });
        self.addEventListener('fetch', (e) => {
            // Simple network-first strategy for a basic PWA
            e.respondWith(
                fetch(e.request).catch(() => caches.match(e.request))
            );
        });
        ";
        exit;
    }
    if (isset($_GET['heyat_manifest'])) {
        header('Content-Type: application/manifest+json; charset=utf-8');
        
        $logo_url = get_template_directory_uri() . "/images/logo.png";
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
            if ($logo) $logo_url = $logo[0];
        }

        echo json_encode(array(
            "name" => get_bloginfo('name'),
            "short_name" => "هیئت",
            "start_url" => "/?source=pwa",
            "display" => "standalone",
            "background_color" => "#121212",
            "theme_color" => "#dfb15b",
            "icons" => array(
                array(
                    "src" => $logo_url,
                    "sizes" => "512x512",
                    "type" => "image/png",
                    "purpose" => "any maskable"
                )
            )
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
});

function heyat_ajax_add_reminder() {
    check_ajax_referer('heyat_reminder_nonce', 'nonce');

    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : '';

    if ($event_id <= 0 || empty($mobile)) {
        wp_send_json_error(array('message' => 'اطلاعات ناقص است.'));
    }

    if (!preg_match('/^09[0-9]{9}$/', $mobile)) {
        wp_send_json_error(array('message' => 'شماره موبایل نامعتبر است (مانند: 09121234567).'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'heyat_reminders';
    
    // Check if table exists, if not create it just in case
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        heyat_create_reminders_table();
    }

    // Check if already registered
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE event_id = %d AND mobile = %s",
        $event_id,
        $mobile
    ));

    if ($exists) {
        wp_send_json_error(array('message' => 'این شماره قبلاً برای این مراسم ثبت شده است.'));
    }

    $inserted = $wpdb->insert(
        $table_name,
        array(
            'event_id' => $event_id,
            'mobile'   => $mobile,
            'created_at' => current_time('mysql')
        ),
        array('%d', '%s', '%s')
    );

    if ($inserted) {
        wp_send_json_success(array('message' => 'شماره شما با موفقیت ثبت شد.'));
    } else {
        wp_send_json_error(array('message' => 'خطایی در ثبت اطلاعات رخ داد.'));
    }
}

/**
 * Data Import (Run once).
 */
require get_template_directory() . '/inc/data-import.php';
require get_template_directory() . '/inc/data-import-2.php';

/**
 * Filter nav menu link attributes to add Tailwind classes
 */
function heyat_nav_menu_link_attributes( $atts, $item, $args ) {
    if ( $args->theme_location == 'primary' ) {
        $is_active = in_array( 'current-menu-item', $item->classes ) || in_array( 'current_page_item', $item->classes );
        if ( $is_active ) {
            $atts['class'] = 'text-goldAccent font-bold flex items-center gap-2 transition-all duration-300 group primary-desktop-nav-link drop-shadow-[0_0_8px_rgba(223,177,91,0.5)]';
        } else {
            $atts['class'] = 'text-textMuted hover:text-goldAccent hover:scale-105 flex items-center gap-2 transition-all duration-300 group primary-desktop-nav-link';
        }
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'heyat_nav_menu_link_attributes', 10, 3 );

/**
 * Render section icon (FontAwesome, SVG code, or SVG upload)
 */
function heyat_render_section_icon($section_id, $default_fa = '', $extra_classes = '') {
    $type = get_theme_mod("icon_type_{$section_id}", 'fontawesome');
    
    if ($type === 'svg_upload') {
        $url = get_theme_mod("icon_svg_upload_{$section_id}");
        if ($url) {
            echo '<img src="' . esc_url($url) . '" class="w-full h-full object-contain filter-goldAccent ' . esc_attr($extra_classes) . '" alt="icon">';
            return;
        }
    } elseif ($type === 'svg_code') {
        $code = get_theme_mod("icon_svg_code_{$section_id}");
        if ($code) {
            // If code has <svg>, we might want to add extra classes but it's hard with raw HTML.
            // We'll wrap it in a span with extra classes.
            echo '<span class="flex items-center justify-center ' . esc_attr($extra_classes) . '">' . $code . '</span>';
            return;
        }
    }
    
    // Default fallback to FontAwesome
    $fa_class = get_theme_mod("icon_{$section_id}", $default_fa);
    echo '<i class="fa-solid ' . esc_attr($fa_class) . ' ' . esc_attr($extra_classes) . '"></i>';
}

