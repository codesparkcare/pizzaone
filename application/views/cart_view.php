<main class="cart-view-page">
    <!-- Cart Header -->
    <section class="cart-header">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> <?php echo t('Mon Panier', 'Shopping Cart'); ?></h1>
            <p><?php echo count($cart_items) . ' ' . t('article(s) dans votre panier', 'item(s) in your cart'); ?></p>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="cart-content section-padding">
        <div class="container">
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items-section">
                    <?php if (!empty($cart_items)): ?>
                        <div class="cart-items-list">
                            <?php foreach ($cart_items as $item_key => $item): ?>
                                <div class="cart-item" data-item-key="<?php echo $item_key; ?>">
                                    <div class="item-image">
                                        <img src="<?php echo base_url('assets/images/products/'.($item['product_image'] ? $item['product_image'] : 'default.png')); ?>" alt="<?php echo $item['product_name']; ?>">
                                    </div>
                                    <div class="item-details">
                                        <h3><?php echo $item['product_name']; ?></h3>
                                        <div class="item-specs">
                                            <?php 
                                                // Get size name
                                                $this->load->model('Common_model');
                                                $product = $this->Common_model->get_single('products', ['id' => $item['product_id']]);
                                                
                                                $this->db->select('sizes.name');
                                                $this->db->from('product_sizes');
                                                $this->db->join('sizes', 'sizes.id = product_sizes.size_id');
                                                $this->db->where('product_sizes.product_id', $item['product_id']);
                                                $this->db->where('product_sizes.price', $item['size_price']);
                                                $size = $this->db->get()->row();
                                            ?>
                                            <span class="spec-item">
                                                <strong><?php echo t('Taille :', 'Size:'); ?></strong> <?php echo $size->name ?? 'Standard'; ?>
                                            </span>
                                            
                                            <?php if (!empty($item['addon_ids'])): ?>
                                                <span class="spec-item">
                                                    <strong><?php echo t('Suppléments :', 'Add-ons:'); ?></strong> <?php echo count($item['addon_ids']) . ' ' . t('article(s)', 'item(s)'); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        <span class="price-label">€<?php echo number_format($item['size_price'], 2); ?></span>
                                        <?php if (!empty($item['addon_prices'])): ?>
                                            <span class="addon-label">+ <?php echo count($item['addon_prices']) . ' ' . t('suppléments', 'add-ons'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-quantity">
                                        <button class="qty-btn" onclick="updateQty('<?php echo $item_key; ?>', -1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" readonly data-item-key="<?php echo $item_key; ?>">
                                        <button class="qty-btn" onclick="updateQty('<?php echo $item_key; ?>', 1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="item-total">
                                        <span class="total-label">€<?php echo number_format($item['item_total'], 2); ?></span>
                                    </div>
                                    <button class="btn-remove" onclick="removeItem('<?php echo $item_key; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="continue-shopping">
                            <a href="<?php echo base_url('menu'); ?>" class="btn-link">
                                <i class="fas fa-arrow-left"></i> <?php echo t('Continuer mes achats', 'Continue Shopping'); ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Empty Cart -->
                        <div class="empty-cart">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h2><?php echo t('Votre panier est vide', 'Your cart is empty'); ?></h2>
                            <p><?php echo t('Il semble que vous n\'avez encore rien ajouté. Découvrez notre menu !', 'Looks like you haven\'t added anything yet. Start exploring our menu!'); ?></p>
                            <a href="<?php echo base_url('menu'); ?>" class="btn-primary"><?php echo t('Parcourir le menu', 'Browse Menu'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Cart Summary (Only show if cart has items) -->
                <?php if (!empty($cart_items)): ?>
                    <div class="cart-summary-section">
                        <div class="summary-card">
                            <h3><?php echo t('Récapitulatif de la commande', 'Order Summary'); ?></h3>
                            
                            <div class="summary-row">
                                <span><?php echo t('Sous-total', 'Subtotal'); ?></span>
                                <span class="amount">€<?php echo number_format($subtotal, 2); ?></span>
                            </div>

                            <div class="summary-row">
                                <span><?php echo t('TVA (10%)', 'Tax (10%)'); ?></span>
                                <span class="amount">€<?php echo number_format($tax, 2); ?></span>
                            </div>

                            <div class="summary-row">
                                <span><?php echo t('Livraison', 'Delivery'); ?></span>
                                <span class="amount">€5.00</span>
                            </div>

                            <div class="summary-row total">
                                <span><?php echo t('Total', 'Total'); ?></span>
                                <strong class="amount">€<?php echo number_format($total + 5, 2); ?></strong>
                            </div>

                            <div class="summary-actions">
                                <button id="btnCheckout" class="btn-checkout">
                                    <i class="fas fa-lock"></i> <?php echo t('Passer la commande', 'Proceed to Checkout'); ?>
                                </button>
                                <button class="btn-clear-cart" onclick="clearCart()">
                                    <i class="fas fa-times"></i> <?php echo t('Vider le panier', 'Clear Cart'); ?>
                                </button>
                            </div>

                            <div class="payment-methods">
                                <h4><?php echo t('Moyens de paiement', 'Payment Methods'); ?></h4>
                                <div class="methods-list">
                                    <div class="method">
                                        <i class="fas fa-credit-card"></i>
                                        <span><?php echo t('Carte de crédit', 'Credit Card'); ?></span>
                                    </div>
                                    <div class="method">
                                        <i class="fas fa-wallet"></i>
                                        <span><?php echo t('Carte de débit', 'Debit Card'); ?></span>
                                    </div>
                                    <div class="method">
                                        <i class="fas fa-money-bill"></i>
                                        <span><?php echo t('Espèces à la livraison', 'Cash on Delivery'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<style>
/* Cart View Page Styles */
.cart-view-page {
    min-height: 100vh;
    background: var(--light);
}

.cart-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: var(--white);
    padding: 3rem 0;
    margin-bottom: 3rem;
    box-shadow: var(--shadow);
}

.cart-header h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.cart-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Cart Layout */
.cart-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

/* Cart Items Section */
.cart-items-section {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 2rem;
}

.cart-items-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr 100px 120px 120px 50px;
    gap: 1.5rem;
    align-items: center;
    padding: 1.5rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    transition: var(--transition);
}

.cart-item:hover {
    box-shadow: var(--shadow-sm);
    border-color: var(--primary);
}

.item-image {
    width: 100px;
    height: 100px;
    overflow: hidden;
    border-radius: var(--radius);
    background: var(--light);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details h3 {
    font-size: 1.1rem;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.item-specs {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    font-size: 0.9rem;
    color: var(--dark-muted);
}

.spec-item {
    display: block;
}

.item-price {
    text-align: center;
}

.price-label {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.3rem;
}

.addon-label {
    display: block;
    font-size: 0.8rem;
    color: var(--dark-muted);
}

/* Quantity Controls */
.item-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border: 1px solid var(--border);
    background: var(--white);
    cursor: pointer;
    border-radius: 4px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dark);
}

.qty-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.qty-input {
    width: 50px;
    text-align: center;
    border: 1px solid var(--border);
    padding: 0.4rem;
    border-radius: 4px;
    font-weight: 600;
}

.item-total {
    text-align: center;
}

.total-label {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--secondary);
}

