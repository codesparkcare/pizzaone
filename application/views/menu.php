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
            <h1>Our Delicious Menu</h1>
            <p>From classic Neapolitan to creative gourmet pizzas, we have something for everyone.</p>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="category-filter section-padding">
        <div class="container">
            <div class="filter-wrapper">
                <a href="<?php echo base_url('menu'); ?>" class="filter-item <?php echo !$current_cat_id ? 'active' : ''; ?>">
                    <div class="filter-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <span>All Items</span>
                </a>
                <?php foreach($categories as $cat): ?>
                    <a href="<?php echo base_url('menu/'.$cat->id); ?>" class="filter-item <?php echo $current_cat_id == $cat->id && !isset($is_subcategory) ? 'active' : ''; ?>">
                        <div class="filter-icon">
                            <?php if($cat->image): ?>
                                <img src="<?php echo base_url('assets/images/categories/'.$cat->image); ?>" alt="<?php echo $cat->name; ?>">
                            <?php else: ?>
                                <i class="fas fa-pizza-slice"></i>
                            <?php endif; ?>
                        </div>
                        <span><?php echo $cat->name; ?></span>
                    </a>
                    
                    <!-- Show subcategories for this parent category -->
                    <?php 
                        $subcats = array_filter($all_categories, function($c) use ($cat) { 
                            return $c->parent_id == $cat->id; 
                        });
                    ?>
                    <?php foreach($subcats as $subcat): ?>
                        <a href="<?php echo base_url('menu/'.$subcat->id); ?>" class="filter-item filter-subitem <?php echo $current_cat_id == $subcat->id && isset($is_subcategory) && $is_subcategory ? 'active' : ''; ?>">
                            <div class="filter-icon">
                                <?php if($subcat->image): ?>
                                    <img src="<?php echo base_url('assets/images/categories/'.$subcat->image); ?>" alt="<?php echo $subcat->name; ?>">
                                <?php else: ?>
                                    <i class="fas fa-arrow-right"></i>
                                <?php endif; ?>
                            </div>
                            <span><?php echo $subcat->name; ?></span>
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
                <h2>Showing <?php echo $cat_name; ?></h2>
            <?php else: ?>
                <h2>All Products</h2>
            <?php endif; ?>
            <p><?php echo count($products); ?> items found</p>
        </div>

        <div class="menu-grid">
            <?php if(!empty($products)): ?>
                <?php foreach($products as $p): ?>
                    <div class="menu-card">
                        <div class="menu-card-img">
                            <img src="<?php echo base_url('assets/images/products/'.($p->image ? $p->image : 'default.png')); ?>" alt="<?php echo $p->name; ?>">
                            <div class="menu-card-badge">
                                <?php if (!empty($p->offer_name)): ?>
                                    <div style="background: #ff0000; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; margin-bottom: 2px; text-align: center; font-weight: bold; box-shadow: 0 0 5px rgba(255,0,0,0.5);"><?php echo $p->offer_name; ?></div>
                                <?php endif; ?>
                                <?php if(!empty($p->subcategory_name)): ?>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <span style="font-size: 0.75rem; opacity: 0.8;"><?php echo $p->category_name; ?></span>
                                        <span style="font-weight: 600;"><?php echo $p->subcategory_name; ?></span>
                                    </div>
                                <?php else: ?>
                                    <span><?php echo $p->category_name; ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $p->id; ?>, this)" title="Add to Wishlist">
                                <i class="<?php echo !empty($p->in_wishlist) ? 'fas' : 'far'; ?> fa-heart" style="color: <?php echo !empty($p->in_wishlist) ? '#ff4757' : '#fff'; ?>;"></i>
                            </button>
                        </div>
                        <div class="menu-card-body">
                            <div class="menu-card-header">
                                <h3><?php echo $p->name; ?></h3>
                                <span class="price">€<?php echo $p->price; ?></span>
                            </div>
                            <p><?php echo $p->description; ?></p>
                            <div class="menu-card-footer">
                                <a href="javascript:void(0)" onclick="openProductModal(<?php echo $p->id; ?>)" class="btn-details">View Details</a>
                                <a href="javascript:void(0)" onclick="openProductModal(<?php echo $p->id; ?>)" class="btn-add">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-search"></i>
                    <p>No products found in this category.</p>
                    <a href="<?php echo base_url('menu'); ?>" class="btn-primary">Browse All</a>
                </div>
            <?php endif; ?>
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
    position: sticky;
    top: 70px;
    z-index: 100;
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
    color: var(--secondary);
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
}

.menu-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.menu-card-img {
    height: 220px;
    position: relative;
    overflow: hidden;
}

.menu-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.menu-card:hover .menu-card-img img {
    transform: scale(1.1);
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
}

.menu-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.menu-card-header h3 {
    font-size: 1.25rem;
    color: var(--secondary);
}

.menu-card-header .price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
}

.menu-card-body p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.menu-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-details {
    text-decoration: none;
    color: var(--secondary);
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
    .menu-hero { padding: 4rem 0; }
    .menu-hero h1 { font-size: 2.2rem; }
    .category-filter { padding: 1rem 0; }
    .filter-wrapper { 
        justify-content: flex-start; 
        padding: 5px 15px 15px; 
        gap: 1.2rem; 
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    .filter-item { min-width: 65px; }
    .filter-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .filter-item span {
        font-size: 0.75rem;
        white-space: nowrap;
    }
    .filter-subitem .filter-icon {
        width: 45px !important;
        height: 45px !important;
    }
    .filter-item:hover .filter-icon, .filter-item.active .filter-icon {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .menu-grid { 
        grid-template-columns: repeat(2, 1fr); 
        gap: 1rem; 
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
</style>
