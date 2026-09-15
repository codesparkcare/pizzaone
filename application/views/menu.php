<?php 
    // Initialize variables if not set
    if (!isset($current_cat_id)) $current_cat_id = null;
    if (!isset($is_subcategory)) $is_subcategory = false;
    if (!isset($categories)) $categories = [];
    if (!isset($all_categories)) $all_categories = [];
    if (!isset($products)) $products = [];
?>

<main class="menu-page">
    <!-- Menu Hero -->
    <section class="menu-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
        <div class="container">
            <h1><?php echo t('Notre Délicieux Menu', 'Our Delicious Menu'); ?></h1>
            <p><?php echo t('De la classique napolitaine aux pizzas gourmandes créatives, nous en avons pour tous les goûts.', 'From classic Neapolitan to creative gourmet pizzas, we have something for everyone.'); ?></p>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="category-filter section-padding">
        <div class="container">
            <div class="filter-wrapper">
                <a href="<?php echo base_url('menu'); ?>" data-cat-id="" onclick="loadCategory('', event)" class="filter-item <?php echo !$current_cat_id ? 'active' : ''; ?>">
                    <div class="filter-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <span><?php echo t('Tous les articles', 'All Items'); ?></span>
                </a>
                <?php foreach($categories as $cat): ?>
                    <a href="<?php echo base_url('menu/'.$cat->id); ?>" data-cat-id="<?php echo $cat->id; ?>" onclick="loadCategory('<?php echo $cat->id; ?>', event)" class="filter-item <?php echo $current_cat_id == $cat->id && !isset($is_subcategory) ? 'active' : ''; ?>">
                        <div class="filter-icon">
                            <?php if($cat->image): ?>
                                <img src="<?php echo base_url('assets/images/categories/'.$cat->image); ?>" alt="<?php echo htmlspecialchars($cat->name); ?>" loading="lazy">
                            <?php else: ?>
                                <i class="fas fa-pizza-slice"></i>
                            <?php endif; ?>
                        </div>
                        <span><?php echo htmlspecialchars($cat->name); ?></span>
                    </a>
                    
                    <!-- Show subcategories for this parent category -->
                    <?php 
                        $subcats = array_filter($all_categories, function($c) use ($cat) { 
                            return $c->parent_id == $cat->id; 
                        });
                    ?>
                    <?php foreach($subcats as $subcat): ?>
                        <a href="<?php echo base_url('menu/'.$subcat->id); ?>" data-cat-id="<?php echo $subcat->id; ?>" onclick="loadCategory('<?php echo $subcat->id; ?>', event)" class="filter-item filter-subitem <?php echo $current_cat_id == $subcat->id && isset($is_subcategory) && $is_subcategory ? 'active' : ''; ?>">
                            <div class="filter-icon">
                                <?php if($subcat->image): ?>
                                    <img src="<?php echo base_url('assets/images/categories/'.$subcat->image); ?>" alt="<?php echo htmlspecialchars($subcat->name); ?>" loading="lazy">
                                <?php else: ?>
                                    <i class="fas fa-arrow-right"></i>
                                <?php endif; ?>
                            </div>
                            <span><?php echo htmlspecialchars($subcat->name); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <section class="products-section container">
        <div class="products-header">
            <?php if($current_cat_id): ?>
                <?php 
                    $cat_name = "";
                    foreach($all_categories as $cat) { 
                        if($cat->id == $current_cat_id) { 
                            $cat_name = $cat->name; 
                            break; 
                        } 
                    }
                ?>
                <h2 id="categoryHeaderTitle"><?php echo t('Affichage de ', 'Showing ') . $cat_name; ?></h2>
            <?php else: ?>
                <h2 id="categoryHeaderTitle"><?php echo t('Tous les produits', 'All Products'); ?></h2>
            <?php endif; ?>
            <p id="categoryHeaderCount"><?php echo count($products) . ' ' . t('articles trouvés', 'items found'); ?></p>
        </div>

        <div class="menu-grid" id="menuProductsGrid">
            <?php $this->load->view('partials/menu_products_grid', ['products' => $products]); ?>
        </div>
    </section>
</main>

<style>
/* Menu Page Specific Styles */
.menu-hero {
    padding: 6rem 0;
    text-align: center;
    color: var(--white);
    background-size: cover !important;
    background-position: center !important;
}

.menu-hero h1 {
    font-size: 3.5rem;
    font-family: 'Lobster', cursive;
    margin-bottom: 1rem;
}

.category-filter {
    background: #fff;
    border-bottom: 1px solid #eee;
    padding: 2rem 0;
    position: relative;
    z-index: 10;
}

