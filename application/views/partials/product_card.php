<div class="pizza-card animate-fade-in">
    <?php if (!empty($p->offer_name)): ?>
        <div class="sale-badge"><?php echo htmlspecialchars($p->offer_name); ?></div>
    <?php endif; ?>
    <div class="pizza-img-wrapper">
        <div class="pizza-bg-shape"></div>
        <img src="<?php echo base_url('assets/images/products/'.($p->image ? $p->image : 'default.png')); ?>" alt="<?php echo htmlspecialchars($p->name); ?>" loading="lazy">
        <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $p->id; ?>, this)" title="Add to Wishlist" style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.4); border: none; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; z-index: 10;">
            <i class="<?php echo !empty($p->in_wishlist) ? 'fas' : 'far'; ?> fa-heart" style="color: <?php echo !empty($p->in_wishlist) ? '#ff4757' : '#fff'; ?>; font-size: 1.2rem;"></i>
        </button>
    </div>
    <div class="pizza-info">
        <div class="pizza-card-header">
            <h3 class="pizza-title"><?php echo htmlspecialchars($p->name); ?></h3>
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
        <p class="pizza-desc"><?php echo htmlspecialchars($p->description); ?></p>
        
        <div class="pizza-footer">
            <a href="javascript:void(0)" onclick="openProductModal(<?php echo $p->id; ?>)" style="color: #111111; font-weight: 600; text-decoration: none; font-size: 0.9rem;"><?php echo t('Voir les détails', 'View Details'); ?></a>
            <button class="btn-basket" onclick="openProductModal(<?php echo $p->id; ?>)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>
