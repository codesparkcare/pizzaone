<main class="cart-page">
    <!-- Product Header Hero -->
    <section class="product-hero" style="background: linear-gradient(135deg, rgba(230, 126, 34, 0.1) 0%, rgba(52, 152, 219, 0.1) 100%);">
        <div class="container">
            <nav class="breadcrumb-nav">
                <a href="<?php echo base_url('menu'); ?>" class="breadcrumb-link">
                    <i class="fas fa-arrow-left"></i> Back to Menu
                </a>
            </nav>
        </div>
    </section>

    <!-- Product Section -->
    <section class="product-section">
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
                                <span>100% Fresh Ingredients</span>
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
                                        <i class="fas fa-expand"></i> Choose Size
                                        <span class="required-badge">Required</span>
                                    </h3>
                                </div>
                                <div class="size-options-grid">
                                    <?php foreach ($product->sizes as $index => $size): ?>
                                        <label class="size-card">
                                            <input type="radio" name="product_size" 
                                                   value="<?php echo $size->price; ?>" 
                                                   data-id="<?php echo $size->ps_id; ?>"
                                                   data-name="<?php echo $size->name; ?>"
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
                                                        <span class="required-badge">Required</span>
                                                    <?php endif; ?>
                                                </h3>
                                                <div class="addon-group-info">
                                                    <?php 
                                                        if ($group->min_selections == 0 && $group->max_selections == 1) {
                                                            echo '<span class="selection-hint">Choose up to 1</span>';
                                                        } elseif ($group->max_selections > 1) {
                                                            echo '<span class="selection-hint">Choose up to ' . $group->max_selections . '</span>';
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
                                                                   data-price="<?php echo $addon->price; ?>"
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
                                                                        echo 'Free';
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
                                                <i class="fas fa-plus-circle"></i> Customize Your Order
                                                <span class="optional-badge">Optional</span>
                                            </h3>
                                        </div>
                                        <div class="addons-showcase">
                                            <?php foreach ($product->addons as $addon): ?>
                                                <label class="addon-card">
                                                    <div class="addon-checkbox">
                                                        <input type="checkbox" name="product_addon" 
                                                               value="<?php echo $addon->id; ?>"
                                                               data-price="<?php echo $addon->price; ?>"
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
                                                                    echo 'Remove';
                                                                } else {
                                                                    echo 'Free';
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
                                    <i class="fas fa-box"></i> Quantity
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
                                    <span class="price-label">Base Price:</span>
                                    <span class="price-value" id="basePrice" data-base-price="<?php echo number_format($product->sizes[0]->price ?? $product->price, 2); ?>">€<?php echo number_format($product->sizes[0]->price ?? $product->price, 2); ?></span>
                                </div>
                                <div class="price-line" id="addonPriceRow" style="display: none;">
                                    <span class="price-label">Add-ons:</span>
                                    <span class="price-value addon-sum" id="addonPrice">€0.00</span>
                                </div>
                                <div class="price-line">
                                    <span class="price-label">Quantity:</span>
                                    <span class="price-value qty-count" id="qtyDisplay">1</span>
                                </div>
                            </div>
                            <div class="price-divider"></div>
                            <div class="total-price-section">
                                <span class="total-label">Total</span>
                                <span class="total-amount" id="totalPrice">€<?php echo number_format($product->sizes[0]->price ?? $product->price, 2); ?></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Add to Cart</span>
                            </button>
                            <a href="<?php echo base_url('menu'); ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i>
                                <span>Continue Shopping</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Global Modal -->
    <div id="globalModal" class="modal-overlay">
        <div class="modal-content">
            <p id="modalMessage"></p>
            <button class="close-btn">Close</button>
        </div>
    </div>
</section>
</main>

<style>
/* Add to Cart Page - Professional Modern Design */

.cart-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
    padding-top: 2rem;
}

/* Product Hero Section */
.product-hero {
    padding: 1.5rem 0;
    margin-bottom: 2rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
}

.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.breadcrumb-link:hover {
    background: rgba(230, 126, 34, 0.1);
    color: var(--secondary);
    transform: translateX(-3px);
}

/* Product Section */
.product-section {
    padding: 3rem 0;
}

.product-wrapper {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 4rem;
    background: var(--white);
    padding: 3rem;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Product Image Container */
.product-image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.image-frame {
    position: relative;
    width: 100%;
    background: linear-gradient(135deg, rgba(230, 126, 34, 0.05) 0%, rgba(52, 152, 219, 0.05) 100%);
    border-radius: 16px;
    padding: 2rem;
    overflow: hidden;
}

.product-image-main {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 12px;
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
    top: 1.5rem;
    left: 1.5rem;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
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
    gap: 2rem;
}

.product-info-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    padding-bottom: 2rem;
}

.product-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 1rem;
    line-height: 1.2;
    letter-spacing: -0.5px;
}

.product-description {
    color: var(--dark-muted);
    font-size: 1.05rem;
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

.product-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--dark-muted);
    font-size: 0.95rem;
    font-weight: 500;
}

