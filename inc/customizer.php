<?php
/**
 * Heyat Theme Customizer
 *
 * @package heyat
 */

function heyat_customize_register( $wp_customize ) {
	// ==========================================
	// 1. COLOR PANEL (Task 8)
	// ==========================================
	$wp_customize->add_section( 'heyat_colors_section', array(
		'title'       => __( 'رنگ‌بندی قالب', 'heyat' ),
		'priority'    => 20,
	) );

	$colors = array(
		'heyat_color_gold' => array('default' => '#DFB15B', 'label' => 'رنگ اصلی (Accent)'),
		'heyat_color_bg' => array('default' => '#0A0C14', 'label' => 'رنگ پس‌زمینه (Background)'),
		'heyat_color_text_main' => array('default' => '#FFFFFF', 'label' => 'رنگ متون اصلی (Text Main)'),
		'heyat_color_text_muted' => array('default' => '#9CA3AF', 'label' => 'رنگ متون فرعی (Text Muted)'),
		'heyat_color_red' => array('default' => '#EF4444', 'label' => 'رنگ هشدار/زنده (Red Accent)'),
	);

	foreach($colors as $id => $data) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data['default'],
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
			'label'    => $data['label'],
			'section'  => 'heyat_colors_section',
		) ) );
	}

	// ==========================================
	// 2. HEADER SECTION (Task 9)
	// ==========================================
	$wp_customize->add_section( 'heyat_header_section', array(
		'title'       => __( 'تنظیمات هدر (دکمه‌ها)', 'heyat' ),
		'priority'    => 30,
	) );

	// Support CTA Toggle
	$wp_customize->add_setting( 'show_support_btn', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control( 'show_support_btn', array( 'label' => __( 'نمایش دکمه حمایت مالی', 'heyat' ), 'section' => 'heyat_header_section', 'type' => 'checkbox' ) );
	
	// Support CTA Text
	$wp_customize->add_setting( 'heyat_support_text', array(
		'default'           => 'حمایت مالی',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'heyat_support_text', array(
		'label'    => __( 'متن دکمه حمایت مالی', 'heyat' ),
		'section'  => 'heyat_header_section',
		'type'     => 'text',
	) );

	// Support CTA Link
	$wp_customize->add_setting( 'heyat_support_link', array(
		'default'           => '#campaigns',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'heyat_support_link', array(
		'label'    => __( 'لینک دکمه حمایت مالی', 'heyat' ),
		'section'  => 'heyat_header_section',
		'type'     => 'url',
	) );

	// Live Stream Toggle
	$wp_customize->add_setting( 'show_live_btn', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control( 'show_live_btn', array( 'label' => __( 'نمایش دکمه پخش زنده', 'heyat' ), 'section' => 'heyat_header_section', 'type' => 'checkbox' ) );

	// Live Stream CTA Text
	$wp_customize->add_setting( 'heyat_live_text', array(
		'default'           => 'پخش زنده',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'heyat_live_text', array(
		'label'    => __( 'متن دکمه پخش زنده', 'heyat' ),
		'section'  => 'heyat_header_section',
		'type'     => 'text',
	) );

	// Live Stream CTA Link
	$wp_customize->add_setting( 'heyat_live_link', array(
		'default'           => '#live-section',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'heyat_live_link', array(
		'label'    => __( 'لینک دکمه پخش زنده', 'heyat' ),
		'section'  => 'heyat_header_section',
		'type'     => 'url',
	) );

	// ==========================================
	// 3. HOME PAGE PANEL (Tasks 1, 2, 3, 4)
	// ==========================================
	$wp_customize->add_panel( 'heyat_home_panel', array(
		'priority'       => 40,
		'title'          => __( 'تنظیمات صفحه اصلی', 'heyat' ),
	) );

	// Helper for adding standard section fields
	$add_section_fields = function($section_id, $defaults) use ($wp_customize) {
        // Remove 'heyat_' prefix for setting keys so it matches template parts
        $prefix = str_replace('heyat_', '', $section_id);

		// Toggle
		$wp_customize->add_setting( "show_{$prefix}", array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
		$wp_customize->add_control( "show_{$prefix}", array( 'label' => 'نمایش این بخش', 'section' => $section_id, 'type' => 'checkbox' ) );
		// Title
		if(isset($defaults['title'])) {
			$wp_customize->add_setting( "title_{$prefix}", array( 'default' => $defaults['title'], 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( "title_{$prefix}", array( 'label' => 'عنوان بخش', 'section' => $section_id, 'type' => 'text' ) );
		}
		// Subtitle
		if(isset($defaults['subtitle'])) {
			$wp_customize->add_setting( "subtitle_{$prefix}", array( 'default' => $defaults['subtitle'], 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( "subtitle_{$prefix}", array( 'label' => 'زیرتیتر بخش', 'section' => $section_id, 'type' => 'text' ) );
		}
		// Icon Type
		if(isset($defaults['icon'])) {
			$wp_customize->add_setting( "icon_type_{$prefix}", array( 'default' => 'fontawesome', 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( "icon_type_{$prefix}", array( 
				'label' => 'نوع آیکون', 
				'section' => $section_id, 
				'type' => 'radio',
				'choices' => array(
					'fontawesome' => 'کلاس FontAwesome',
					'svg_code' => 'کد SVG سفارشی',
					'svg_upload' => 'آپلود فایل آیکون'
				)
			) );

			$wp_customize->add_setting( "icon_{$prefix}", array( 'default' => $defaults['icon'], 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( "icon_{$prefix}", array( 'label' => 'کلاس آیکون (در صورت انتخاب FontAwesome)', 'section' => $section_id, 'type' => 'text' ) );

			$wp_customize->add_setting( "icon_svg_code_{$prefix}", array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
			$wp_customize->add_control( "icon_svg_code_{$prefix}", array( 'label' => 'کد SVG (در صورت انتخاب SVG سفارشی)', 'section' => $section_id, 'type' => 'textarea' ) );

			$wp_customize->add_setting( "icon_svg_upload_{$prefix}", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "icon_svg_upload_{$prefix}", array(
				'label'    => 'آپلود آیکون (در صورت انتخاب آپلود)',
				'section'  => $section_id,
			) ) );
		}
		// Count
		if(isset($defaults['count'])) {
			$wp_customize->add_setting( "count_{$prefix}", array( 'default' => $defaults['count'], 'sanitize_callback' => 'absint' ) );
			$wp_customize->add_control( "count_{$prefix}", array( 'label' => 'تعداد نمایش', 'section' => $section_id, 'type' => 'number' ) );
		}
	};

	// --- Hero Section ---
	$wp_customize->add_section( 'heyat_hero_section', array( 'title' => 'بخش هیرو (اسلایدر)', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_hero_section', array('count' => 5));

	// --- Speakers Section ---
	$wp_customize->add_section( 'heyat_speakers_section', array( 'title' => 'بخش مادحین', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_speakers_section', array(
		'title' => 'مادحین و سخنرانان', 
		'subtitle' => 'ذاکرین و سخنرانان برجسته و میهمانان هیئت ثارالله', 
		'icon' => 'fa-microphone-lines', 
		'count' => -1
	));

	// --- Events Section --- (Has two boxes)
	$wp_customize->add_section( 'heyat_events_section', array( 'title' => 'بخش مراسمات', 'panel' => 'heyat_home_panel' ) );
	$wp_customize->add_setting( 'show_heyat_events_section', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control( 'show_heyat_events_section', array( 'label' => 'نمایش کل بخش مراسمات', 'section' => 'heyat_events_section', 'type' => 'checkbox' ) );
	
	$wp_customize->add_setting( 'title_events_upcoming', array( 'default' => 'برنامه پیش‌رو هیئت', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'title_events_upcoming', array( 'label' => 'عنوان برنامه بعدی', 'section' => 'heyat_events_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'icon_type_events_upcoming', array( 'default' => 'fontawesome', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'icon_type_events_upcoming', array( 'label' => 'نوع آیکون برنامه بعدی', 'section' => 'heyat_events_section', 'type' => 'radio', 'choices' => array('fontawesome' => 'FontAwesome', 'svg_code' => 'SVG Code', 'svg_upload' => 'Upload') ) );
	$wp_customize->add_setting( 'icon_events_upcoming', array( 'default' => 'fa-calendar-check', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'icon_events_upcoming', array( 'label' => 'آیکون برنامه بعدی', 'section' => 'heyat_events_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'icon_svg_code_events_upcoming', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'icon_svg_code_events_upcoming', array( 'label' => 'کد SVG برنامه بعدی', 'section' => 'heyat_events_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'icon_svg_upload_events_upcoming', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'icon_svg_upload_events_upcoming', array('label' => 'آپلود آیکون برنامه بعدی', 'section' => 'heyat_events_section' ) ) );
	
	$wp_customize->add_setting( 'title_events_calendar', array( 'default' => 'تقویم مناسبت‌ها', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'title_events_calendar', array( 'label' => 'عنوان تقویم', 'section' => 'heyat_events_section', 'type' => 'text' ) );
	
	$wp_customize->add_setting( 'icon_type_events_calendar', array( 'default' => 'fontawesome', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'icon_type_events_calendar', array( 'label' => 'نوع آیکون تقویم', 'section' => 'heyat_events_section', 'type' => 'radio', 'choices' => array('fontawesome' => 'FontAwesome', 'svg_code' => 'SVG Code', 'svg_upload' => 'Upload') ) );
	$wp_customize->add_setting( 'icon_events_calendar', array( 'default' => 'fa-list-ul', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'icon_events_calendar', array( 'label' => 'آیکون تقویم', 'section' => 'heyat_events_section', 'type' => 'text' ) );
	$wp_customize->add_setting( 'icon_svg_code_events_calendar', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'icon_svg_code_events_calendar', array( 'label' => 'کد SVG تقویم', 'section' => 'heyat_events_section', 'type' => 'textarea' ) );
	$wp_customize->add_setting( 'icon_svg_upload_events_calendar', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'icon_svg_upload_events_calendar', array('label' => 'آپلود آیکون تقویم', 'section' => 'heyat_events_section' ) ) );
	
	$wp_customize->add_setting( 'count_heyat_events_section', array( 'default' => 4, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'count_heyat_events_section', array( 'label' => 'تعداد مراسمات تقویم', 'section' => 'heyat_events_section', 'type' => 'number' ) );


	// --- Campaigns Section ---
	$wp_customize->add_section( 'heyat_campaigns_section', array( 'title' => 'بخش پویش‌ها', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_campaigns_section', array(
		'title' => 'پویش‌های در حال اجرا', 
		'subtitle' => 'بستر همدلی، موسسه خیریه', 
		'icon' => 'fa-hand-holding-heart', 
		'count' => 3
	));

	// --- Media Section ---
	$wp_customize->add_section( 'heyat_media_section', array( 'title' => 'بخش رسانه و آثار', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_media_section', array(
		'title' => 'جدیدترین آثار منتشر شده', 
		'subtitle' => 'آرشیو جدیدترین صوت‌ها و ویدیوها', 
		'icon' => 'fa-compact-disc', 
		'count' => 10
	));

	// --- Speeches Section ---
	$wp_customize->add_section( 'heyat_speeches_section', array( 'title' => 'بخش سخنرانی‌ها', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_speeches_section', array(
		'title' => 'سخنرانی‌های برگزیده', 
		'subtitle' => 'مباحث معرفتی، فلسفی و اخلاقی اساتید بنام', 
		'icon' => 'fa-book-open', 
		'count' => 6
	));

	// --- Gallery Section ---
	$wp_customize->add_section( 'heyat_gallery_section', array( 'title' => 'بخش گالری', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_gallery_section', array(
		'title' => 'گزارش‌های تصویری', 
		'subtitle' => 'مستندنگاری تصویری و ثبت جلوه‌های معنوی و هنری جلسات ثارالله', 
		'icon' => 'fa-images', 
		'count' => 4
	));

	// --- News Section ---
	$wp_customize->add_section( 'heyat_news_section', array( 'title' => 'بخش اخبار', 'panel' => 'heyat_home_panel' ) );
	$add_section_fields('heyat_news_section', array(
		'title' => 'اخبار و اطلاعیه‌ها', 
		'subtitle' => 'مطالب علمی، گزارش کارها و بیانیه‌های رسمی هیئت', 
		'icon' => 'fa-newspaper', 
		'count' => 3
	));

	// ==========================================
	// 4. SOCIAL MEDIA PANEL (Task 5)
	// ==========================================
	$wp_customize->add_section( 'heyat_social_section', array(
		'title'       => __( 'شبکه‌های اجتماعی (فوتر)', 'heyat' ),
		'priority'    => 50,
	) );

	$socials = array(
		'eitaa' => 'ایتا',
		'telegram' => 'تلگرام',
		'instagram' => 'اینستاگرام',
		'aparat' => 'آپارات',
	);

	foreach($socials as $id => $label) {
		$wp_customize->add_setting( "heyat_social_{$id}", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( "heyat_social_{$id}", array( 'label' => "لینک $label", 'section' => 'heyat_social_section', 'type' => 'url' ) );
	}
}
add_action( 'customize_register', 'heyat_customize_register' );
