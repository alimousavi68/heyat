<?php
/**
 * The template for displaying single gallery posts
 */

get_header(); 
?>

<!-- Header Spacer -->
<div class="h-20"></div>

<?php while (have_posts()) : the_post(); 
    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if (!$img_url) $img_url = get_template_directory_uri() . '/images/slider-0.webp';
?>
<main class="max-w-6xl mx-auto px-4 w-full mb-20 min-h-[60vh]">
    <!-- Minimalist Hero for Gallery -->
    <div class="w-full flex flex-col items-center justify-center text-center mb-10 pt-8 pb-12 border-b border-white/10 relative">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full blur-[120px] bg-goldAccent/5 pointer-events-none -z-10"></div>
        <span class="ios-glass text-goldAccent px-4 py-1.5 rounded-full text-xs font-bold border border-goldAccent/20 flex items-center gap-2 mb-6">
            <i class="fa-solid fa-image text-sm"></i> گزارش تصویری
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight max-w-4xl"><?php the_title(); ?></h1>
        <div class="flex items-center gap-6 text-sm text-textMuted">
            <span class="flex items-center gap-2"><i class="fa-regular fa-calendar text-goldAccent"></i> <?php echo get_the_date('j F Y'); ?></span>
        </div>
    </div>

    <!-- Content Area (Gallery styling) -->
    <article class="single-gallery-content text-white/90 leading-loose">
        <style>
            /* Custom CSS to format standard WordPress [gallery] or Block Editor Galleries beautifully */
            .single-gallery-content p { display: none; } /* Hide empty p tags around gallery */
            .single-gallery-content .gallery, .single-gallery-content .wp-block-gallery { 
                display: grid !important; 
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important; 
                gap: 20px !important; 
                margin: 0 !important;
            }
            .single-gallery-content .gallery-item, .single-gallery-content .wp-block-image {
                margin: 0 !important;
                width: 100% !important;
                position: relative;
                overflow: hidden;
                border-radius: 16px;
                border: 1px solid rgba(255,255,255,0.05);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                transition: transform 0.3s, box-shadow 0.3s;
                cursor: pointer;
            }
            .single-gallery-content .gallery-item:hover, .single-gallery-content .wp-block-image:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(223,177,91,0.1);
                border-color: rgba(223,177,91,0.3);
            }
            .single-gallery-content .gallery-icon img, .single-gallery-content .wp-block-image img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                aspect-ratio: 1 / 1;
                transition: transform 0.5s;
                border: none !important;
                padding: 0 !important;
            }
            .single-gallery-content .gallery-item:hover .gallery-icon img, .single-gallery-content .wp-block-image:hover img {
                transform: scale(1.05);
            }
            .single-gallery-content .gallery-caption, .single-gallery-content figcaption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
                color: white !important;
                padding: 15px 10px 10px;
                font-size: 0.75rem !important;
                text-align: center;
                opacity: 0;
                transition: opacity 0.3s;
            }
            .single-gallery-content .gallery-item:hover .gallery-caption, .single-gallery-content .wp-block-image:hover figcaption {
                opacity: 1;
            }
            /* Show text content if there's any actual description */
            .single-gallery-content p:not(:empty) { 
                display: block; 
                margin-bottom: 2em; 
                text-align: center;
                font-size: 1.1rem;
                max-w: 800px;
                margin-inline: auto;
            }
        </style>
        
        <?php 
        $content = get_the_content();
        if (!empty($content)) {
            echo '<div class="mb-8">';
            the_content();
            echo '</div>';
        }
        
        $gallery_ids = get_post_meta(get_the_ID(), '_heyat_gallery_images', true);
        if (!empty($gallery_ids)) :
            $ids = explode(',', $gallery_ids);
            if (!empty($ids)) :
        ?>
            <div class="gallery">
                <?php foreach ($ids as $id) : 
                    if (empty($id)) continue;
                    $url = wp_get_attachment_url($id);
                    $caption = wp_get_attachment_caption($id);
                    if ($url) :
                ?>
                <figure class="gallery-item">
                    <div class="gallery-icon">
                        <!-- Open in Lightbox -->
                        <a href="<?php echo esc_url($url); ?>" class="gallery-lightbox-link">
                            <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($caption); ?>" />
                        </a>
                    </div>
                    <?php if ($caption): ?>
                    <figcaption class="gallery-caption"><?php echo esc_html($caption); ?></figcaption>
                    <?php endif; ?>
                </figure>
                <?php endif; endforeach; ?>
            </div>
        <?php 
            endif; 
        endif; 
        ?>
    </article>
    
    <!-- Share -->
    <div class="mt-16 flex flex-col items-center justify-center gap-4">
        <span class="text-textMuted text-sm">به اشتراک‌گذاری این گزارش تصویری:</span>
        <div class="flex gap-3">
            <a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="w-12 h-12 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all text-xl"><i class="fa-brands fa-telegram"></i></a>
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" class="w-12 h-12 rounded-full ios-glass flex items-center justify-center text-white hover:text-goldAccent hover:border-goldAccent transition-all text-xl"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
    </div>