.meta-item i {
    color: var(--primary);
    font-size: 1.1rem;
}

/* Customization Form */
.customization-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-section {
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    padding-bottom: 2rem;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.section-title i {
    font-size: 1.3rem;
    color: var(--primary);
}

.required-badge,
.optional-badge {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
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

/* Size Options Grid */
.size-options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
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
    padding: 1.5rem;
    border: 2px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: var(--white);
    min-height: 120px;
    text-align: center;
    position: relative;
}

.size-card input[type="radio"]:checked + .size-card-content {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(230, 126, 34, 0.05) 0%, rgba(230, 126, 34, 0.02) 100%);
    box-shadow: 0 4px 20px rgba(230, 126, 34, 0.15);
    transform: translateY(-2px);
}

.size-card:hover .size-card-content {
    border-color: rgba(230, 126, 34, 0.4);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.size-name {
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.6rem;
    font-size: 1rem;
}

.size-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
}

.checkmark {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--primary);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    opacity: 0;
    transform: scale(0);
    transition: all 0.3s ease;
}

.size-card input[type="radio"]:checked ~ .checkmark {
    opacity: 1;
    transform: scale(1);
}

/* Add-ons Showcase */
.addons-showcase {
    display: grid;
    gap: 0.8rem;
}

.addon-card {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    padding: 1.2rem;
    border: 2px solid rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: var(--white);
}

.addon-card:hover {
    border-color: rgba(230, 126, 34, 0.3);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
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
    width: 24px;
    height: 24px;
    border: 2px solid rgba(0, 0, 0, 0.2);
    border-radius: 6px;
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
    font-size: 0.85rem;
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
    font-size: 0.95rem;
}

.addon-price {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.9rem;
    white-space: nowrap;
}

/* Quantity Control */
.quantity-control {
    display: flex;
    align-items: center;
    gap: 0;
    width: fit-content;
    background: rgba(0, 0, 0, 0.03);
    border-radius: 12px;
    padding: 0.5rem;
    border: 1px solid rgba(0, 0, 0, 0.08);
}

.qty-btn {
    width: 44px;
    height: 44px;
    border: none;
    background: var(--white);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1rem;
    font-weight: 600;
}

.qty-btn:hover {
    background: var(--primary);
    color: var(--white);
    transform: scale(1.05);
}

.qty-input {
    width: 80px;
    padding: 0.8rem;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
}

/* Price Card */
.price-card {
    background: linear-gradient(135deg, rgba(230, 126, 34, 0.08) 0%, rgba(52, 152, 219, 0.08) 100%);
    padding: 2rem;
    border-radius: 14px;
    border: 1px solid rgba(230, 126, 34, 0.2);
}

.price-details {
    margin-bottom: 1.5rem;
}

.price-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    color: var(--dark);
}

.price-line:last-child {
    margin-bottom: 0;
}

.price-label {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--dark-muted);
}

.price-value {
    font-weight: 600;
    font-size: 1rem;
    color: var(--dark);
}

.addon-sum {
    color: var(--primary);
}

.qty-count {
    background: rgba(230, 126, 34, 0.1);
    color: var(--primary);
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    font-size: 0.9rem;
}

.price-divider {
    height: 1px;
    background: rgba(230, 126, 34, 0.3);
    margin: 1.5rem 0;
}

.total-price-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-label {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
}

.total-amount {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
    letter-spacing: -0.5px;
}

/* Action Buttons */
.action-buttons-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 1rem;
}

.btn {
    padding: 1.1rem 2rem;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    text-decoration: none;
    letter-spacing: 0.3px;
}

.btn-lg {
    min-height: 56px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, #e67e22 100%);
    color: var(--white);
    box-shadow: 0 4px 15px rgba(230, 126, 34, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(230, 126, 34, 0.4);
}

.btn-primary:active {
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--white);
    color: var(--dark);
    border: 2px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

.btn i {
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .product-wrapper {
        grid-template-columns: 1fr;
        gap: 3rem;
        padding: 2.5rem;
    }

    .product-title {
        font-size: 2rem;
    }

    .size-options-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }
}

@media (max-width: 768px) {
    .cart-page {
        padding-top: 1rem;
    }

    .product-section {
        padding: 1.5rem 0;
    }

    .product-wrapper {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .product-title {
        font-size: 1.75rem;
    }

    .product-description {
        font-size: 0.95rem;
    }

    .size-options-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .size-card-content {
        min-height: 100px;
        padding: 1.2rem;
    }

    .addons-showcase {
        gap: 0.6rem;
    }

    .addon-card {
        flex-direction: column;
        text-align: center;
        gap: 0.8rem;
        padding: 1rem;
    }

    .addon-info {
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }

    .action-buttons-group {
        grid-template-columns: 1fr;
        gap: 0.8rem;
    }

    .btn {
        padding: 1rem 1.5rem;
    }

    .btn-lg {
        min-height: 50px;
    }

    .section-title {
        font-size: 1rem;
    }

    .price-card {
        padding: 1.5rem;
    }

    .total-amount {
        font-size: 1.5rem;
    }
}

/* Addon Groups Styling */
.addon-group-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
}

