<div class="product-modal-inner">
    <!-- Product Section -->
    <section class="product-section" style="padding: 1rem 0;">
        <div class="container">
            <div class="product-wrapper">
                <!-- Product Image Gallery -->
                <div class="product-image-container">
                    <div class="image-frame">
                        <div class="product-image-main">
                            <img src="<?php echo base_url('assets/images/products/'.($product->image ? $product->image : 'default.png')); ?>" alt="<?php echo $product->name; ?>" id="mainImage">
                        </div>
                        <div class="badge-overlay">
                            <?php if (!empty($product->offer_name)): ?>
                                <span class="badge badge-offer" style="background: #ff0000; color: white; box-shadow: 0 0 5px rgba(255,0,0,0.5);"><?php echo $product->offer_name; ?></span>
                            <?php endif; ?>
                            <span class="badge badge-category"><?php echo $product->category ? $product->category->name : ''; ?></span>
                            <?php if (!empty($product->subcategory_name)): ?>
                                <span class="badge badge-subcategory"><?php echo $product->subcategory_name; ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="wishlist-btn" onclick="toggleWishlist(<?php echo $product->id; ?>, this)" title="Add to Wishlist" style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.4); border: none; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; z-index: 10;">
                            <i class="<?php echo !empty($product->in_wishlist) ? 'fas' : 'far'; ?> fa-heart" style="color: <?php echo !empty($product->in_wishlist) ? '#ff4757' : '#fff'; ?>; font-size: 1.2rem;"></i>
                        </button>
                    </div>
                </div>

                <!-- Product Details & Form -->
                <div class="product-details-wrapper">
                    <div class="product-info-header">
                        <h1 class="product-title"><?php echo $product->name; ?></h1>
                        <p class="product-description"><?php echo $product->description; ?></p>
                        
                        <!-- Quick Rating/Stats -->
                        <div class="product-meta">
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo t('Ingrédients 100% Frais', '100% Fresh Ingredients'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Customization Form -->
                    <form id="addToCartForm" class="customization-form">
                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">

                        <!-- Size Selection -->
                        <?php if (!empty($product->sizes)): ?>
                            <div class="form-section">
                                <div class="section-header">
                                    <h3 class="section-title">
                                        <i class="fas fa-expand"></i> <?php echo t('Choisir la taille', 'Choose Size'); ?>
                                        <span class="required-badge"><?php echo t('Obligatoire', 'Required'); ?></span>
                                    </h3>
                                </div>
                                <div class="size-options-grid">
                                    <?php foreach ($product->sizes as $index => $size): ?>
                                        <label class="size-card">
                                            <input type="radio" name="product_size" 
                                                   value="<?php echo $size->price; ?>" 
                                                   data-id="<?php echo $size->ps_id; ?>"
                                                   data-size-id="<?php echo $size->size_id ?? ''; ?>"
                                                   data-name="<?php echo htmlspecialchars($size->name); ?>"
                                                   <?php echo $index === 0 ? 'checked' : ''; ?> 
                                                   onchange="updatePrice()">
                                            <div class="size-card-content">
                                                <span class="size-name"><?php echo $size->name; ?></span>
                                                <span class="size-price">€<?php echo number_format($size->price, 2); ?></span>
                                            </div>
                                            <span class="checkmark"><i class="fas fa-check"></i></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Add-ons/Customizations Selection -->
                        <?php if (!empty($product->addon_groups) || !empty($product->addons)): ?>
                            <div class="form-section">
                                <!-- Display Addon Groups (New System) -->
                                <?php if (!empty($product->addon_groups)): ?>
                                    <?php foreach ($product->addon_groups as $group): ?>
                                        <div class="addon-group-section">
                                            <div class="section-header">
                                                <h3 class="section-title">
                                                    <i class="fas fa-plus-circle"></i> 
                                                    <?php echo $group->group_name; ?>
                                                    <?php if ($group->is_required): ?>
                                                        <span class="required-badge"><?php echo t('Obligatoire', 'Required'); ?></span>
                                                    <?php endif; ?>
                                                </h3>
                                                <div class="addon-group-info">
                                                    <?php 
                                                        if ($group->min_selections == 0 && $group->max_selections == 1) {
                                                            echo '<span class="selection-hint">' . t('Choisissez jusqu\'à 1', 'Choose up to 1') . '</span>';
                                                        } elseif ($group->max_selections > 1) {
                                                            echo '<span class="selection-hint">' . t('Choisissez jusqu\'à ', 'Choose up to ') . $group->max_selections . '</span>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="addons-showcase addon-group-<?php echo $group->group_id; ?>">
                                                <?php foreach ($group->items as $addon): ?>
                                                    <label class="addon-card" data-group="<?php echo $group->group_id; ?>" 
                                                           data-max="<?php echo $group->max_selections; ?>"
                                                           data-min="<?php echo $group->min_selections; ?>">
                                                        <div class="addon-checkbox">
                                                            <input type="checkbox" 
                                                                   name="addon_group_<?php echo $group->group_id; ?>" 
                                                                   value="<?php echo $addon->id; ?>"
                                                                   data-base-price="<?php echo $addon->price; ?>"
                                                                   data-price="<?php echo $addon->price; ?>"
                                                                   data-size-prices='<?php echo htmlspecialchars(json_encode($addon->size_prices_by_name ?? []), ENT_QUOTES, 'UTF-8'); ?>'
                                                                   data-size-prices-id='<?php echo htmlspecialchars(json_encode($addon->size_prices ?? []), ENT_QUOTES, 'UTF-8'); ?>'
                                                                   data-group-id="<?php echo $group->group_id; ?>"
                                                                   data-max-selections="<?php echo $group->max_selections; ?>"
                                                                   onchange="updatePrice(); validateAddonGroup(this)">
                                                            <span class="checkbox-custom"></span>
                                                        </div>
                                                        <div class="addon-info">
                                                            <span class="addon-name"><?php echo $addon->name; ?></span>
                                                            <span class="addon-price">
                                                                <?php 
                                                                    if ($addon->price > 0) {
                                                                        echo '+€' . number_format($addon->price, 2);
                                                                    } else {
                                                                        echo t('Gratuit', 'Free');
                                                                    }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Fallback to old system for backward compatibility -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <h3 class="section-title">
                                                <i class="fas fa-plus-circle"></i> <?php echo t('Personnaliser votre commande', 'Customize Your Order'); ?>
                                                <span class="optional-badge"><?php echo t('Optionnel', 'Optional'); ?></span>
                                            </h3>
                                        </div>
                                        <div class="addons-showcase">
                                            <?php foreach ($product->addons as $addon): ?>
                                                <label class="addon-card">
                                                    <div class="addon-checkbox">
                                                        <input type="checkbox" name="product_addon" 
                                                               value="<?php echo $addon->id; ?>"
                                                               data-base-price="<?php echo $addon->price; ?>"
                                                               data-price="<?php echo $addon->price; ?>"
                                                               data-size-prices='<?php echo htmlspecialchars(json_encode($addon->size_prices_by_name ?? []), ENT_QUOTES, 'UTF-8'); ?>'
                                                               data-size-prices-id='<?php echo htmlspecialchars(json_encode($addon->size_prices ?? []), ENT_QUOTES, 'UTF-8'); ?>'
                                                               onchange="updatePrice()">
                                                        <span class="checkbox-custom"></span>
                                                    </div>
                                                    <div class="addon-info">
                                                        <span class="addon-name"><?php echo $addon->name; ?></span>
                                                        <span class="addon-price">
                                                            <?php 
                                                                if ($addon->price > 0) {
                                                                    echo '+€' . number_format($addon->price, 2);
                                                                } elseif ($addon->type == 'exclude') {
                                                                    echo t('Retirer', 'Remove');
                                                                } else {
                                                                    echo t('Gratuit', 'Free');
                                                                }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Quantity Selection -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3 class="section-title">
                                    <i class="fas fa-box"></i> <?php echo t('Quantité', 'Quantity'); ?>
                                </h3>
                            </div>
                            <div class="quantity-control">
                                <button type="button" class="qty-btn qty-minus" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" readonly class="qty-input">
                                <button type="button" class="qty-btn qty-plus" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Price Summary Card -->
                        <div class="price-card">
                            <div class="price-details">
                                <div class="price-line">
                                    <span class="price-label"><?php echo t('Prix de base :', 'Base Price:'); ?></span>
                                    <span class="price-value" id="basePrice">€<?php echo number_format($product->sizes[0]->price ?? $product->price, 2); ?></span>
                                </div>
                                <div class="price-line" id="addonPriceRow" style="display: none;">
                                    <span class="price-label"><?php echo t('Suppléments :', 'Add-ons:'); ?></span>
                                    <span class="price-value addon-sum" id="addonPrice">€0.00</span>
                                </div>
                                <div class="price-line">
                                    <span class="price-label"><?php echo t('Quantité :', 'Quantity:'); ?></span>
                                    <span class="price-value qty-count" id="qtyDisplay">1</span>
                                </div>
                            </div>
                            <div class="price-divider"></div>
                            <div class="total-price-section">
                                <span class="total-label"><?php echo t('Total', 'Total'); ?></span>
                                <span class="total-amount" id="totalPrice">€<?php echo number_format($product->sizes[0]->price ?? $product->price, 2); ?></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart"></i>
                                <span><?php echo t('Ajouter au panier', 'Add to Cart'); ?></span>
                            </button>
                            <a href="<?php echo base_url('menu'); ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i>
                                <span><?php echo t('Continuer mes achats', 'Continue Shopping'); ?></span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Add to Cart Modal - Professional Modern Design */

.product-modal-inner {
    background: #fff;
    border-radius: 12px;
}

/* Product Section */
.product-section {
    padding: 0;
}

.product-wrapper {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 1rem;
    background: var(--white);
    padding: 1rem;
    border-radius: 12px;
}

/* Product Image Container */
.product-image-container {
    position: relative;
    height: 100%;
}

.image-frame {
    position: relative;
    width: 100%;
    background: linear-gradient(135deg, rgba(230, 126, 34, 0.05) 0%, rgba(52, 152, 219, 0.05) 100%);
    border-radius: 12px;
    padding: 0.75rem;
    overflow: hidden;
}

.product-image-main {
    width: 100%;
    aspect-ratio: 1.2;
    border-radius: 10px;
    overflow: hidden;
    background: var(--white);
}

.product-image-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.image-frame:hover .product-image-main img {
    transform: scale(1.05);
}

.badge-overlay {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.badge {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    border-radius: 14px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.badge-category {
    background: rgba(230, 126, 34, 0.95);
    color: var(--white);
}

.badge-subcategory {
    background: rgba(52, 152, 219, 0.95);
    color: var(--white);
}

/* Product Details Wrapper */
.product-details-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-info-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    padding-bottom: 0.4rem;
}

.product-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #e74c3c;
    margin-bottom: 0.2rem;
    line-height: 1.2;
}

.product-description {
    color: #222222;
    font-size: 0.8rem;
    line-height: 1.35;
    margin-bottom: 0.3rem;
}

.product-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: var(--dark-muted);
    font-size: 0.78rem;
    font-weight: 500;
}

.meta-item i {
    color: var(--primary);
    font-size: 0.9rem;
}

/* Customization Form */
.customization-form {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-section {
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    padding-bottom: 0.4rem;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-header {
    margin-bottom: 0.35rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.section-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin: 0;
}

.section-title i {
    font-size: 1rem;
    color: var(--primary);
}

.required-badge,
.optional-badge {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.15rem 0.4rem;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-left: auto;
}

.required-badge {
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
}

.optional-badge {
    background: rgba(52, 152, 219, 0.1);
    color: var(--primary);
}

/* Size Options Grid - Always Side-by-Side (2 Columns) */
.size-options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 0.5rem !important;
}

.size-card {
    position: relative;
    cursor: pointer;
}

.size-card input[type="radio"] {
    display: none;
}

.size-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0.4rem 0.5rem;
    border: 2px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    transition: all 0.2s ease;
    background: var(--white);
    min-height: auto;
    text-align: center;
    position: relative;
}

.size-card input[type="radio"]:checked + .size-card-content {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(230, 126, 34, 0.05) 0%, rgba(230, 126, 34, 0.02) 100%);
    box-shadow: 0 2px 10px rgba(230, 126, 34, 0.15);
}