.filter-wrapper {
    display: flex;
    justify-content: center;
    gap: 2rem;
    overflow-x: auto;
    padding: 10px 0;
    scrollbar-width: none;
}

.filter-wrapper::-webkit-scrollbar {
    display: none;
}

.filter-item {
    text-decoration: none;
    text-align: center;
    color: var(--dark);
    transition: var(--transition);
    min-width: 80px;
    flex-shrink: 0;
}

.filter-icon {
    width: 65px;
    height: 65px;
    background: #f8f8f8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    transition: var(--transition);
    border: 2px solid transparent;
    overflow: hidden;
}

.filter-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.filter-icon i {
    font-size: 1.5rem;
    color: var(--primary);
}

.filter-item span {
    font-size: 0.85rem;
    font-weight: 600;
}

.filter-item:hover .filter-icon, .filter-item.active .filter-icon {
    background: var(--white);
    border-color: var(--primary);
    transform: translateY(-5px);
    box-shadow: var(--shadow);
}

.filter-item.active span {
    color: var(--primary);
}

.filter-subitem {
    min-width: auto !important;
}

.filter-subitem .filter-icon {
    width: 50px !important;
    height: 50px !important;
    margin: 0 auto 5px !important;
}

.filter-subitem .filter-icon i {
    font-size: 1rem !important;
}

.filter-subitem span {
    font-size: 0.75rem !important;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    margin-top: 3rem;
}

.products-header h2 {
    font-size: 2rem;
    color: var(--primary);
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2.5rem;
    padding-bottom: 5rem;
}

.menu-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.menu-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.menu-card-img {
    height: 200px;
    position: relative;
    overflow: hidden;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

.menu-card-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: var(--transition);
}

.menu-card:hover .menu-card-img img {
    transform: scale(1.08);
}

.menu-card-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--primary);
    color: #fff;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

.menu-card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
}

.menu-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 10px;
}

.menu-card-header h3 {
    font-size: 1.25rem;
    color: #e74c3c;
    font-weight: 700;
}

.menu-card-header .price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
    white-space: nowrap;
}

.menu-card-body p {
    color: #222222;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.7em;
}

