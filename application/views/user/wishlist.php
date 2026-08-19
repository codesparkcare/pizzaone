<main class="wishlist-page section-padding">
    <div class="container">
        <div class="wishlist-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="color: var(--secondary);">My Wishlist</h2>
            <a href="<?php echo base_url('menu'); ?>" class="btn-primary" style="padding: 10px 20px; text-decoration: none; border-radius: 50px;">Browse Menu</a>
        </div>

        <div class="menu-grid">
            <?php if(!empty($wishlist_items)): ?>
                <?php foreach($wishlist_items as $p): ?>
                    <div class="menu-card" id="wishlist-item-<?php echo $p->id; ?>">
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
                            <button class="wishlist-btn" onclick="removeFromWishlist(<?php echo $p->id; ?>)" title="Remove from Wishlist">
                                <i class="fas fa-trash" style="color: #ff4757;"></i>
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
                <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 5rem 0;">
                    <i class="fas fa-heart-broken" style="font-size: 4rem; color: #eee; margin-bottom: 1rem;"></i>
                    <p style="font-size: 1.2rem; color: #999; margin-bottom: 2rem;">Your wishlist is empty.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.wishlist-page {
    min-height: 60vh;
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
.wishlist-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255,255,255,0.9);
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
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.wishlist-btn:hover {
    background: #fff;
    transform: scale(1.1);
}
@media (max-width: 768px) {
    .menu-grid { 
        grid-template-columns: repeat(2, 1fr); 
        gap: 1rem; 
    }
}
</style>

<script>
function removeFromWishlist(productId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        fetch('<?php echo base_url("user/toggle_wishlist/"); ?>' + productId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'removed') {
                    const card = document.getElementById('wishlist-item-' + productId);
                    if (card) {
                        card.remove();
                        // Check if grid is empty
                        const grid = document.querySelector('.menu-grid');
                        if (grid.querySelectorAll('.menu-card').length === 0) {
                            grid.innerHTML = '<div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 5rem 0;"><i class="fas fa-heart-broken" style="font-size: 4rem; color: #eee; margin-bottom: 1rem;"></i><p style="font-size: 1.2rem; color: #999; margin-bottom: 2rem;">Your wishlist is empty.</p></div>';
                        }
                    }
                    
                    // Update badge count
                    let badge = document.getElementById('wishlistBadge');
                    if (badge) {
                        badge.textContent = data.wishlist_count;
                        if (data.wishlist_count > 0) {
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                } else if (data.status === 'error') {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error removing from wishlist:', error);
            });
    }
}
</script>