.size-card:hover .size-card-content {
    border-color: rgba(230, 126, 34, 0.4);
}

.size-name {
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.1rem;
    font-size: 0.8rem;
}

.size-price {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--primary);
}

.checkmark {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--primary);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    opacity: 0;
    transform: scale(0);
    transition: all 0.2s ease;
}

.size-card input[type="radio"]:checked ~ .checkmark {
    opacity: 1;
    transform: scale(1);
}

/* Add-ons Showcase */
.addons-showcase {
    display: grid;
    gap: 0.4rem;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
}

.addon-card {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.6rem;
    border: 1.5px solid rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--white);
}

.addon-card:hover {
    border-color: rgba(230, 126, 34, 0.3);
}

.addon-checkbox {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.addon-checkbox input[type="checkbox"] {
    display: none;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 1.5px solid rgba(0, 0, 0, 0.2);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background: var(--white);
    position: relative;
}

.addon-checkbox input[type="checkbox"]:checked + .checkbox-custom {
    background: var(--primary);
    border-color: var(--primary);
}

.addon-checkbox input[type="checkbox"]:checked + .checkbox-custom::after {
    content: '✓';
    color: var(--white);
    font-size: 0.75rem;
    font-weight: bold;
}

.addon-info {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.addon-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.78rem;
}

.addon-price {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.78rem;
    white-space: nowrap;
}

/* Quantity Control - Small & Compact */
.quantity-control {
    display: flex;
    align-items: center;
    gap: 0;
    width: fit-content;
    background: rgba(0, 0, 0, 0.03);
    border-radius: 8px;
    padding: 0.15rem;
    border: 1px solid rgba(0, 0, 0, 0.08);
}

.qty-btn {
    width: 30px;
    height: 30px;
    border: none;
    background: var(--white);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 0.85rem;
    font-weight: 600;
}

.qty-btn:hover {
    background: var(--primary);
    color: var(--white);
}

.qty-input {
    width: 45px;
    padding: 0.2rem;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--dark);
}