.menu-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-details {
    text-decoration: none;
    color: #111111;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}

.btn-details:hover {
    color: var(--primary);
}

.btn-add {
    width: 40px;
    height: 40px;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-add:hover {
    background: var(--secondary);
    transform: scale(1.1);
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 5rem 0;
}

.no-products i {
    font-size: 4rem;
    color: #eee;
    margin-bottom: 1rem;
}

.no-products p {
    font-size: 1.2rem;
    color: #999;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .menu-hero { padding: 3rem 0; }
    .menu-hero h1 { font-size: 2rem; }
    .category-filter {
        position: relative;
        top: auto;
        padding: 0.8rem 0;
        z-index: 10;
    }
    .filter-wrapper { 
        justify-content: flex-start; 
        padding: 5px 15px 15px; 
        gap: 1rem; 
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    .filter-item { 
        min-width: 65px; 
        flex-shrink: 0;
    }
    .filter-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .filter-item span {
        font-size: 0.75rem;
        white-space: nowrap;
    }
    .filter-subitem .filter-icon {
        width: 42px !important;
        height: 42px !important;
    }
    .filter-item:hover .filter-icon, .filter-item.active .filter-icon {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .products-header {
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .products-header h2 {
        font-size: 1.4rem;
    }
    .menu-grid { 
        grid-template-columns: repeat(2, 1fr); 
        gap: 0.85rem; 
        padding-bottom: 3rem;
    }
    .menu-card {
        border-radius: 14px;
    }
    .menu-card-img {
        height: 140px;
        padding: 6px;
    }
    .menu-card-body {
        padding: 0.85rem;
    }
    .menu-card-header {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.2rem;
        margin-bottom: 8px;
    }
    .menu-card-header h3 {
        font-size: 0.95rem;
        line-height: 1.25;
        word-break: break-word;
    }
    .menu-card-header .price {
        font-size: 0.95rem;
        font-weight: 800;
    }
    .menu-card-body p {
        font-size: 0.8rem;
        margin-bottom: 0.8rem;
        -webkit-line-clamp: 2;
        min-height: 2.5em;
    }
    .btn-details {
        font-size: 0.8rem;
    }
    .btn-add {
        width: 32px;
        height: 32px;
        border-radius: 8px;
    }
    .wishlist-btn {
        top: 8px;
        right: 8px;
        width: 30px;
        height: 30px;
    }
    .wishlist-btn i {
        font-size: 1rem;
    }
}

.wishlist-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0,0,0,0.4);
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    z-index: 10;
}

.wishlist-btn:hover {
    background: rgba(0,0,0,0.7);
    transform: scale(1.1);
}

.wishlist-btn i {
    font-size: 1.2rem;
    transition: var(--transition);
}

.animate-fade-in {
    animation: fadeInMenu 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeInMenu {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
    const categoryCache = {};
    let isCatLoading = false;
    const initialCatId = '<?php echo $current_cat_id ? $current_cat_id : ""; ?>';
    
    // Save initial page products into cache
    const initialGrid = document.getElementById('menuProductsGrid');
    const initialTitle = document.getElementById('categoryHeaderTitle')?.textContent || '';
    const initialCount = document.getElementById('categoryHeaderCount')?.textContent || '';
    if (initialGrid) {
        categoryCache[initialCatId] = {
            html: initialGrid.innerHTML,
            title: initialTitle,
            count: initialCount
        };
    }

    function loadCategory(catId, event) {
        if (event) event.preventDefault();
        if (isCatLoading) return;

        const strCatId = String(catId || '');

        // Update active filter styling immediately
        document.querySelectorAll('.filter-item').forEach(item => {
            const itemCat = item.getAttribute('data-cat-id') || '';
            if (itemCat === strCatId) {
                item.classList.add('active');
                item.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });

        const targetUrl = '<?php echo base_url("menu"); ?>' + (strCatId ? '/' + strCatId : '');

        // If in cache, render INSTANTLY (0ms)
        if (categoryCache[strCatId]) {
            renderCategoryData(categoryCache[strCatId]);
            window.history.pushState({ catId: strCatId }, '', targetUrl);
            return;
        }

        // Show loading state in grid
        const grid = document.getElementById('menuProductsGrid');
        if (grid) {
            grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem; color: var(--primary, #e21b1b);"><i class="fas fa-spinner fa-spin" style="font-size: 2.5rem;"></i><p style="margin-top: 15px; font-weight: 600; color: #555;"><?php echo t("Chargement des produits...", "Loading products..."); ?></p></div>';
        }

        isCatLoading = true;
        fetch(targetUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const titleText = data.cat_name ? ('<?php echo t("Affichage de ", "Showing "); ?>' + data.cat_name) : '<?php echo t("Tous les produits", "All Products"); ?>';
                const countText = data.count + ' ' + '<?php echo t("articles trouvés", "items found"); ?>';
                
                const payload = {
                    html: data.html,
                    title: titleText,
                    count: countText
                };

                categoryCache[strCatId] = payload;
                renderCategoryData(payload);
                window.history.pushState({ catId: strCatId }, '', targetUrl);
            }
        })
        .catch(err => {
            console.error('Error loading category:', err);
            window.location.href = targetUrl;
        })
        .finally(() => {
            isCatLoading = false;
        });
    }

    function renderCategoryData(data) {
        const grid = document.getElementById('menuProductsGrid');
        const titleEl = document.getElementById('categoryHeaderTitle');
        const countEl = document.getElementById('categoryHeaderCount');

        if (grid && data.html) grid.innerHTML = data.html;
        if (titleEl && data.title) titleEl.textContent = data.title;
        if (countEl && data.count) countEl.textContent = data.count;
    }

    // Handle browser Back / Forward buttons
    window.addEventListener('popstate', function(e) {
        const catId = e.state ? e.state.catId : '';
        if (catId !== undefined) {
            loadCategory(catId);
        }
    });

    // Background prefetch for remaining categories when browser is idle
    window.addEventListener('load', function() {
        const prefetchCategories = () => {
            const items = Array.from(document.querySelectorAll('.filter-item'));
            let queue = items.map(el => el.getAttribute('data-cat-id') || '').filter(id => id !== initialCatId);

            function fetchNext() {
                if (queue.length === 0) return;
                const nextId = queue.shift();
                if (categoryCache[nextId]) {
                    fetchNext();
                    return;
                }

                const url = '<?php echo base_url("menu"); ?>' + (nextId ? '/' + nextId : '');
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status === 'success') {
                            const titleText = res.cat_name ? ('<?php echo t("Affichage de ", "Showing "); ?>' + res.cat_name) : '<?php echo t("Tous les produits", "All Products"); ?>';
                            const countText = res.count + ' ' + '<?php echo t("articles trouvés", "items found"); ?>';
                            categoryCache[nextId] = {
                                html: res.html,
                                title: titleText,
                                count: countText
                            };
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        setTimeout(fetchNext, 400); // polite background queue
                    });
            }
            setTimeout(fetchNext, 1200);
        };

        if ('requestIdleCallback' in window) {
            requestIdleCallback(prefetchCategories);
        } else {
            setTimeout(prefetchCategories, 1500);
        }
    });
</script>