</main>
<?php endwhile; ?>

<!-- Lightbox Modal -->
<div id="gallery-lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/95 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <button id="lightbox-close" class="absolute top-6 right-6 text-white hover:text-goldAccent text-4xl z-20 transition-colors cursor-pointer w-12 h-12 flex items-center justify-center bg-black/40 rounded-full border border-white/10">&times;</button>
    
    <button id="lightbox-prev" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-goldAccent text-2xl z-20 w-12 h-12 flex items-center justify-center bg-black/40 rounded-full border border-white/10 transition-colors"><i class="fa-solid fa-chevron-left"></i></button>
    <button id="lightbox-next" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-goldAccent text-2xl z-20 w-12 h-12 flex items-center justify-center bg-black/40 rounded-full border border-white/10 transition-colors"><i class="fa-solid fa-chevron-right"></i></button>
    
    <div class="relative w-full h-full max-w-6xl max-h-screen p-4 md:p-12 flex flex-col items-center justify-center" id="lightbox-content-area">
        <img id="lightbox-img" src="" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-[0_0_50px_rgba(223,177,91,0.15)] transition-transform duration-300 scale-95 border border-white/10" alt="">
        <div id="lightbox-caption" class="text-white text-center mt-6 text-sm md:text-base font-medium bg-black/50 px-6 py-2 rounded-full border border-white/5"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');
    const contentArea = document.getElementById('lightbox-content-area');
    
    const galleryLinks = Array.from(document.querySelectorAll('.gallery-lightbox-link'));
    if (galleryLinks.length === 0) return;
    
    let currentIndex = 0;
    
    function showImage(index) {
        if (index < 0) index = galleryLinks.length - 1;
        if (index >= galleryLinks.length) index = 0;
        
        currentIndex = index;
        const link = galleryLinks[currentIndex];
        const img = link.querySelector('img');
        
        // Reset animation
        lightboxImg.classList.remove('scale-100');
        lightboxImg.classList.add('scale-95');
        lightboxImg.style.opacity = '0.5';
        
        setTimeout(() => {
            lightboxImg.src = link.href;
            lightboxCaption.textContent = img.alt || '';
            lightboxCaption.style.display = img.alt ? 'block' : 'none';
            
            lightboxImg.onload = () => {
                lightboxImg.classList.remove('scale-95');
                lightboxImg.classList.add('scale-100');
                lightboxImg.style.opacity = '1';
            };
        }, 150);
    }
    
    galleryLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showImage(index);
            
            lightbox.classList.remove('hidden');
            lightbox.style.display = 'flex';
            // Force reflow
            void lightbox.offsetWidth;
            
            lightbox.classList.remove('opacity-0');
            lightbox.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        });
    });
    
    function closeModal() {
        lightbox.classList.remove('opacity-100');
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.style.display = '';
            document.body.style.overflow = '';
        }, 300);
    }
    
    closeBtn.addEventListener('click', closeModal);
    
    // Close when clicking outside image
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox || e.target === contentArea) {
            closeModal();
        }
    });
    
    prevBtn.addEventListener('click', (e) => { e.stopPropagation(); showImage(currentIndex - 1); });
    nextBtn.addEventListener('click', (e) => { e.stopPropagation(); showImage(currentIndex + 1); });
    
    document.addEventListener('keydown', function(e) {
        if (lightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeModal();
        if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
        if (e.key === 'ArrowRight') showImage(currentIndex + 1);
    });
});
</script>

<?php get_footer(); ?>
