<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        :root {
            --color-gold-accent: <?php echo esc_attr(get_theme_mod('heyat_color_gold', '#DFB15B')); ?>;
            --color-dark-main: <?php echo esc_attr(get_theme_mod('heyat_color_bg', '#0A0C14')); ?>;
            --color-text-main: <?php echo esc_attr(get_theme_mod('heyat_color_text_main', '#FFFFFF')); ?>;
            --color-text-muted: <?php echo esc_attr(get_theme_mod('heyat_color_text_muted', '#9CA3AF')); ?>;
            --color-red-accent: <?php echo esc_attr(get_theme_mod('heyat_color_red', '#EF4444')); ?>;
        }
        /* Override Tailwind Hardcoded Colors */
        .text-goldAccent { color: var(--color-gold-accent) !important; }
        .bg-goldAccent { background-color: var(--color-gold-accent) !important; }
        .border-goldAccent { border-color: var(--color-gold-accent) !important; }
        
        .text-darkMain { color: var(--color-dark-main) !important; }
        .bg-darkMain { background-color: var(--color-dark-main) !important; }
        body { background-color: var(--color-dark-main) !important; color: var(--color-text-main) !important; }
        
        .text-textMuted { color: var(--color-text-muted) !important; }
        
        .bg-redAccent { background-color: var(--color-red-accent) !important; }
        .text-redAccent { color: var(--color-red-accent) !important; }
        
        /* Ensure gradient background respects dynamic color (optimized with ::before to avoid repaint lag) */
        body::before {
            background: radial-gradient(circle at 50% 0%, color-mix(in srgb, var(--color-dark-main) 80%, white 20%) 0%, var(--color-dark-main) 50%, color-mix(in srgb, var(--color-dark-main) 95%, black 5%) 100%) no-repeat !important;
        }
        
        /* Floating particles / Orbs colors */
        .glass-orb.bg-goldAccent\/30 { background-color: color-mix(in srgb, var(--color-gold-accent) 30%, transparent) !important; }
        .glass-orb.bg-goldAccent\/5 { background-color: color-mix(in srgb, var(--color-gold-accent) 5%, transparent) !important; }
        .glass-orb.bg-goldAccent\/10 { background-color: color-mix(in srgb, var(--color-gold-accent) 10%, transparent) !important; }
    </style>
    <!-- PWA Setup -->
    <link rel="manifest" href="<?php echo esc_url(home_url('/?heyat_manifest=1')); ?>">
    <meta name="theme-color" content="#dfb15b">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/logo.png">
