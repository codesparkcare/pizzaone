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
            <div class="form-group">
                <label for="shop">Select Shop</label>
                <select name="shop_id" id="shop" required>
                    <option value="" disabled selected>Choose a shop</option>
                    <option value="1">11 Place de la Tolinette, 95400 Villiers Le Bel</option>
                    <option value="2">Commercial des Hauts de Saint-Nicolas 95130 Le Plessis-Bouchard +33 1 34 14 15 16</option>
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