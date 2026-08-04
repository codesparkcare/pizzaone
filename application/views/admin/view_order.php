<div class="row">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Order Details: #<?php echo $order->id; ?></h4>
            <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-secondary btn-sm" style="background: #95a5a6; color: #fff;">Back to Orders</a>
        </div>
        <div class="card-body">
            <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                <!-- Customer Details -->
                <div style="flex: 1; min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                    <h5 style="color: var(--secondary); margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Customer Information</h5>
                    <p><strong>Name:</strong> <?php echo $order->customer_name; ?></p>
                    <p><strong>Phone:</strong> <?php echo $order->customer_phone; ?></p>
                    <p><strong>Address:</strong> <?php echo !empty($order->customer_address) ? $order->customer_address : 'N/A'; ?></p>
                </div>

                <!-- Order Details -->
                <div style="flex: 1; min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                    <h5 style="color: var(--secondary); margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Order Summary</h5>
                    <p><strong>Order Type:</strong> <?php echo ucfirst($order->order_type); ?></p>
                    <p><strong>Assigned Shop:</strong> <?php echo $order->shop_name ? $order->shop_name : 'N/A'; ?></p>
                    <p><strong>Payment Method:</strong> <?php echo strtoupper($order->payment_method); ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge" style="background: <?php 
                                echo ($order->status == 'pending') ? '#f1c40f' : 
                                     (($order->status == 'delivered') ? '#2ecc71' : 
                                     (($order->status == 'cancelled') ? '#e74c3c' : '#3498db')); 
                            ?>; color: #fff; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                <?php echo ucfirst($order->status); ?>
                        </span>
                    </p>
                    <p><strong>Date Ordered:</strong> <?php echo date('d M Y, h:i A', strtotime($order->created_at)); ?></p>
                </div>
            </div>

            <div style="margin-top: 25px; background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                <h5 style="color: var(--secondary); margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Notes</h5>
                <p><?php echo !empty($order->notes) ? nl2br($order->notes) : '<em>No special notes provided.</em>'; ?></p>
            </div>

            <div style="margin-top: 25px; background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                <h5 style="color: var(--secondary); margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Financial Summary</h5>
                <table class="table" style="width: 100%; max-width: 400px; margin-left: auto;">
                    <tr>
                        <td style="border: none; text-align: right;"><strong>Subtotal:</strong></td>
                        <td style="border: none; text-align: right; width: 100px;">$<?php echo number_format($order->subtotal, 2); ?></td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: right;"><strong>Delivery Fee:</strong></td>
                        <td style="border: none; text-align: right;">$<?php echo number_format($order->delivery_fee, 2); ?></td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: right; font-size: 1.2rem; color: var(--primary);"><strong>Total Amount:</strong></td>
                        <td style="border: none; text-align: right; font-size: 1.2rem; color: var(--primary);"><strong>$<?php echo number_format($order->total_amount, 2); ?></strong></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