/* Price Card - Small & Compact */
.price-card {
    background: linear-gradient(135deg, rgba(230, 126, 34, 0.08) 0%, rgba(52, 152, 219, 0.08) 100%);
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: 1px solid rgba(230, 126, 34, 0.2);
}

.price-details {
    margin-bottom: 0.3rem;
}

.price-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.2rem;
    color: var(--dark);
}

.price-line:last-child {
    margin-bottom: 0;
}

.price-label {
    font-weight: 600;
    font-size: 0.78rem;
    color: var(--dark-muted);
}

.price-value {
    font-weight: 600;
    font-size: 0.82rem;
    color: var(--dark);
}

.addon-sum {
    color: var(--primary);
}

.qty-count {
    background: rgba(230, 126, 34, 0.1);
    color: var(--primary);
    padding: 0.1rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
}

.price-divider {
    height: 1px;
    background: rgba(230, 126, 34, 0.3);
    margin: 0.3rem 0;
}

.total-price-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--dark);
}

.total-amount {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--primary);
    letter-spacing: -0.3px;
}

/* Action Buttons - Side-by-Side */
.action-buttons-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-top: 0.4rem;
}

.btn {
    padding: 0.55rem 0.8rem;
    border: none;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    text-decoration: none;
}

.btn-lg {
    min-height: 38px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, #e67e22 100%);
    color: var(--white);
    box-shadow: 0 2px 10px rgba(230, 126, 34, 0.3);
}

