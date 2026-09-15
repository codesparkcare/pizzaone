<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('assets/css/checkout.css'); ?>">

<style>
.checkout-user-badge {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    font-size: 0.88rem;
}
.checkout-user-badge .user-name {
    font-weight: 600;
    color: #1e293b;
}
.address-card-option {
    display: block;
    position: relative;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px 12px 42px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.address-card-option:hover {
    border-color: #cbd5e1;
    background: #fafafa;
}
.address-card-option.selected {
    border-color: #e74c3c;
    background: #fff5f5;
}
.address-card-option input[type="radio"] {
    position: absolute;
    top: 14px;
    left: 14px;
    margin: 0;
    accent-color: #e74c3c;
    width: 18px;
    height: 18px;
}
.address-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #f1f5f9;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 4px;
    text-transform: uppercase;
    margin-right: 6px;
}
.address-badge.default {
    background: #e0e7ff;
    color: #3730a3;
}
.new-address-toggle {
    display: block;
    position: relative;
    background: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 12px 16px 12px 42px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.new-address-toggle:hover {
    border-color: #e74c3c;
    background: #fff5f5;
}
.new-address-toggle.selected {
    border-color: #e74c3c;
    background: #fff5f5;
}
.new-address-toggle input[type="radio"] {
    position: absolute;
    top: 14px;
    left: 14px;
    margin: 0;
    accent-color: #e74c3c;
    width: 18px;
    height: 18px;
}
.collect-notice-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 14px 16px;
    color: #166534;
    font-size: 0.9rem;
    margin-bottom: 15px;
    display: none;
}
</style>

