
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
    <div class="pizza-grid" id="popularPizzasGrid">
        <?php foreach($featured_products as $p): ?>
            <?php $this->load->view('partials/product_card', ['p' => $p]); ?>
        <?php endforeach; ?>
    </div>

    <!-- Infinite Scroll & Load More Container -->
    <div id="loadMoreContainer" style="text-align: center; margin: 35px 0 15px 0; min-height: 50px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div id="loadingSpinner" style="display: none; align-items: center; justify-content: center; gap: 10px; color: var(--primary, #e21b1b); font-weight: 600; font-size: 1rem; padding: 10px 20px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 1.4rem;"></i>
            <span><?php echo t('Chargement de plus de pizzas...', 'Loading more pizzas...'); ?></span>
        </div>
        <?php if (!empty($has_more_featured)): ?>
            <button id="btnLoadMore" type="button" class="btn-load-more" onclick="loadMorePizzas()" style="display: inline-flex; align-items: center; gap: 10px; background: #ffffff; color: #222222; border: 2px solid #e2e8f0; padding: 12px 30px; border-radius: 50px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
                <span><?php echo t('Voir plus de pizzas', 'View More Pizzas'); ?></span>
                <i class="fas fa-chevron-down" style="font-size: 0.85rem; color: var(--primary, #e21b1b);"></i>
            </button>
        <?php endif; ?>
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

<style>
.animate-fade-in {
    animation: fadeInPizza 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeInPizza {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.btn-load-more:hover {
    background: #f8fafc !important;
    border-color: var(--primary, #e21b1b) !important;
    color: var(--primary, #e21b1b) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}
</style>

<script>
    let pizzaOffset = 6;
    const pizzaLimit = 6;
    let isPizzaLoading = false;
    let hasMorePizzas = <?php echo !empty($has_more_featured) ? 'true' : 'false'; ?>;

    function loadMorePizzas() {
        if (isPizzaLoading || !hasMorePizzas) return;
        isPizzaLoading = true;

        const spinner = document.getElementById('loadingSpinner');
        const btn = document.getElementById('btnLoadMore');
        if (spinner) spinner.style.display = 'inline-flex';
        if (btn) btn.style.display = 'none';

        fetch('<?php echo base_url("welcome/load_more_pizzas"); ?>?offset=' + pizzaOffset + '&limit=' + pizzaLimit, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const grid = document.getElementById('popularPizzasGrid');
                if (grid && data.html) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                }
                pizzaOffset = data.next_offset;
                hasMorePizzas = data.has_more;

                const container = document.getElementById('loadMoreContainer');
                if (!hasMorePizzas) {
                    if (container) container.style.display = 'none';
                } else if (btn) {
                    btn.style.display = 'inline-flex';
                }
            }
        })
        .catch(err => {
            console.error('Error loading more pizzas:', err);
            if (btn && hasMorePizzas) btn.style.display = 'inline-flex';
        })
        .finally(() => {
            isPizzaLoading = false;
            if (spinner) spinner.style.display = 'none';
        });
    }

    // Auto-load on scroll via IntersectionObserver
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        if (!loadMoreContainer || !hasMorePizzas) return;

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && hasMorePizzas && !isPizzaLoading) {
                        loadMorePizzas();
                    }
                });
            }, {
                rootMargin: '250px 0px',
                threshold: 0.05
            });

            observer.observe(loadMoreContainer);
        }
    });
</script>