.btn-secondary {
    background: var(--white);
    color: var(--dark);
    border: 1.5px solid rgba(0, 0, 0, 0.1);
}

.btn i {
    font-size: 0.95rem;
}

/* Mobile & Small Screen Responsive Rules */
@media (max-width: 768px) {
    .product-wrapper {
        grid-template-columns: 1fr;
        gap: 0.6rem;
        padding: 0.75rem;
    }

    .product-title {
        font-size: 1.1rem;
    }

    .product-image-main {
        aspect-ratio: 1.4;
    }

    .size-options-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.4rem !important;
    }

    .size-card-content {
        min-height: auto;
        padding: 0.4rem 0.5rem;
    }

    .action-buttons-group {
        grid-template-columns: 1fr 1fr !important;
        gap: 0.4rem;
    }

    .btn {
        padding: 0.5rem 0.4rem;
        font-size: 0.78rem;
    }

    .btn-lg {
        min-height: 36px;
    }

    .price-card {
        padding: 0.5rem 0.7rem;
    }

    .total-amount {
        font-size: 1.1rem;
    }
}

.addon-group-section {
    margin-bottom: 0.8rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.addon-group-section:last-child {
    border-bottom: none;
    margin-bottom: 0.4rem;
    padding-bottom: 0;
}

.addon-group-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: auto;
}