<div class="container">
    <div class="left">
        <h2><?php echo t('Détails du client', 'Customer Details'); ?></h2>
        
        <?php if ($this->session->flashdata('checkout_error')): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.9rem;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('checkout_error'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($user)): ?>
            <!-- Logged-in customer notice -->
            <div class="checkout-user-badge">
                <div>
                    <i class="fas fa-user-check" style="color: #10b981; margin-right: 6px;"></i>
                    <span class="user-name"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></span>
                    <span style="color: #64748b; font-size: 0.82rem; margin-left: 6px;">(<?php echo htmlspecialchars($user->email); ?>)</span>
                </div>
                <a href="<?php echo base_url('user/account'); ?>" style="color: #e74c3c; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                    <?php echo t('Gérer profil', 'Manage Profile'); ?> &rarr;
                </a>
            </div>
        <?php else: ?>
            <!-- Guest customer notice -->
            <div class="checkout-user-badge" style="background: #fffbeb; border-color: #fde68a;">
                <div>
                    <i class="fas fa-info-circle" style="color: #d97706; margin-right: 6px;"></i>
                    <span style="color: #92400e; font-weight: 500;"><?php echo t('Commande en tant qu\'invité', 'Ordering as Guest'); ?></span>
                </div>
                <a href="<?php echo base_url('user/login'); ?>" style="color: #d97706; font-weight: 600; text-decoration: underline; font-size: 0.82rem;">
                    <?php echo t('Déjà un compte ? Connexion', 'Have an account? Log in'); ?>
                </a>
            </div>
        <?php endif; ?>

        <form id="checkoutForm" method="POST" action="<?php echo base_url('cart/place_order'); ?>">
            <!-- Order Type -->
            <div class="form-group">
                <label><?php echo t('Type de commande', 'Order Type'); ?></label>
                <div class="radio-group" style="display: flex; gap: 20px;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <input type="radio" name="order_type" value="collect" id="typeCollect" onchange="toggleOrderType('collect')"> 
                        <span><i class="fas fa-store"></i> <?php echo t('À emporter', 'Collect'); ?></span>
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <input type="radio" name="order_type" value="delivery" id="typeDelivery" checked onchange="toggleOrderType('delivery')"> 
                        <span><i class="fas fa-motorcycle"></i> <?php echo t('Livraison', 'Delivery'); ?></span>
                    </label>
                </div>
            </div>

            <!-- Shop Location -->
            <?php $selected_shop_id = $this->session->userdata('selected_shop_id') ?: '1'; ?>
            <div class="form-group">
                <label for="shop"><?php echo t('Sélectionner le magasin', 'Select Shop Location'); ?></label>
                <select name="shop_id" id="shop" required onchange="updateShopCard(this.value)">
                    <option value="" disabled><?php echo t('Choisir un magasin', 'Choose a shop'); ?></option>
                    <option value="1" <?php echo ($selected_shop_id == '1') ? 'selected' : ''; ?>>Villiers-le-bel (11 Place de la Tolinette, 95400 Villiers Le Bel)</option>
                    <option value="2" <?php echo ($selected_shop_id == '2') ? 'selected' : ''; ?>>Le Plessis-Bouchard (Commercial des Hauts de Saint-Nicolas 95130 Le Plessis-Bouchard)</option>
                </select>
            </div>

            <!-- Name & Phone -->
            <?php
                $default_name = !empty($user) ? trim($user->first_name . ' ' . $user->last_name) : '';
                $default_phone = !empty($user->phone) ? $user->phone : '';
            ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="name"><?php echo t('Nom', 'Name'); ?> <span style="color: #e74c3c;">*</span></label>
                    <input type="text" id="name" name="customer_name" value="<?php echo htmlspecialchars($default_name); ?>" required placeholder="<?php echo t('Votre nom', 'Your name'); ?>">
                </div>
                <div class="form-group">
                    <label for="phone"><?php echo t('Numéro de téléphone', 'Phone Number'); ?> <span style="color: #e74c3c;">*</span></label>
                    <input type="tel" id="phone" name="customer_phone" value="<?php echo htmlspecialchars($default_phone); ?>" required placeholder="06 12 34 56 78">
                </div>
            </div>

            <!-- Collect Store Notice (Shown when Collect is selected) -->
            <div id="collectNoticeBox" class="collect-notice-box">
                <i class="fas fa-store-alt" style="margin-right: 6px;"></i>
                <strong><?php echo t('Retrait en magasin :', 'Pickup at store:'); ?></strong>
                <span id="collectNoticeText">
                    <?php echo ($selected_shop_id == '2') ? 'Le Plessis-Bouchard (Commercial des Hauts de Saint-Nicolas)' : 'Villiers-le-bel (11 Place de la Tolinette)'; ?>
                </span>
                <div style="font-size: 0.8rem; color: #15803d; margin-top: 4px;">
                    <?php echo t('Aucune adresse de livraison nécessaire. Votre commande sera préparée pour vous au comptoir.', 'No delivery address needed. Your order will be prepared for you at the counter.'); ?>
                </div>
            </div>

            <!-- Delivery Address Section (Shown when Delivery is selected) -->
            <div id="deliveryAddressSection">
                <div class="form-group">
                    <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                        <i class="fas fa-map-marker-alt" style="color: #e74c3c;"></i>
                        <?php echo t('Adresse de livraison', 'Delivery Address'); ?> <span style="color: #e74c3c;">*</span>
                    </label>

                    <?php if (!empty($user_addresses)): ?>
                        <!-- Logged-in Customer with Saved Addresses -->
                        <div id="savedAddressesList">
                            <?php foreach ($user_addresses as $idx => $addr): ?>
                                <label class="address-card-option <?php echo ($addr->is_default || $idx === 0) ? 'selected' : ''; ?>" id="addr-label-<?php echo $addr->id; ?>">
                                    <input type="radio" name="selected_address_id" value="<?php echo $addr->id; ?>" <?php echo ($addr->is_default || $idx === 0) ? 'checked' : ''; ?> onchange="handleAddressOptionChange(this)">
                                    <div>
                                        <span class="address-badge <?php echo $addr->is_default ? 'default' : ''; ?>">
                                            <?php echo htmlspecialchars($addr->label ?: 'Maison'); ?>
                                        </span>
                                        <?php if ($addr->is_default): ?>
                                            <span style="font-size: 0.72rem; color: #3730a3; font-weight: 600;">(<?php echo t('Par défaut', 'Default'); ?>)</span>
                                        <?php endif; ?>
                                        <div style="font-size: 0.92rem; color: #334155; margin-top: 4px; font-weight: 500;">
                                            <?php echo htmlspecialchars($addr->address); ?>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>

                            <!-- Option to enter another address -->
                            <label class="new-address-toggle" id="addr-label-new">
                                <input type="radio" name="selected_address_id" value="new" id="radioAddrNew" onchange="handleAddressOptionChange(this)">
                                <div style="font-weight: 600; color: #334155; font-size: 0.9rem;">
                                    <i class="fas fa-plus-circle" style="color: #e74c3c; margin-right: 4px;"></i>
                                    <?php echo t('+ Livrer à une autre adresse', '+ Deliver to another address'); ?>
                                </div>
                            </label>
                        </div>

                        <!-- Expandable box for entering a new address -->
                        <div id="newAddressBox" style="display: none; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 14px; margin-top: 10px;">
                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <input type="text" name="address_label" placeholder="<?php echo t('Nom de l\'adresse (ex: Bureau, Parents)', 'Address label (e.g. Work, Parents)'); ?>" style="flex: 1; padding: 8px 12px; font-size: 0.88rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                            <input type="text" id="newAddressInput" name="customer_address" placeholder="<?php echo t('Numéro, Rue, Ville, Code Postal', 'Street address, City, Postal code'); ?>" style="width: 100%; padding: 10px 12px; font-size: 0.92rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                            <label style="display: flex; align-items: center; gap: 8px; margin-top: 10px; cursor: pointer; font-size: 0.85rem; color: #475569; font-weight: 500;">
                                <input type="checkbox" name="save_new_address" value="1" checked style="accent-color: #e74c3c; width: 16px; height: 16px;">
                                <?php echo t('Enregistrer cette adresse sur mon compte pour mes prochaines commandes', 'Save this address to my account for future orders'); ?>
                            </label>
                        </div>

                    <?php elseif (!empty($user)): ?>
                        <!-- Logged in but no saved addresses yet -->
                        <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 14px;">
                            <input type="text" id="customer_address" name="customer_address" placeholder="<?php echo t('Numéro, Rue, Ville, Code Postal', 'Street address, City, Postal code'); ?>" required style="width: 100%; padding: 10px 12px; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <input type="text" name="address_label" value="Maison" placeholder="<?php echo t('Libellé (ex: Maison)', 'Label (e.g. Home)'); ?>" style="width: 140px; padding: 6px 10px; font-size: 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; color: #475569;">
                                    <input type="checkbox" name="save_new_address" value="1" checked style="accent-color: #e74c3c; width: 16px; height: 16px;">
                                    <?php echo t('Enregistrer sur mon compte', 'Save to my account'); ?>
                                </label>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Guest Customer Manual Address -->
                        <input type="text" id="customer_address" name="customer_address" placeholder="<?php echo t('Numéro, Rue, Ville, Code Postal', 'Street address, City, Postal code'); ?>" required style="width: 100%; padding: 10px 14px; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box;">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes"><?php echo t('Remarques (optionnel)', 'Notes (optional)'); ?></label>
                <input type="text" id="notes" name="notes" placeholder="<?php echo t('Instructions particulières (ex: code porte, étage)', 'Special instructions (e.g. door code, floor)'); ?>">
            </div>

            <!-- Payment Method -->
            <div class="form-group">
                <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px;"><?php echo t('Moyen de paiement', 'Payment Method'); ?></label>
                <?php if (!empty($payment_methods)): ?>
                    <?php foreach ($payment_methods as $idx => $pm): ?>
                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="<?php echo htmlspecialchars($pm->payment_key); ?>" <?php echo ($idx === 0) ? 'checked' : ''; ?> required style="accent-color: #e74c3c; width: 18px; height: 18px; margin: 0;">
                            <i class="<?php echo htmlspecialchars($pm->icon ?: 'fas fa-credit-card'); ?>" style="color: #64748b;"></i>
                            <span style="font-weight: 500; color: #1e293b;"><?php echo t($pm->name_fr, $pm->name_en); ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 10px 14px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; color: #e11d48; font-size: 0.9rem; margin-bottom: 10px;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo t('Aucun moyen de paiement disponible actuellement. Veuillez contacter le restaurant.', 'No payment methods are currently available. Please contact the restaurant.'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn" id="placeOrderBtn" <?php echo empty($payment_methods) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                <i class="fas fa-check-circle" style="margin-right: 6px;"></i> <?php echo t('Valider la commande', 'Place Order'); ?>
            </button>
        </form>
    </div>

    <div class="right">
        <h2><?php echo t('Récapitulatif de la commande', 'Order Summary'); ?></h2>
        <?php 
            $is_shop2 = ($selected_shop_id == '2');
            $box_bg = $is_shop2 ? '#eff6ff' : '#fff5f5';
            $box_border = $is_shop2 ? '#bfdbfe' : '#fecaca';
            $box_color = $is_shop2 ? '#2563eb' : '#e74c3c';
        ?>
        <div id="selectedShopCard" style="background: <?php echo $box_bg; ?>; border: 1px solid <?php echo $box_border; ?>; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
            <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: <?php echo $box_color; ?>; font-weight: 700;">
                <i class="fas fa-store"></i> <?php echo t('Magasin sélectionné', 'Selected Shop Location'); ?>
            </div>
            <div id="selectedShopName" style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-top: 4px;">
                <?php echo $is_shop2 ? 'Le Plessis-Bouchard' : 'Villiers-le-bel'; ?>
            </div>
            <div id="selectedShopAddress" style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                <?php echo $is_shop2 ? 'Commercial des Hauts de Saint-Nicolas, 95130 Le Plessis-Bouchard' : '11 Place de la Tolinette, 95400 Villiers Le Bel'; ?>
            </div>
        </div>

        <div class="order-summary" id="orderSummary">
            <h3><?php echo t('Produits', 'Products'); ?></h3>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>
                        <span style="font-weight: 600;">€<?php echo number_format($item['item_total'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 12px 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem; color: #64748b;">
                <span><?php echo t('Sous-total', 'Subtotal'); ?></span>
                <span>€<span id="summarySubtotal"><?php echo number_format($subtotal, 2); ?></span></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem; color: #64748b;">
                <span><?php echo t('Frais de livraison', 'Delivery Fee'); ?></span>
                <span id="summaryDeliveryFee">€5.00</span>
            </div>
            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 12px 0;">
            <div class="total" style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700; color: #0f172a;">
                <strong><?php echo t('Total :', 'Total:'); ?></strong>
                <strong style="color: #e74c3c;">€<span id="summaryGrandTotal"><?php echo number_format($subtotal + 5.00, 2); ?></span></strong>
            </div>
        </div>
    </div>
</div>

<script>
var baseSubtotal = <?php echo floatval($subtotal); ?>;

function toggleOrderType(type) {
    var deliverySec = document.getElementById('deliveryAddressSection');
    var collectBox = document.getElementById('collectNoticeBox');
    var deliveryFeeElem = document.getElementById('summaryDeliveryFee');
    var grandTotalElem = document.getElementById('summaryGrandTotal');
    var guestAddr = document.getElementById('customer_address');
    var newAddr = document.getElementById('newAddressInput');

    if (type === 'collect') {
        deliverySec.style.display = 'none';
        collectBox.style.display = 'block';
        if (guestAddr) guestAddr.removeAttribute('required');
        if (newAddr) newAddr.removeAttribute('required');

        deliveryFeeElem.innerText = '€0.00 (Gratuit)';
        grandTotalElem.innerText = baseSubtotal.toFixed(2);
    } else {
        deliverySec.style.display = 'block';
        collectBox.style.display = 'none';
        
        var selectedRadio = document.querySelector('input[name="selected_address_id"]:checked');
        if (selectedRadio && selectedRadio.value === 'new') {
            if (newAddr) newAddr.setAttribute('required', 'required');
        } else if (guestAddr) {
            guestAddr.setAttribute('required', 'required');
        }

        deliveryFeeElem.innerText = '€5.00';
        grandTotalElem.innerText = (baseSubtotal + 5.00).toFixed(2);
    }
}

function handleAddressOptionChange(radio) {
    var cards = document.querySelectorAll('.address-card-option, .new-address-toggle');
    cards.forEach(function(c) { c.classList.remove('selected'); });

    var parentLabel = radio.closest('label');
    if (parentLabel) parentLabel.classList.add('selected');

    var newBox = document.getElementById('newAddressBox');
    var newAddr = document.getElementById('newAddressInput');

    if (radio.value === 'new') {
        if (newBox) newBox.style.display = 'block';
        if (newAddr) {
            newAddr.setAttribute('required', 'required');
            newAddr.focus();
        }
    } else {
        if (newBox) newBox.style.display = 'none';
        if (newAddr) newAddr.removeAttribute('required');
    }
}

function updateShopCard(shopId) {
    var shopCard = document.getElementById('selectedShopCard');
    var nameElem = document.getElementById('selectedShopName');
    var addrElem = document.getElementById('selectedShopAddress');
    var noticeText = document.getElementById('collectNoticeText');

    if (shopId === '2') {
        nameElem.innerText = 'Le Plessis-Bouchard';
        addrElem.innerText = 'Commercial des Hauts de Saint-Nicolas, 95130 Le Plessis-Bouchard';
        if (noticeText) noticeText.innerText = 'Le Plessis-Bouchard (Commercial des Hauts de Saint-Nicolas)';
        shopCard.style.background = '#eff6ff';
        shopCard.style.borderColor = '#bfdbfe';
    } else {
        nameElem.innerText = 'Villiers-le-bel';
        addrElem.innerText = '11 Place de la Tolinette, 95400 Villiers Le Bel';
        if (noticeText) noticeText.innerText = 'Villiers-le-bel (11 Place de la Tolinette)';
        shopCard.style.background = '#fff5f5';
        shopCard.style.borderColor = '#fecaca';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check initial type (delivery is default)
    var isDelivery = document.getElementById('typeDelivery').checked;
    toggleOrderType(isDelivery ? 'delivery' : 'collect');
});
</script>