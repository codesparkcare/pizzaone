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

            <?php if (!empty($order->items)): ?>
                <div style="margin-top: 25px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;">
                    <div style="background: #f8fafc; padding: 12px 18px; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 0.95rem; color: #1e293b;">
                        <i class="fas fa-pizza-slice" style="color: #e74c3c; margin-right: 8px;"></i> Ordered Items
                    </div>
                    <table class="table" style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
                        <thead>
                            <tr style="background: #fafafa; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem; text-align: left;">
                                <th style="padding: 10px 18px;">Item</th>
                                <th style="padding: 10px 12px; text-align: center;">Qty</th>
                                <th style="padding: 10px 18px; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->items as $it): 
                                $itName = $it['name'] ?? $it['product_name'] ?? 'Item';
                                $itSize = $it['size_name'] ?? $it['size'] ?? '';
                                $itQty  = $it['quantity'] ?? $it['qty'] ?? 1;
                                $itTotal = number_format(floatval($it['item_total'] ?? (($it['price'] ?? 0) * $itQty) ?? 0), 2);
                                $itAddons = $it['addons'] ?? [];
                            ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 12px 18px; vertical-align: top;">
                                        <strong style="color: #1e293b; font-size: 0.95rem;"><?php echo htmlspecialchars($itName); ?></strong>
                                        <?php if (!empty($itSize)): ?>
                                            <span class="badge" style="background: #eff6ff; color: #1d4ed8; font-size: 0.75rem; padding: 2px 8px; border-radius: 4px; margin-left: 6px; font-weight: 600; border: 1px solid #dbeafe;">
                                                <?php echo htmlspecialchars($itSize); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($itAddons)): ?>
                                            <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px;">
                                                <?php foreach ($itAddons as $ad): 
                                                    $ad_name = is_array($ad) ? ($ad['name'] ?? '') : ($ad->name ?? (string)$ad);
                                                    $ad_price = is_array($ad) ? floatval($ad['price'] ?? 0) : floatval($ad->price ?? 0);
                                                    $is_sans = stripos($ad_name, 'sans ') === 0;
                                                ?>
                                                    <span style="display: inline-flex; align-items: center; gap: 3px; font-size: 0.75rem; font-weight: 600; padding: 2px 7px; border-radius: 4px; background: <?= $is_sans ? '#fef2f2' : '#f0fdf4'; ?>; color: <?= $is_sans ? '#b91c1c' : '#15803d'; ?>; border: 1px solid <?= $is_sans ? '#fecaca' : '#bbf7d0'; ?>;">
                                                        <i class="fas <?= $is_sans ? 'fa-ban' : 'fa-check'; ?>" style="font-size: 0.65rem;"></i> <?= htmlspecialchars($ad_name); ?><?= ($ad_price > 0) ? ' (+€' . number_format($ad_price, 2) . ')' : ''; ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center; font-weight: 700; color: #475569; vertical-align: top;">
                                        <?php echo $itQty; ?>
                                    </td>
                                    <td style="padding: 12px 18px; text-align: right; font-weight: 700; color: #1e293b; vertical-align: top;">
                                        €<?php echo $itTotal; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div style="margin-top: 25px; background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                <h5 style="color: var(--secondary); margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Notes</h5>
                <p><?php echo !empty($order->notes) ? nl2br($order->notes) : '<em>No special notes provided.</em>'; ?></p>
            </div>

            <div style="margin-top: 25px; background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                <h5 style="color: var(--secondary); margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Financial Summary</h5>
                <table class="table" style="width: 100%; max-width: 400px; margin-left: auto;">
                    <tr>
                        <td style="border: none; text-align: right;"><strong>Subtotal:</strong></td>
                        <td style="border: none; text-align: right; width: 100px;">€<?php echo number_format($order->subtotal, 2); ?></td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: right;"><strong>Delivery Fee:</strong></td>
                        <td style="border: none; text-align: right;">€<?php echo number_format($order->delivery_fee, 2); ?></td>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: right; font-size: 1.2rem; color: var(--primary);"><strong>Total Amount:</strong></td>
                        <td style="border: none; text-align: right; font-size: 1.2rem; color: var(--primary);"><strong>€<?php echo number_format($order->total_amount, 2); ?></strong></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