.selection-hint {
    display: inline-block;
    font-size: 0.72rem;
    color: var(--dark-muted);
    background: rgba(52, 152, 219, 0.08);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    border-left: 2px solid var(--primary);
}

.addon-card input[type="checkbox"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.addon-card.disabled {
    opacity: 0.6;
    pointer-events: none;
}

.addon-selection-error {
    color: #e74c3c;
    font-size: 0.75rem;
    margin-top: 0.3rem;
    display: none;
}

.addon-selection-error.show {
    display: block;
}

@media (max-width: 480px) {
    .product-wrapper {
        padding: 0.6rem;
        gap: 0.5rem;
    }

    .product-title {
        font-size: 1.05rem;
    }

    .section-title {
        font-size: 0.8rem;
    }

    .size-options-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.4rem !important;
    }

    .action-buttons-group {
        grid-template-columns: 1fr 1fr !important;
    }
}
</style>

<script>
function changeQuantity(amount) {
    const qtyInput = document.getElementById('quantity');
    let newQty = parseInt(qtyInput.value) + amount;
    if (newQty < 1) newQty = 1;
    qtyInput.value = newQty;
    updatePrice();
}

function updatePrice() {
    // Get selected size details
    const sizeInput = document.querySelector('input[name="product_size"]:checked');
    const basePrice = sizeInput ? parseFloat(sizeInput.value) : <?php echo floatval($product->price); ?>;
    const selectedSizeName = sizeInput && sizeInput.dataset.name ? sizeInput.dataset.name.toLowerCase().trim() : '';
    const selectedSizeId = sizeInput && sizeInput.dataset.sizeId ? sizeInput.dataset.sizeId : '';

    // Update prices on all addon inputs based on selected size
    const allAddonCheckboxes = document.querySelectorAll('input[name*="addon_group_"], input[name="product_addon"]');
    allAddonCheckboxes.forEach(cb => {
        let currentPrice = parseFloat(cb.dataset.basePrice || cb.dataset.price || 0);
        
        let sizePricesByName = {};
        let sizePricesById = {};
        try {
            if (cb.dataset.sizePrices) sizePricesByName = JSON.parse(cb.dataset.sizePrices);
            if (cb.dataset.sizePricesId) sizePricesById = JSON.parse(cb.dataset.sizePricesId);
        } catch(e) {}

        if (selectedSizeId && sizePricesById[selectedSizeId] !== undefined) {
            currentPrice = parseFloat(sizePricesById[selectedSizeId]);
        } else if (selectedSizeName) {
            let matchedPrice = undefined;
            for (let nameKey in sizePricesByName) {
                if (selectedSizeName.includes(nameKey) || nameKey.includes(selectedSizeName)) {
                    matchedPrice = sizePricesByName[nameKey];
                    break;
                }
            }
            if (matchedPrice !== undefined) {
                currentPrice = parseFloat(matchedPrice);
            }
        }

        cb.dataset.price = currentPrice;

        const card = cb.closest('.addon-card');
        const priceSpan = card ? card.querySelector('.addon-price') : null;
        if (priceSpan) {
            if (currentPrice > 0) {
                priceSpan.innerText = '+€' + currentPrice.toFixed(2);
            } else {
                priceSpan.innerText = (typeof window.t === 'function') ? window.t('Gratuit', 'Free') : 'Free';
            }
        }
    });

    // Calculate sum of selected addons
    let addonPrice = 0;
    const checkedAddons = document.querySelectorAll('input[name*="addon_group_"]:checked, input[name="product_addon"]:checked');
    checkedAddons.forEach(input => {
        addonPrice += parseFloat(input.dataset.price || 0);
    });

    // Get quantity
    const qtyEl = document.getElementById('quantity');
    const quantity = qtyEl ? parseInt(qtyEl.value) : 1;

    // Update display
    const basePriceEl = document.getElementById('basePrice');
    if (basePriceEl) basePriceEl.innerText = '€' + basePrice.toFixed(2);
    
    const addonPriceRow = document.getElementById('addonPriceRow');
    const addonPriceEl = document.getElementById('addonPrice');
    if (addonPriceRow && addonPriceEl) {
        if (addonPrice > 0) {
            addonPriceRow.style.display = 'flex';
            addonPriceEl.innerText = '€' + addonPrice.toFixed(2);
        } else {
            addonPriceRow.style.display = 'none';
        }
    }

    const qtyDisplayEl = document.getElementById('qtyDisplay');
    if (qtyDisplayEl) qtyDisplayEl.innerText = quantity;

    // Calculate total
    const total = (basePrice + addonPrice) * quantity;
    const totalPriceEl = document.getElementById('totalPrice');
    if (totalPriceEl) totalPriceEl.innerText = '€' + total.toFixed(2);
}