.addon-group-section:last-child {
    border-bottom: none;
    margin-bottom: 2rem;
    padding-bottom: 0;
}

.addon-group-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-left: auto;
}

.selection-hint {
    display: inline-block;
    font-size: 0.85rem;
    color: var(--dark-muted);
    background: rgba(52, 152, 219, 0.08);
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    border-left: 3px solid var(--primary);
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
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: none;
}

.addon-selection-error.show {
    display: block;
}

        /* Global Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: #fff;
            padding: 1.5rem 2rem;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            text-align: center;
            font-family: 'Inter', sans-serif;
        }
        .modal-content p {
            margin: 0 0 1rem;
            font-size: 1rem;
            color: #2c3e50;
        }
        .modal-content .close-btn {
            background: #2ecc71;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .modal-content.error p { color: #e74c3c; }

@media (max-width: 480px) {
    .product-wrapper {
        padding: 1rem;
        gap: 1.5rem;
    }

    .product-title {
        font-size: 1.4rem;
    }

    .section-title {
        font-size: 0.95rem;
    }

    .size-options-grid {
        grid-template-columns: 1fr;
    }

    .quantity-control {
        width: 100%;
        justify-content: center;
    }

    .qty-input {
        width: 60px;
    }

    .price-card {
        padding: 1.2rem;
    }

    .btn {
        font-size: 0.9rem;
        padding: 0.9rem 1.2rem;
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
    // Get selected size price
    const sizeInput = document.querySelector('input[name="product_size"]:checked');
const basePriceElement = document.getElementById('basePrice');
const basePrice = sizeInput ? parseFloat(sizeInput.value) : parseFloat(basePriceElement.dataset.basePrice);

    // Get selected addons prices (old system)
    let addonPrice = 0;
    const addonInputs = document.querySelectorAll('input[name="product_addon"]:checked');
    addonInputs.forEach(input => {
        addonPrice += parseFloat(input.dataset.price);
    });

    // Get selected addon group items prices (new system)
    const addonGroupInputs = document.querySelectorAll('input[name*="addon_group_"]:checked');
    addonGroupInputs.forEach(input => {
        addonPrice += parseFloat(input.dataset.price);
    });

    // Get quantity
    const quantity = parseInt(document.getElementById('quantity').value);

    // Update display
    document.getElementById('basePrice').innerText = '€' + basePrice.toFixed(2);
    
    if (addonPrice > 0) {
        document.getElementById('addonPriceRow').style.display = 'flex';
        document.getElementById('addonPrice').innerText = '€' + addonPrice.toFixed(2);
    } else {
        document.getElementById('addonPriceRow').style.display = 'none';
    }

    document.getElementById('qtyDisplay').innerText = quantity;

    // Calculate total
    const total = (basePrice + addonPrice) * quantity;
    document.getElementById('totalPrice').innerText = '€' + total.toFixed(2);
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
        showModal(`You can select maximum ${maxSelections} item(s) for this group`, true);
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

// Handle form submission
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form data
    const productId = document.querySelector('input[name="product_id"]').value;
    const quantity = document.getElementById('quantity').value;
    // Determine size price if applicable
    const sizeInput = document.querySelector('input[name="product_size"]:checked');
    const sizePrice = sizeInput ? parseFloat(sizeInput.value) : 0;
    // If product has sizes but none selected, enforce selection
    const sizeOptions = document.querySelectorAll('input[name="product_size"]');
    if (sizeOptions.length > 0 && !sizeInput) {
        showModal('Please select a size', true);
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
                // Trigger pulse animation
                badge.classList.remove('pulse');
                setTimeout(() => badge.classList.add('pulse'), 10);
                // Remove animation class after animation completes
                setTimeout(() => badge.classList.remove('pulse'), 610);
            }
            // Show success message
            showModal(res.message);
            // Redirect to cart or menu
            window.location.href = '<?php echo base_url('cart/view'); ?>';
        } else {
            showModal('Error: ' + res.message, true);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModal('An error occurred. Please try again.', true);
    });
});

// Show modal function
function showModal(message, isError = false) {
    const overlay = document.getElementById('globalModal');
    const msgElem = document.getElementById('modalMessage');
    const modalContent = document.querySelector('#globalModal .modal-content');
    msgElem.textContent = message;
    if (isError) {
        modalContent.classList.add('error');
    } else {
        modalContent.classList.remove('error');
    }
    overlay.style.display = 'flex';
}

// Close modal handler
document.addEventListener('click', function(e) {
    if (e.target.id === 'globalModal' || e.target.classList.contains('close-btn')) {
        e.target.closest('#globalModal').style.display = 'none';
    }
});

// Initialize price on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePrice();
});
</script>
