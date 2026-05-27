        <footer
            class="reveal-on-scroll mt-16 pt-8 pb-12 border-t border-white/5 text-center flex flex-col items-center gap-6">

            <!-- Static PWA Install Button in Footer -->
            <button id="static-pwa-install-btn" class="hidden mb-2 ios-glass px-5 py-2 rounded-full border border-goldAccent/20 text-goldAccent text-xs font-bold flex items-center gap-2 hover:bg-goldAccent hover:text-darkMain transition-all cursor-pointer shadow-lg press-effect">
                <i class="fa-solid fa-download"></i> نصب اپلیکیشن هیئت روی گوشی
            </button>

            <!-- Social Media premium links (Enlarged and Bolder) -->
            <div class="flex gap-5">
                <?php 
                $eitaa = get_theme_mod('heyat_social_eitaa', '');
                $telegram = get_theme_mod('heyat_social_telegram', '');
                $instagram = get_theme_mod('heyat_social_instagram', '');
                $aparat = get_theme_mod('heyat_social_aparat', '');
                
                if ( !empty($eitaa) ) : ?>
                <a class="w-12 h-12 rounded-full ios-glass flex items-center justify-center text-goldAccent press-effect border border-goldAccent/20 hover:border-goldAccent hover:bg-goldAccent hover:text-darkMain hover:scale-110 transition-all duration-300 text-lg group"
                    href="<?php echo esc_url($eitaa); ?>" target="_blank">
                    <i class="fa-solid fa-paper-plane transition-transform duration-300 group-hover:-translate-y-0.5"></i>
                </a>
                <?php endif; 
                
                if ( !empty($telegram) ) : ?>
                <a class="w-12 h-12 rounded-full ios-glass flex items-center justify-center text-goldAccent press-effect border border-goldAccent/20 hover:border-goldAccent hover:bg-goldAccent hover:text-darkMain hover:scale-110 transition-all duration-300 text-lg group"
                    href="<?php echo esc_url($telegram); ?>" target="_blank">
                    <i class="fa-brands fa-telegram transition-transform duration-300 group-hover:-translate-y-0.5"></i>
                </a>
                <?php endif;
                
                if ( !empty($instagram) ) : ?>
                <a class="w-12 h-12 rounded-full ios-glass flex items-center justify-center text-goldAccent press-effect border border-goldAccent/20 hover:border-goldAccent hover:bg-goldAccent hover:text-darkMain hover:scale-110 transition-all duration-300 text-lg group"
                    href="<?php echo esc_url($instagram); ?>" target="_blank">
                    <i class="fa-brands fa-instagram transition-transform duration-300 group-hover:-translate-y-0.5"></i>
                </a>
                <?php endif;
                
                if ( !empty($aparat) ) : ?>
                <a class="w-12 h-12 rounded-full ios-glass flex items-center justify-center text-goldAccent press-effect border border-goldAccent/20 hover:border-goldAccent hover:bg-goldAccent hover:text-darkMain hover:scale-110 transition-all duration-300 text-lg group"
                    href="<?php echo esc_url($aparat); ?>" target="_blank">
                    <i class="fa-solid fa-circle-play transition-transform duration-300 group-hover:-translate-y-0.5"></i>
                </a>
                <?php endif; ?>
            </div>

            <div class="text-xs text-textMuted mt-4 flex flex-col gap-1.5 font-medium">
                <span>تمامی حقوق مادی و معنوی محفوظ و متعلق به واحد رسانه هیئت عاشقان ثارالله (ع) می‌باشد.</span>
                <span class="mt-2 text-[10px] opacity-70">طراحی و توسعه: <a href="https://ihasht.ir" target="_blank" class="text-goldAccent hover:text-white transition-colors">گروه هشت بهشت</a></span>
            </div>
        </footer>

    </main>

    <!-- FANTASY BACK TO TOP BUTTON -->
    <button id="backToTopBtn"
        class="ios-glass fixed bottom-20 left-4 md:bottom-6 md:left-6 w-11 h-11 rounded-full z-50 flex items-center justify-center text-goldAccent border border-goldAccent/20 shadow-[0_0_15px_rgba(0,0,0,0.5)] opacity-0 pointer-events-none translate-y-4 transition-all duration-300 hover:border-goldAccent hover:text-white">
        <i class="fa-solid fa-arrow-up text-sm"></i>
    </button>

    <!-- 3. CONDITIONAL STICKY AUDIO PLAYER: FULL VIEWPORT WIDTH with LTR timeline, post link and timers (Task 1) -->
    <div id="stickyPlayer"
        class="ios-glass fixed bottom-16 md:bottom-0 left-0 right-0 w-full z-45 overflow-hidden transition-all duration-500 transform translate-y-44 opacity-0 pointer-events-none rounded-t-large md:rounded-t-none border-t border-white/10 shadow-2xl">
        <!-- Seekbar / Timeline - Left to Right (Task 1) -->
        <div class="w-full h-[4px] bg-white/5 relative cursor-pointer" id="progressBarContainer">
            <div id="playerProgress" class="h-full bg-goldAccent shadow-[0_0_10px_#DFB15B] transition-all duration-150"
                style="width: 0%;"></div>
        </div>

        <!-- Player Content Grid aligned to maximum responsive container -->
        <div
            class="max-w-6xl mx-auto px-4 py-3.5 flex flex-col md:flex-row items-center justify-between gap-3.5 w-full">

            <!-- Left Side: Cover & Titles -->
            <div class="flex items-center gap-3 overflow-hidden w-full md:w-auto">
                <img id="playerCover" src=""
                    class="w-12 h-12 rounded-small object-cover flex-shrink-0 border border-white/5"
                    alt="کاور در حال پخش">
                <div class="flex flex-col overflow-hidden text-right">
                    <span id="playerTitle" class="text-sm font-bold text-white truncate w-44 md:w-64">عنوان قطعه</span>
                    <span id="playerArtist" class="text-xs text-textMuted block mt-0.5 font-medium">مداح / گوینده</span>
                </div>
            </div>

            <!-- Central Seek Timers Left-to-Right layout (Task 1) -->
            <div class="flex items-center gap-3 w-full md:w-1/3 justify-between md:justify-center text-[11px] text-textMuted font-mono"
                dir="ltr">
                <span id="currentTime">00:00</span>
                <span class="text-white/10 hidden md:inline">|</span>
                <span id="totalTime">05:20</span>
            </div>

            <!-- Right Side: Media Controls & CTA Post Link (Task 1) -->
            <div
                class="flex items-center justify-between md:justify-end gap-5 w-full md:w-auto border-t border-white/5 md:border-none pt-3 md:pt-0">
                <!-- CTA link to original post -->
                <a id="postLink" href="#media"
                    class="text-xs font-bold text-goldAccent flex items-center gap-1.5 hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>مشاهده پست</span>
                </a>

                <div class="flex items-center gap-4">
                    <button onclick="toggleAudio()"
                        class="w-10 h-10 rounded-full bg-goldAccent text-darkMain flex items-center justify-center text-xs shadow-md hover:scale-105 transition-transform">
                        <i id="playBtnIcon" class="fa-solid fa-pause"></i>
                    </button>
                    <button onclick="closeAudio()" class="text-textMuted hover:text-white press-effect text-sm p-1">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- STICKY BOTTOM NAVIGATION BAR (Mobile Only Active item highlighted) -->
    <nav
        class="ios-glass fixed bottom-0 left-0 right-0 z-50 h-16 flex items-center justify-around rounded-t-large md:hidden">
        <?php
        $locations = get_nav_menu_locations();
        $has_bottom_menu = false;
        if ( isset( $locations['mobile_bottom'] ) ) {
            $menu = wp_get_nav_menu_object( $locations['mobile_bottom'] );
            if ( $menu ) {
                $has_bottom_menu = true;
                $menu_items = wp_get_nav_menu_items( $menu->term_id );
                if ( $menu_items ) {
                    foreach ( $menu_items as $count => $item ) {
                        if ( $count >= 4 ) break; // Max 4 items + 1 hamburger
                        
                        // Extract icon classes, remove empty ones
                        $classes = is_array($item->classes) ? array_filter($item->classes) : array();
                        $icon_class = !empty($classes) ? implode(' ', $classes) : 'fa-solid fa-circle';
                        
                        echo '<a href="' . esc_url( $item->url ) . '" class="flex flex-col items-center justify-center gap-1 text-textMuted hover:text-goldAccent transition-all press-effect">';
                        echo '<i class="' . esc_attr( $icon_class ) . ' text-lg"></i>';
                        echo '<span class="text-[9px] font-bold">' . esc_html( $item->title ) . '</span>';
                        echo '</a>';
                    }
                }
            }
        }
        
        if ( !$has_bottom_menu ) : 
        ?>
        <!-- Tab: Home -->
        <a href="<?php echo home_url('/'); ?>" class="flex flex-col items-center justify-center gap-1 text-goldAccent transition-all press-effect">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[9px] font-bold">خانه</span>
        </a>

        <!-- Tab: Events -->
        <a href="<?php echo home_url('/#events'); ?>"
            class="flex flex-col items-center justify-center gap-1 text-textMuted hover:text-goldAccent transition-all press-effect">
            <i class="fa-solid fa-calendar-day text-lg"></i>
            <span class="text-[9px] font-bold">مراسمات</span>
        </a>

        <!-- Tab: Media -->
        <a href="<?php echo home_url('/#media'); ?>"
            class="flex flex-col items-center justify-center gap-1 text-textMuted hover:text-goldAccent transition-all press-effect">
            <i class="fa-solid fa-compact-disc text-lg"></i>
            <span class="text-[9px] font-bold">رسانه</span>
        </a>

        <!-- Tab: Campaigns -->
        <a href="<?php echo home_url('/#campaigns'); ?>"
            class="flex flex-col items-center justify-center gap-1 text-textMuted hover:text-goldAccent transition-all press-effect">
            <i class="fa-solid fa-hand-holding-heart text-lg"></i>
            <span class="text-[9px] font-bold">پویش‌ها</span>
        </a>
        <?php endif; ?>

        <!-- Tab: Menu (Hamburger) -->
        <button id="openDrawerBtn"
            class="flex flex-col items-center justify-center gap-1 text-textMuted hover:text-goldAccent transition-all press-effect">
            <i class="fa-solid fa-bars-staggered text-lg"></i>
            <span class="text-[9px] font-bold">منو</span>
        </button>
    </nav>

    <!-- MOBILE DRAWER MENU (Task 6) -->
    <div id="drawerOverlay" class="drawer-overlay"></div>
    <aside id="mobileDrawer" class="mobile-drawer">
        <div class="flex flex-col h-full p-6">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-goldAccent/10 border border-goldAccent/20 flex items-center justify-center">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.webp" class="w-6 h-6 object-contain" alt="Logo">
                    </div>
                    <span class="text-white font-bold">منوی دسترسی</span>
                </div>
                <button id="closeDrawerBtn" class="text-textMuted hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <nav class="flex flex-col gap-6">
                <?php
                $has_drawer_menu = false;
                if ( isset( $locations['mobile_drawer'] ) ) {
                    $drawer_menu = wp_get_nav_menu_object( $locations['mobile_drawer'] );
                    if ( $drawer_menu ) {
                        $has_drawer_menu = true;
                        $drawer_items = wp_get_nav_menu_items( $drawer_menu->term_id );
                        if ( $drawer_items ) {
                            foreach ( $drawer_items as $item ) {
                                $classes = is_array($item->classes) ? array_filter($item->classes) : array();
                                $icon_class = !empty($classes) ? implode(' ', $classes) : 'fa-solid fa-circle-dot';
                                
                                echo '<a href="' . esc_url( $item->url ) . '" class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">';
                                echo '<i class="' . esc_attr( $icon_class ) . ' w-6 text-center"></i>';
                                echo '<span>' . esc_html( $item->title ) . '</span>';
                                echo '</a>';
                            }
                        }
                    }
                }
                
                if ( !$has_drawer_menu ) :
                ?>
                <a href="<?php echo home_url('/'); ?>" class="drawer-link flex items-center gap-4 text-goldAccent font-bold">
                    <i class="fa-solid fa-house w-6 text-center"></i>
                    <span>صفحه اصلی</span>
                </a>
                <a href="<?php echo home_url('/#events'); ?>"
                    class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-calendar-day w-6 text-center"></i>
                    <span>برنامه مراسمات</span>
                </a>
                <a href="<?php echo home_url('/#media'); ?>"
                    class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-compact-disc w-6 text-center"></i>
                    <span>آرشیو رسانه‌ای</span>
                </a>
                <a href="<?php echo home_url('/#campaigns'); ?>"
                    class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-hand-holding-heart w-6 text-center"></i>
                    <span>حمایت از پویش‌ها</span>
                </a>
                <a href="<?php echo get_post_type_archive_link('gallery'); ?>"
                    class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-images w-6 text-center"></i>
                    <span>گزارش تصویری</span>
                </a>
                <a href="<?php echo get_post_type_archive_link('persons'); ?>"
                    class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-microphone-lines w-6 text-center"></i>
                    <span>مادحین و سخنرانان</span>
                </a>
                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>"
                    class="drawer-link flex items-center gap-4 text-white/70 hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-newspaper w-6 text-center"></i>
                    <span>اخبار و اطلاعیه‌ها</span>
                </a>
                <?php endif; ?>
            </nav>

            <div class="mt-auto pt-10">
                <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                    <p class="text-[10px] text-textMuted leading-relaxed text-center">
                        تمامی حقوق برای رسانه عاشقان ثارالله محفوظ است.<br>طراحی شده با عشق برای اهل‌بیت (ع)
                    </p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Slider & Carousel Initialization Script -->
    <script>
        const state = {
            isPlaying: false,
            interval: null,
            audioDuration: 320, // 5:20 in seconds
            audioProgress: 0,
            progressInterval: null
        };

        // 8. Mobile Drawer Logic (Task 6)
        function initMobileDrawer() {
            const openBtn = document.getElementById('openDrawerBtn');
            const closeBtn = document.getElementById('closeDrawerBtn');
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('drawerOverlay');
            const links = document.querySelectorAll('.drawer-link');

            function toggleDrawer(isOpen) {
                if (isOpen) {
                    drawer.classList.add('open');
                    overlay.classList.add('open');
                    document.body.style.overflow = 'hidden';
                } else {
                    drawer.classList.remove('open');
                    overlay.classList.remove('open');
                    document.body.style.overflow = '';
                }
            }

            openBtn?.addEventListener('click', () => toggleDrawer(true));
            closeBtn?.addEventListener('click', () => toggleDrawer(false));
            overlay?.addEventListener('click', () => toggleDrawer(false));

            links.forEach(link => {
                link.addEventListener('click', () => toggleDrawer(false));
            });
        }

        // 5. Hero Slider Logic with Manual Buttons & Class Transition
        function initHeroSlider(intervalTime = 6000) {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            const prevBtn = document.getElementById('heroPrevBtn');
            const nextBtn = document.getElementById('heroNextBtn');
            let currentIndex = 0;
            let timer = null;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    if (i === index) {
                        slide.classList.add('active');
                    } else {
                        slide.classList.remove('active');
                    }
                });

                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('bg-goldAccent', 'w-6');
                        dot.classList.remove('bg-white/30', 'w-2');
                    } else {
                        dot.classList.add('bg-white/30', 'w-2');
                        dot.classList.remove('bg-goldAccent', 'w-6');
                    }
                });
                currentIndex = index;
            }

            function nextSlide() {
                let next = (currentIndex + 1) % slides.length;
                showSlide(next);
            }

            function prevSlide() {
                let prev = (currentIndex - 1 + slides.length) % slides.length;
                showSlide(prev);
            }

            function resetTimer() {
                clearInterval(timer);
                timer = setInterval(nextSlide, intervalTime);
            }

            timer = setInterval(nextSlide, intervalTime);

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    resetTimer();
                });
            });

            nextBtn?.addEventListener('click', () => {
                nextSlide();
                resetTimer();
            });

            prevBtn?.addEventListener('click', () => {
                prevSlide();
                resetTimer();
            });
        }

        // 4. Unified Fantasy Carousel Helper with Autoplay & Permanent Controls
        function initFantasyCarousel(carouselId, autoplayInterval = 5000) {
            const carousel = document.getElementById(carouselId);
            if (!carousel) return;
            const track = carousel.querySelector('.carousel-track');
            // Look for buttons in the carousel or its parent section
            const parent = carousel.closest('section') || carousel.parentElement;
            const prevBtn = carousel.querySelector('.carousel-prev') || parent.querySelector('.carousel-prev');
            const nextBtn = carousel.querySelector('.carousel-next') || parent.querySelector('.carousel-next');

            if (!track) return;

            let index = 0;

            function getVisibleCount() {
                if (carouselId === 'speakersCarousel') {
                    if (window.innerWidth >= 1024) return 6;
                    if (window.innerWidth >= 768) return 4;
                    return 2.5;
                }
                if (window.innerWidth >= 1024) return 4;
                if (window.innerWidth >= 768) return 3;
                return 1.6;
            }

            function updateSlider() {
                const items = track.children;
                if (items.length === 0) return;
                const itemWidth = items[0].getBoundingClientRect().width;
                const gap = 16;
                const visibleCount = getVisibleCount();
                const maxIndex = items.length - Math.floor(visibleCount);

                if (index > maxIndex) {
                    index = 0;
                } else if (index < 0) {
                    index = Math.max(0, maxIndex);
                }

                // Handle RTL: TranslateX needs to be adjusted based on direction
                const offset = index * (itemWidth + gap);
                track.style.transform = `translateX(${offset}px)`;
            }

            nextBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                index++;
                updateSlider();
                resetAutoplay();
            });

            prevBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                index--;
                updateSlider();
                resetAutoplay();
            });

            let timer = setInterval(() => {
                index++;
                updateSlider();
            }, autoplayInterval);

            function resetAutoplay() {
                clearInterval(timer);
                timer = setInterval(() => {
                    index++;
                    updateSlider();
                }, autoplayInterval);
            }

            window.addEventListener('resize', updateSlider);
            setTimeout(updateSlider, 150);
        }

        // Reveal on Scroll Logic
        function initRevealOnScroll() {
            const observerOptions = {
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-on-scroll').forEach(section => {
                observer.observe(section);
            });
        }

        // Kativeh Drop Animation with Delay
        function initKativehAnimation() {
            const kativeh = document.querySelector('.hanging-kativeh');
            if (kativeh) {
                setTimeout(() => {
                    kativeh.classList.add('kativeh-drop-in');
                }, 500);
            }
        }

        // 7. Back to Top Button Logic
        function initBackToTop() {
            const btn = document.getElementById('backToTopBtn');
            if (!btn) return;

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    btn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                    btn.classList.add('opacity-100', 'translate-y-0');
                } else {
                    btn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                    btn.classList.remove('opacity-100', 'translate-y-0');
                }
            }, { passive: true });

            btn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Format Seconds to MM:SS
        function formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        // Audio Player Simulation Actions with dynamic timers (Task 1)
        function playAudio(title, artist, cover, durationStr = '05:20') {
            document.getElementById('playerTitle').innerText = title;
            document.getElementById('playerArtist').innerText = artist;
            document.getElementById('playerCover').src = cover;

            const player = document.getElementById('stickyPlayer');
            player.classList.remove('translate-y-44', 'opacity-0', 'pointer-events-none');
            player.classList.add('translate-y-0', 'opacity-100');

            state.isPlaying = true;
            document.getElementById('playBtnIcon').className = "fa-solid fa-pause";

            state.audioProgress = 0;
            document.getElementById('playerProgress').style.width = '0%';
            document.getElementById('currentTime').innerText = "00:00";

            // Convert MM:SS string to seconds
            let seconds = 320;
            if (durationStr && durationStr.includes(':')) {
                const parts = durationStr.split(':');
                if (parts.length === 2) {
                    seconds = parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
                } else if (parts.length === 3) { // HH:MM:SS
                    seconds = parseInt(parts[0], 10) * 3600 + parseInt(parts[1], 10) * 60 + parseInt(parts[2], 10);
                }
            }
            if (isNaN(seconds) || seconds <= 0) seconds = 320;

            state.audioDuration = seconds;
            document.getElementById('totalTime').innerText = formatTime(state.audioDuration);

            if (state.progressInterval) clearInterval(state.progressInterval);
            state.progressInterval = setInterval(() => {
                if (state.isPlaying) {
                    state.audioProgress += 1;
                    if (state.audioProgress > state.audioDuration) {
                        state.audioProgress = 0;
                    }
                    const percent = (state.audioProgress / state.audioDuration) * 100;
                    document.getElementById('playerProgress').style.width = percent + '%';
                    document.getElementById('currentTime').innerText = formatTime(state.audioProgress);
                }
            }, 1000);
        }

        function toggleAudio() {
            state.isPlaying = !state.isPlaying;
            document.getElementById('playBtnIcon').className = state.isPlaying ? "fa-solid fa-pause" : "fa-solid fa-play";
        }

        function closeAudio() {
            const player = document.getElementById('stickyPlayer');
            player.classList.add('translate-y-44', 'opacity-0', 'pointer-events-none');
            player.classList.remove('translate-y-0', 'opacity-100');
            state.isPlaying = false;
            if (state.progressInterval) clearInterval(state.progressInterval);
        }

        // Window Loaded Actions
        window.addEventListener('load', () => {
            initHeroSlider(6000);
            initFantasyCarousel('maddahiCarousel', 4500);
            initFantasyCarousel('speechesCarousel', 5500);
            initFantasyCarousel('speakersCarousel', 5000); // Speakers list carousel init - Task 1
            initBackToTop();
            initMobileDrawer(); // Initialize mobile drawer - Task 6
            initRevealOnScroll();
            initKativehAnimation();
        });
    </script>
