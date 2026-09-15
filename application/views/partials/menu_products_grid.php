<?php if(!empty($products)): ?>
    <?php foreach($products as $p): ?>
        <div class="menu-card animate-fade-in">
            <div class="menu-card-img">
                <img src="<?php echo base_url('assets/images/products/'.($p->image ? $p->image : 'default.png')); ?>" alt="<?php echo htmlspecialchars($p->name); ?>" loading="lazy">
                <?php if (!empty($p->offer_name)): ?>
                    <div class="menu-card-badge">
                        <div style="background: #ff0000; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; text-align: center; font-weight: bold; box-shadow: 0 0 5px rgba(255,0,0,0.5);"><?php echo htmlspecialchars($p->offer_name); ?></div>
                    </div>
                <?php endif; ?>
                <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $p->id; ?>, this)" title="Add to Wishlist">
                    <i class="<?php echo !empty($p->in_wishlist) ? 'fas' : 'far'; ?> fa-heart" style="color: <?php echo !empty($p->in_wishlist) ? '#ff4757' : '#fff'; ?>;"></i>
                </button>
            </div>
            <div class="menu-card-body">
                <div class="menu-card-header">
                    <h3><?php echo htmlspecialchars($p->name); ?></h3>
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
                <p><?php echo htmlspecialchars($p->description); ?></p>
                <div class="menu-card-footer">
                    <a href="javascript:void(0)" onclick="openProductModal(<?php echo $p->id; ?>)" class="btn-details"><?php echo t('Voir les détails', 'View Details'); ?></a>
                    <a href="javascript:void(0)" onclick="openProductModal(<?php echo $p->id; ?>)" class="btn-add">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem;">
        <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
        <p style="font-size: 1.1rem; color: #666; margin-bottom: 1.5rem;"><?php echo t('Aucun produit trouvé dans cette catégorie.', 'No products found in this category.'); ?></p>
        <a href="javascript:void(0)" onclick="loadCategory(null)" class="btn-primary" style="display: inline-block; padding: 10px 24px; border-radius: 25px; text-decoration: none;"><?php echo t('Tout parcourir', 'Browse All'); ?></a>
    </div>
<?php endif; ?>
