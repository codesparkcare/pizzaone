<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'My Orders'; ?></title>
    <style>
        body {font-family: 'Inter', sans-serif; background:#f9fafb; margin:0; padding:0;}
        .container {max-width:960px; margin:auto; padding:2rem;}
        h2 {color:#2d8cf0; text-align:center; margin-bottom:1.5rem;}
        table {width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.08);}
        th, td {padding:0.75rem; text-align:left; border-bottom:1px solid #eaecef;}
        th {background:#f0f4f8; color:#333;}
        .badge {display:inline-block; padding:0.3rem 0.6rem; border-radius:12px; font-size:0.85rem; color:#fff;}
        .badge.pending {background:#f1c40f;}
        .badge.confirmed {background:#2ecc71;}
        .badge.preparing {background:#e67e22;}
        .badge.delivered {background:#3498db;}
        .badge.cancelled {background:#e74c3c;}
        .no-orders {text-align:center; padding:2rem; color:#777;}
        a.btn {display:inline-block; margin-top:1rem; padding:0.75rem 1.5rem; background:#2d8cf0; color:#fff; border-radius:4px; text-decoration:none;}
    </style>
</head>
<body>
<div class="container">
    <h2><?= $title ?? 'My Orders'; ?></h2>
    <?php if (!empty($orders) && is_array($orders)): ?>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Type</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order->id; ?></td>
                        <td><?= ucfirst($order->order_type); ?></td>
                        <td>$<?= number_format($order->total_amount, 2); ?></td>
                        <td>
                            <?php
                                $statusClass = strtolower($order->status);
                                echo "<span class='badge $statusClass'>" . ucfirst($order->status) . "</span>";
                            ?>
                        </td>
                        <td><?= date('d M Y', strtotime($order->created_at)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-orders">You have no orders yet.</p>
    <?php endif; ?>
    <a href="<?= base_url('menu'); ?>" class="btn">Continue Shopping</a>
</div>
</body>
</html>