/**
 * Validate addon group selections
 */
function validateAddonGroup(checkbox) {
    const groupId = checkbox.dataset.groupId;
    const maxSelections = parseInt(checkbox.dataset.maxSelections);
    
    // Get all checkboxes in this group
    const groupCheckboxes = document.querySelectorAll(`input[data-group-id="${groupId}"]`);
    const checkedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
    
    if (checkedCount > maxSelections) {
        checkbox.checked = false;
        alert(window.t(`Vous pouvez sélectionner au maximum ${maxSelections} article(s) pour ce groupe`, `You can select maximum ${maxSelections} item(s) for this group`));
        return false;
    }
    
    // Update disabled state for unchecked boxes
    groupCheckboxes.forEach(cb => {
        if (!cb.checked && checkedCount >= maxSelections) {
            cb.disabled = true;
            cb.parentElement.parentElement.classList.add('disabled');
        } else {
            cb.disabled = false;
            cb.parentElement.parentElement.classList.remove('disabled');
        }
    });
    
    return true;
}

// Initialization logic when injected via AJAX
(function() {
    updatePrice();

    const form = document.getElementById('addToCartForm');
    // Remove old listeners to prevent duplicates if modal is opened multiple times
    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);

    newForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Get form data
        const productId = document.querySelector('input[name="product_id"]').value;
        const quantity = document.getElementById('quantity').value;
        const sizeInput = document.querySelector('input[name="product_size"]:checked');
        const hasSizes = document.querySelectorAll('input[name="product_size"]').length > 0;
        const sizePrice = sizeInput ? sizeInput.value : 0;

        // Validate that size is selected only if sizes exist
        if (hasSizes && !sizeInput) {
            alert(window.t('Veuillez sélectionner une taille', 'Please select a size'));
            return;
        }

        // Get selected addons (old system)
        const addonInputs = document.querySelectorAll('input[name="product_addon"]:checked');
        const addonIds = Array.from(addonInputs).map(input => input.value);
        const addonPrices = Array.from(addonInputs).map(input => input.dataset.price);

        // Get selected addon groups (new system)
        const addonGroupInputs = document.querySelectorAll('input[name*="addon_group_"]:checked');
        const addonGroupIds = Array.from(addonGroupInputs).map(input => input.value);
        const addonGroupPrices = Array.from(addonGroupInputs).map(input => input.dataset.price);

        // Prepare FormData for proper array serialization
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('size_price', sizePrice);
        
        // Add old addon system values
        addonIds.forEach(id => formData.append('addon_ids[]', id));
        addonPrices.forEach(price => formData.append('addon_prices[]', price));

        // Add new addon group values
        addonGroupIds.forEach(id => formData.append('addon_group_ids[]', id));
        addonGroupPrices.forEach(price => formData.append('addon_group_prices[]', price));

        // Send AJAX request
        fetch('<?php echo base_url('cart/add_item'); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                // Update cart badge with animation
                const badge = document.getElementById('cartBadge');
                if (badge) {
                    badge.textContent = res.cart_count || 0;
                    badge.classList.remove('pulse');
                    setTimeout(() => badge.classList.add('pulse'), 10);
                    setTimeout(() => badge.classList.remove('pulse'), 610);
                }
                alert(res.message);
                
                // Close the modal
                if (typeof closeProductModal === 'function') {
                    closeProductModal();
                }
            } else {
                alert(res.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(window.t('Une erreur est survenue. Veuillez réessayer.', 'An error occurred. Please try again.'));
        });
    });
})();
</script>
