<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('assets/css/checkout.css'); ?>">

<div class="container">
    <div class="left">
        <h2>Customer Details</h2>
        <form id="checkoutForm" method="POST" action="<?php echo base_url('cart/place_order'); ?>">
            <div class="form-group">
                <label>Order Type</label>
                <div class="radio-group">
                    <label><input type="radio" name="order_type" value="collect" checked> Collect</label>
                    <label><input type="radio" name="order_type" value="delivery"> Delivery</label>
                </div>
            </div>
            <?php $selected_shop_id = $this->session->userdata('selected_shop_id') ?: '1'; ?>
            <div class="form-group">
                <label for="shop">Select Shop Location</label>
                <select name="shop_id" id="shop" required>
                    <option value="" disabled>Choose a shop</option>
                    <option value="1" <?php echo ($selected_shop_id == '1') ? 'selected' : ''; ?>>Villiers-le-bel (11 Place de la Tolinette, 95400 Villiers Le Bel)</option>
                    <option value="2" <?php echo ($selected_shop_id == '2') ? 'selected' : ''; ?>>Le Plessis-Bouchard (Commercial des Hauts de Saint-Nicolas 95130 Le Plessis-Bouchard)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="customer_name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="customer_phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="customer_address" placeholder="Street, City" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes (optional)</label>
                <input type="text" id="notes" name="notes" placeholder="Any special instructions">
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <label><input type="radio" name="payment_method" value="cash" checked> Cash on Delivery /
                    Pickup</label><br>
                <label><input type="radio" name="payment_method" value="card"> Credit / Debit Card</label>
            </div>
            <button type="submit" class="btn">Place Order</button>
        </form>
    </div>
    <div class="right">
        <h2>Order Summary</h2>
        <?php 
            $is_shop2 = ($selected_shop_id == '2');
            $box_bg = $is_shop2 ? '#eff6ff' : '#fff5f5';
            $box_border = $is_shop2 ? '#bfdbfe' : '#fecaca';
            $box_color = $is_shop2 ? '#2563eb' : '#e74c3c';
        ?>
        <div style="background: <?php echo $box_bg; ?>; border: 1px solid <?php echo $box_border; ?>; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
            <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: <?php echo $box_color; ?>; font-weight: 700;">
                <i class="fas fa-store"></i> Selected Shop Location
            </div>
            <div style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-top: 4px;">
                <?php echo $is_shop2 ? 'Le Plessis-Bouchard' : 'Villiers-le-bel'; ?>
            </div>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                <?php echo $is_shop2 ? 'Commercial des Hauts de Saint-Nicolas, 95130 Le Plessis-Bouchard' : '11 Place de la Tolinette, 95400 Villiers Le Bel'; ?>
            </div>
        </div>
        <div class="order-summary" id="orderSummary">
            <h3>Products</h3>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <span><?php echo $item['product_name']; ?> x <?php echo $item['quantity']; ?></span>
                        <span>€<?php echo number_format($item['item_total'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <div class="total">
                <strong>Total:</strong>
                <strong>€<?php echo number_format($subtotal, 2); ?></strong>
            </div>
        </div>
    </div>
</div>
</body>

</html>