</head>
<body <?php body_class("pb-36 relative bg-darkMain text-textMain font-body overflow-x-hidden rtl"); ?>>
<?php wp_body_open(); ?>
    <!-- Background Decorative Orbs -->
    <div class="glass-orb w-[400px] h-[400px] bg-goldAccent/30 top-[-100px] left-[-100px]"></div>
    <div class="glass-orb w-[500px] h-[500px] bg-purple-600/20 bottom-[10%] right-[-150px]"
        style="animation-delay: -7s;"></div>
    <div class="glass-orb w-[350px] h-[350px] bg-amber-500/10 top-[40%] left-[20%] opacity-5"></div>

    <!-- Chosen Palette: Premium Champagne Gold & Midnight Black -->
    <!-- Application Structure Plan: Mobile-first SPA mimicking premium music apps (Spotify/iOS). Features sticky header/nav, thematic sections (Hero, Speakers, Events, Campaigns, Media, News) with horizontal scrolling on mobile and grid on desktop. Audio player is conditional and sticky. -->
    <!-- Visualization & Content Choices: Inform -> Hero & Event Cards (HTML/CSS). Compare/Change -> Charity Progress (CSS width). Organize -> Media Carousels & Grids (Tailwind Flex/Grid). Interaction -> Audio Player (JS DOM manipulation). NO SVG/Mermaid used. -->
    <!-- HANGING KATIVEH BANNER (Elegantly displays vertical logo on desktop and mobile) -->
    <header
        class="ios-glass absolute top-0 left-0 right-0 z-50 h-16 max-w-6xl mx-auto px-4 flex items-center justify-between rounded-b-large transition-all border-x border-b border-white/20 shadow-[0_4px_30px_rgba(0,0,0,0.1)] ">

        <!-- HANGING KATIVEH BANNER (Elegantly displays vertical logo on desktop and mobile) -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="hanging-kativeh glass-hover-effect">
            <?php 
            if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                $custom_logo_id = get_theme_mod( 'custom_logo' );
                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                echo '<img src="' . esc_url( $logo[0] ) . '" class="w-full h-full object-contain" alt="' . get_bloginfo( 'name' ) . '">';
            } else {
                echo '<img src="' . get_template_directory_uri() . '/images/logo.webp" class="w-full h-full object-contain" alt="لوگو عاشقان ثارالله">';
            }
            ?>
            <!-- Decorative bottom elements -->
            <div class="w-full flex justify-center items-center gap-1 mt-1 md:mt-2">
                <span class="w-1 h-1 md:w-1.5 md:h-1.5 rounded-full bg-goldAccent"></span>
                <span class="w-2 md:w-2.5 h-[1px] bg-goldAccent/40"></span>
                <span class="w-1 h-1 md:w-1.5 md:h-1.5 rounded-full bg-goldAccent"></span>
            </div>
        </a>

        <!-- Right side: Spacer for hanging logo -->
        <div class="flex items-center justify-start flex-1 md:flex-initial"></div>

        <style>
            /* Make primary menu icons slightly larger and add hover effect */
            .primary-desktop-nav-link i, .primary-desktop-nav-link svg {
                font-size: 1rem; /* text-base */
                transition: transform 0.3s ease;
            }
            .primary-desktop-nav-link:hover i, .primary-desktop-nav-link:hover svg {
                transform: scale(1.25) translateY(-2px);
            }
        </style>

        <!-- Center: Navigation Menu -->
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location'  => 'primary',
                'container'       => 'nav',
                'container_class' => 'hidden md:flex absolute right-[130px] lg:right-[150px] items-center gap-3 lg:gap-8 text-xs lg:text-sm font-medium primary-desktop-nav',
                'menu_class'      => 'flex items-center gap-3 lg:gap-8 m-0 p-0 list-none',
                'fallback_cb'     => false,
            ) );
        } else {
            // Fallback static menu if no menu is assigned
            ?>
            <nav class="hidden md:flex absolute right-[130px] lg:right-[150px] items-center gap-3 lg:gap-8 text-xs lg:text-sm font-medium primary-desktop-nav">
                <a href="#" class="text-goldAccent font-bold flex items-center gap-2 transition-all duration-300 group drop-shadow-[0_0_8px_rgba(223,177,91,0.5)]"><i class="fa-solid fa-house text-base transition-transform duration-300 group-hover:scale-125 group-hover:-translate-y-0.5"></i> خانه</a>
                <a href="#events" class="text-textMuted hover:text-goldAccent hover:scale-105 flex items-center gap-2 transition-all duration-300 group"><i class="fa-solid fa-calendar-day text-base transition-transform duration-300 group-hover:scale-125 group-hover:-translate-y-0.5"></i> مراسمات</a>
                <a href="#media" class="text-textMuted hover:text-goldAccent hover:scale-105 flex items-center gap-2 transition-all duration-300 group"><i class="fa-solid fa-compact-disc text-base transition-transform duration-300 group-hover:scale-125 group-hover:-translate-y-0.5"></i> رسانه</a>
                <a href="#news" class="text-textMuted hover:text-goldAccent hover:scale-105 flex items-center gap-2 transition-all duration-300 group"><i class="fa-solid fa-newspaper text-base transition-transform duration-300 group-hover:scale-125 group-hover:-translate-y-0.5"></i> اخبار</a>
                <a href="#campaigns" class="text-textMuted hover:text-goldAccent hover:scale-105 flex items-center gap-2 transition-all duration-300 group"><i class="fa-solid fa-hand-holding-heart text-base transition-transform duration-300 group-hover:scale-125 group-hover:-translate-y-0.5"></i> پویش‌ها</a>
            </nav>
            <?php
        }
        ?>

        <!-- Left side: Action Buttons (Optimized for Mobile) -->
        <div class="flex items-center justify-end gap-2 md:gap-3 flex-1 md:flex-initial">
            <!-- Support CTA Button -->
            <?php 
            $show_support = get_theme_mod( 'show_support_btn', true );
            $support_text = get_theme_mod( 'heyat_support_text', 'حمایت مالی' );
            $support_link = get_theme_mod( 'heyat_support_link', '#campaigns' );
            if ( $show_support && ! empty( $support_text ) ) : 
            ?>
            <a href="<?php echo esc_url( $support_link ); ?>"
                class="bg-goldAccent hover:bg-white text-darkMain font-bold text-[10px] md:text-sm px-3 md:px-4 py-2 rounded-full flex items-center gap-1.5 md:gap-2 transition-all duration-300 shadow-[0_4px_12px_rgba(223,177,91,0.15)] press-effect whitespace-nowrap">
                <i class="fa-solid fa-heart text-[10px] md:text-xs"></i>
                <span><?php echo esc_html( $support_text ); ?></span>
            </a>
            <?php endif; ?>
            
            <!-- Live Stream Button -->
            <?php 
            $show_live = get_theme_mod( 'show_live_btn', true );
            $live_text = get_theme_mod( 'heyat_live_text', 'پخش زنده' );
            $live_link = get_theme_mod( 'heyat_live_link', '#live-section' );
            if ( $show_live && ! empty( $live_text ) ) : 
            ?>
            <a href="<?php echo esc_url( $live_link ); ?>"
                class="ios-glass press-effect px-3 md:px-4 py-2 rounded-full flex items-center gap-1.5 md:gap-2 border border-white/5 text-white font-medium text-[10px] md:text-sm whitespace-nowrap">
                <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-redAccent pulse-live"></span>
                <span><?php echo esc_html( $live_text ); ?></span>
            </a>
            <?php endif; ?>
        </div>
    </header>
