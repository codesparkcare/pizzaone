
<!-- Hero Slider Section -->
<section class="hero-slider">
    <?php if(!empty($slider_videos)): ?>
        <?php foreach($slider_videos as $index => $video): ?>
            <div class="slide <?php echo $index == 0 ? 'active' : ''; ?>">
                <video autoplay muted loop playsinline class="slider-video" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; pointer-events: none;">
                    <source src="<?php echo base_url('assets/videos/'.$video->video_url); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="slide-content" style="position: relative; z-index: 3;">
                    <h2>Pizza One</h2>
                    <p>Une pizza. Un moment à partager.<br>Du goût, de la générosité et du plaisir à chaque bouchée.</p>
                    <a href="#menu" class="btn-hero">COMMANDER</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/slider/pizza1.png'); ?>');">
        <div class="slide-content">
            <h2>Pizza One</h2>
            <p>Une pizza. Un moment à partager.<br>Du goût, de la générosité et du plaisir à chaque bouchée.</p>
            <a href="#menu" class="btn-hero">COMMANDER</a>
        </div>
    </div>
    <div class="slide" style="background-image: url('<?php echo base_url('assets/images/slider/pizza2.png'); ?>');">
        <div class="slide-content">
            <h2>Pizza One</h2>
            <p>Une pizza. Un moment à partager.<br>Du goût, de la générosité et du plaisir à chaque bouchée.</p>
            <a href="#menu" class="btn-hero">COMMANDER</a>
        </div>
    </div>
    <div class="slide" style="background-image: url('<?php echo base_url('assets/images/slider/pizza3.png'); ?>');">
        <div class="slide-content">
            <h2>Pizza One</h2>
            <p>Une pizza. Un moment à partager.<br>Du goût, de la générosité et du plaisir à chaque bouchée.</p>
            <a href="#menu" class="btn-hero">COMMANDER</a>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <i class="fas fa-truck"></i>
                <h3><?php echo t('Livraison Gratuite', 'Free Delivery'); ?></h3>
                <p><?php echo t('Sur toutes les commandes dès 20€', 'On all orders above €20'); ?></p>
            </div>
            <div class="feature-item">
                <i class="fas fa-leaf"></i>
                <h3><?php echo t('Ingrédients Frais', 'Fresh Ingredients'); ?></h3>
                <p><?php echo t('100% frais et de qualité', '100% organic and locally sourced'); ?></p>
            </div>
            <div class="feature-item">
                <i class="fas fa-clock"></i>
                <h3><?php echo t('Service Rapide', 'Quick Service'); ?></h3>
                <p><?php echo t('Prêt en seulement 15 min', 'Ready in just 15 minutes'); ?></p>
            </div>
            <div class="feature-item">
                <i class="fas fa-medal"></i>
                <h3><?php echo t('Meilleur Goût', 'Best Taste'); ?></h3>
                <p><?php echo t('Recettes gourmandes savoureuses', 'Award winning recipes'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Menu Categories Section -->
<section class="container section-padding" style="padding-top: 1rem;">
    <div class="section-title">
        <h2><?php echo t('Nos Catégories de Menu', 'Our Menu Categories'); ?></h2>
    </div>
    
    <!-- Swiper Categories Slider -->
    <div class="swiper cat-slider">
        <div class="swiper-wrapper">
            <?php foreach($categories as $cat): ?>
                <div class="swiper-slide">
                    <a href="<?php echo base_url('menu/'.$cat->id); ?>" class="cat-item">
                        <div class="cat-img-wrapper">
                            <?php if($cat->image): ?>
                                <img src="<?php echo base_url('assets/images/categories/'.$cat->image); ?>" alt="<?php echo $cat->name; ?>">
                            <?php else: ?>
                                <i class="fas fa-pizza-slice"></i>
                            <?php endif; ?>
                        </div>
                        <span><?php echo $cat->name; ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Initialize Swiper -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.cat-slider', {
            slidesPerView: 2,
            spaceBetween: 10,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 25,
                },
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });
    });
</script>