<?php wp_footer(); ?>
    <script>
    // Infinite Scroll Logic
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.querySelector('.infinite-scroll-trigger');
        const grid = document.querySelector('.archive-grid');
        const paginationContainer = document.querySelector('.pagination-container');
        
        if (trigger && grid && paginationContainer) {
            let isLoading = false;
            let nextLink = paginationContainer.querySelector('.next-page-btn');
            
            if (!nextLink) {
                // No next page, hide trigger
                trigger.style.display = 'none';
                return;
            }

            const spinner = trigger.querySelector('.fa-circle-notch');

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !isLoading && nextLink) {
                    loadNextPage(nextLink.href);
                }
            }, { rootMargin: '200px' });

            observer.observe(trigger);

            function loadNextPage(url) {
                isLoading = true;
                if(spinner) spinner.classList.remove('opacity-0');
                
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newGrid = doc.querySelector('.archive-grid');
                        const newPagination = doc.querySelector('.pagination-container');
                        
                        if (newGrid) {
                            // Append all children
                            Array.from(newGrid.children).forEach(child => {
                                grid.appendChild(child.cloneNode(true));
                            });
                        }
                        
                        if (newPagination) {
                            nextLink = newPagination.querySelector('.next-page-btn');
                            paginationContainer.innerHTML = newPagination.innerHTML;
                        } else {
                            nextLink = null;
                        }
                        
                        if (!nextLink) {
                            observer.unobserve(trigger);
                            trigger.style.display = 'none';
                        }
                        
                        // Re-trigger filtering if on persons archive
                        if (typeof window.applyPersonFilter === 'function') {
                            window.applyPersonFilter();
                        }
                    })
                    .catch(err => console.error('Error loading more posts:', err))
                    .finally(() => {
                        isLoading = false;
                        if(spinner) spinner.classList.add('opacity-0');
                    });
            }
        }
    });
    </script>

    <script>
    // PWA Setup
    let deferredPrompt;
    
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?php echo esc_url(home_url('/?heyat_sw=1')); ?>')
                .catch(err => console.log('SW registration failed: ', err));
        });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Show the static button in the footer
        const staticBtn = document.getElementById('static-pwa-install-btn');
        if (staticBtn) staticBtn.classList.remove('hidden');
    });

    const staticInstallBtn = document.getElementById('static-pwa-install-btn');
    if (staticInstallBtn) {
        staticInstallBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                staticInstallBtn.classList.add('hidden');
            }
        });
    }
    </script>
</body>
</html>