.btn-remove {
    width: 40px;
    height: 40px;
    border: none;
    background: var(--danger-light);
    color: var(--danger);
    border-radius: var(--radius);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.btn-remove:hover {
    background: var(--danger);
    color: var(--white);
}

/* Continue Shopping */
.continue-shopping {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border);
}

.btn-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.btn-link:hover {
    color: var(--primary-dark);
    transform: translateX(-5px);
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--primary);
    margin-bottom: 1rem;
    opacity: 0.3;
}

.empty-cart h2 {
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.empty-cart p {
    color: var(--dark-muted);
    margin-bottom: 2rem;
}

/* Cart Summary Section */
.cart-summary-section {
    position: sticky;
    top: 20px;
}

.summary-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 2rem;
}

.summary-card h3 {
    font-size: 1.3rem;
    color: var(--dark);
    margin-bottom: 1.5rem;
    border-bottom: 2px solid var(--border);
    padding-bottom: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    color: var(--dark);
    font-size: 0.95rem;
}

.summary-row .amount {
    font-weight: 600;
}

.summary-row.total {
    border-top: 2px solid var(--border);
    padding-top: 1rem;
    margin-top: 1rem;
    font-size: 1.1rem;
    font-weight: 700;
}

.summary-row.total .amount {
    color: var(--primary);
    font-size: 1.3rem;
}

/* Summary Actions */
.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    margin-top: 1.5rem;
}

.btn-checkout {
    padding: 1rem;
    background: var(--primary);
    color: var(--white);
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-checkout:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.btn-clear-cart {
    padding: 0.8rem;
    background: var(--light);
    color: var(--dark);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-clear-cart:hover {
    border-color: var(--danger);
    color: var(--danger);
}

/* Payment Methods */
.payment-methods {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.payment-methods h4 {
    color: var(--dark);
    margin-bottom: 1rem;
    font-size: 0.95rem;
    font-weight: 600;
}

.methods-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.method {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem;
    background: var(--light);
    border-radius: 4px;
    color: var(--dark-muted);
    font-size: 0.9rem;
}

.method i {
    color: var(--primary);
}

/* Responsive */
@media (max-width: 1024px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }

    .cart-summary-section {
        position: static;
    }
}

@media (max-width: 768px) {
    .cart-item {
        grid-template-columns: 80px 1fr 50px;
        gap: 1rem;
        padding: 1rem;
    }

    .item-image {
        width: 80px;
        height: 80px;
    }

    .item-price,
    .item-quantity,
    .item-total,
    .btn-remove {
        display: none;
    }

    .item-details {
        grid-column: 2;
    }

    .cart-items-section {
        padding: 1rem;
    }

    .cart-header h1 {
        font-size: 1.8rem;
    }
}
</style>

<script>
function updateQty(itemKey, amount) {
    const qtyInput = document.querySelector(`.qty-input[data-item-key="${itemKey}"]`);
    let newQty = parseInt(qtyInput.value) + amount;
    if (newQty < 1) newQty = 1;

    // Send AJAX request to update quantity
    fetch('<?php echo base_url('cart/update_quantity'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'item_key=' + encodeURIComponent(itemKey) + '&quantity=' + newQty
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            qtyInput.value = newQty;
            // Update cart display
            location.reload();
        }
    });
}

function removeItem(itemKey) {
    if (confirm(window.t('Voulez-vous retirer cet article du panier ?', 'Remove this item from cart?'))) {
        fetch('<?php echo base_url('cart/remove_item'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'item_key=' + encodeURIComponent(itemKey)
        })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                // Remove item from display
                document.querySelector(`.cart-item[data-item-key="${itemKey}"]`).remove();
                // Reload to update totals
                setTimeout(() => location.reload(), 300);
            }
        });
    }
}

function clearCart() {
    if (confirm(window.t('Voulez-vous vider tout votre panier ?', 'Clear your entire cart?'))) {
        fetch('<?php echo base_url('cart/clear'); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                location.reload();
            }
        });
    }
}
</script>
<script>
document.getElementById('btnCheckout').addEventListener('click', function() {
    window.location.href = '<?php echo base_url('cart/checkout'); ?>';
});
</script>