<!-- Featured Pizzas Section -->
<section id="menu" class="container" style="padding-bottom: 1rem;">
    <div class="section-title">
        <h2><?php echo t('Pizzas Populaires', 'Popular Pizzas'); ?></h2>
    </div>
    <div class="pizza-grid">
        <?php foreach($featured_products as $p): ?>
            <div class="pizza-card">
                <?php if (!empty($p->offer_name)): ?>
                    <div class="sale-badge"><?php echo $p->offer_name; ?></div>
                <?php endif; ?>
                <div class="pizza-img-wrapper">
                    <div class="pizza-bg-shape"></div>
                    <img src="<?php echo base_url('assets/images/products/'.($p->image ? $p->image : 'default.png')); ?>" alt="<?php echo $p->name; ?>">
                    <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $p->id; ?>, this)" title="Add to Wishlist" style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.4); border: none; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; z-index: 10;">
                        <i class="<?php echo !empty($p->in_wishlist) ? 'fas' : 'far'; ?> fa-heart" style="color: <?php echo !empty($p->in_wishlist) ? '#ff4757' : '#fff'; ?>; font-size: 1.2rem;"></i>
                    </button>
                </div>
                <div class="pizza-info">
                    <div class="pizza-card-header">
                        <h3 class="pizza-title"><?php echo $p->name; ?></h3>
                        <div class="product-sizes-list">
                            <?php if (!empty($p->sizes)): ?>
                                <?php foreach ($p->sizes as $sz): ?>
                                    <?php $short_size = ucfirst(strtolower(explode(' ', trim($sz->size_name))[0])); ?>
                                    <div class="size-price-item">
                                        <span class="size-badge"><?php echo htmlspecialchars($short_size); ?></span>
                                        <span class="price-val">€<?php echo number_format($sz->size_price, 2); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="size-price-item">
                                    <span class="price-val">€<?php echo number_format($p->price, 2); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="pizza-desc"><?php echo $p->description; ?></p>
                    
                    <div class="pizza-footer">
                        <a href="javascript:void(0)" onclick="openProductModal(<?php echo $p->id; ?>)" style="color: #111111; font-weight: 600; text-decoration: none; font-size: 0.9rem;"><?php echo t('Voir les détails', 'View Details'); ?></a>
                        <button class="btn-basket" onclick="openProductModal(<?php echo $p->id; ?>)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Customer Reviews Section -->
<?php if (!empty($reviews)): ?>
<style>
.reviews-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
    gap: 30px; 
    margin-top: 30px;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}
.review-card {
    background: #fff; 
    padding: 25px; 
    border-radius: 15px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
    border-bottom: 4px solid var(--primary); 
    transition: transform 0.3s ease;
    width: 100%;
    box-sizing: border-box;
}
@media (max-width: 768px) {
    .reviews-grid {
        grid-template-columns: 1fr;
        padding: 0 15px;
        gap: 15px;
    }
    .review-card {
        padding: 18px 15px;
    }
}
</style>
<section class="container section-padding" style="padding-top: 1rem; padding-bottom: 5rem;">
    <div class="section-title" style="text-align: center;">
        <h2><?php echo t('Ce que disent nos clients', 'What Our Customers Say'); ?></h2>
        <p style="color: #666; margin-top: 10px;"><?php echo t('De vrais avis de passionnés de pizza.', 'Real reviews from real pizza lovers.'); ?></p>
    </div>
    
    <div class="reviews-grid">
        <?php foreach($reviews as $r): ?>
            <div class="review-card">
                <div class="review-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 45px; height: 45px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
                            <?php echo strtoupper(substr($r->customer_name, 0, 1)); ?>
                        </div>
                        <div style="text-align: left;">
                            <h4 style="margin: 0; font-size: 1.1rem; color: var(--secondary);"><?php echo $r->customer_name; ?></h4>
                            <small style="color: #888; font-size: 0.8rem;"><?php echo date('M d, Y', strtotime($r->created_at)); ?></small>
                        </div>
                    </div>
                    <div class="review-rating" style="color: #f1c40f;">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="fas fa-star" style="<?php echo ($i > $r->rating) ? 'color: #e0e0e0;' : ''; ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="review-body" style="color: #555; line-height: 1.6; font-style: italic; text-align: left;">
                    "<?php echo nl2br(htmlspecialchars($r->comment)); ?>"
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Slider JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.slide');
        if (!slides || slides.length === 0) return;
        
        let currentSlide = 0;

        function playActiveVideo() {
            const activeSlide = slides[currentSlide];
            if (activeSlide) {
                const video = activeSlide.querySelector('video');
                if (video) {
                    video.play().catch(function(e) {
                        console.log('Video autoplay exception:', e);
                    });
                }
            }
        }

        playActiveVideo();

        function nextSlide() {
            if (slides.length <= 1) return;
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
            playActiveVideo();
        }

        setInterval(nextSlide, 5000);
    });
</script>
