<style>
    /* ===================== DASHBOARD STYLES ===================== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes countUp {
        from { opacity: 0; transform: scale(0.5); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes pulse-ring {
        0%   { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    .dash-grid-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 18px;
        padding: 24px 20px;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        animation: fadeInUp 0.5s ease both;
        cursor: pointer;
        text-decoration: none;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.18);
        color: #fff;
        text-decoration: none;
    }
    .stat-card .sc-icon {
        font-size: 2.8rem;
        opacity: 0.25;
        position: absolute;
        right: 16px;
        bottom: 12px;
        transition: opacity 0.3s, transform 0.3s;
    }
    .stat-card:hover .sc-icon { opacity: 0.4; transform: scale(1.1) rotate(-5deg); }

    .stat-card .sc-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        opacity: 0.85;
        margin-bottom: 6px;
    }
    .stat-card .sc-value {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        animation: countUp 0.6s ease both;
    }
    .stat-card .sc-sub {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 6px;
    }

    /* Card colours */
    .sc-red    { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .sc-green  { background: linear-gradient(135deg, #2ecc71, #27ae60); }
    .sc-blue   { background: linear-gradient(135deg, #3498db, #2980b9); }
    .sc-purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
    .sc-orange { background: linear-gradient(135deg, #f39c12, #e67e22); }

    /* Stagger animation delays */
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.10s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.20s; }
    .stat-card:nth-child(5) { animation-delay: 0.25s; }

    /* ---- Quick Actions ---- */
    .dash-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }

    .quick-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .quick-btn {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fff;
        border: 1.5px solid #eee;
        border-radius: 14px;
        padding: 16px;
        text-decoration: none;
        color: var(--secondary);
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s;
    }
    .quick-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #fff5f5;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(231,76,60,0.12);
        text-decoration: none;
    }
    .quick-btn .qb-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #fff;
        flex-shrink: 0;
    }

    /* ---- Recent Orders Table ---- */
    .section-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        animation: fadeInUp 0.5s ease 0.3s both;
    }
    .section-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-card-header h4 {
        margin: 0;
        font-weight: 700;
        color: var(--secondary);
        font-size: 1rem;
    }
    .dash-table { width: 100%; border-collapse: collapse; }
    .dash-table th {
        padding: 12px 18px;
        text-align: left;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #aaa;
        background: #fafafa;
        border-bottom: 1px solid #f0f0f0;
    }
    .dash-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f7f7f7;
        font-size: 0.9rem;
        color: #444;
        vertical-align: middle;
    }
    .dash-table tr:last-child td { border-bottom: none; }
    .dash-table tr:hover td { background: #fafeff; }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-pending  { background: #fff3cd; color: #856404; }
    .status-accepted { background: #d1e7dd; color: #0a3622; }
    .status-delivered{ background: #cff4fc; color: #055160; }
    .status-cancelled{ background: #f8d7da; color: #842029; }

    /* ---- Activity Feed ---- */
    .activity-feed { padding: 8px 0; }
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 24px;
        border-bottom: 1px solid #f5f5f5;
        transition: background 0.2s;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-item:hover { background: #fafefe; }
    .activity-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        color: #fff;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .activity-text { flex: 1; }
    .activity-text strong { font-size: 0.9rem; color: var(--secondary); display: block; }
    .activity-text span  { font-size: 0.8rem; color: #999; }

    /* Live indicator */
    .live-dot {
        display: inline-block;
        width: 8px; height: 8px;
        background: #2ecc71;
        border-radius: 50%;
        margin-right: 6px;
        position: relative;
    }
    .live-dot::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: #2ecc71;
        animation: pulse-ring 1.5s ease infinite;
    }

    @media (max-width: 1200px) {
        .dash-grid-cards { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .dash-grid-cards { grid-template-columns: repeat(2, 1fr); }
        .dash-row    { grid-template-columns: 1fr; }
    }
</style>

<!-- ===================== STAT CARDS ===================== -->
<div class="dash-grid-cards">

    <a href="<?php echo base_url('admin/products'); ?>" class="stat-card sc-red">
        <div>
            <div class="sc-label">Products</div>
            <div class="sc-value"><?php echo $total_products; ?></div>
            <div class="sc-sub">Menu items</div>
        </div>
        <i class="fas fa-pizza-slice sc-icon"></i>
    </a>

    <a href="<?php echo base_url('admin/categories'); ?>" class="stat-card sc-green">
        <div>
            <div class="sc-label">Categories</div>
            <div class="sc-value"><?php echo $total_categories; ?></div>
            <div class="sc-sub">Active categories</div>
        </div>
        <i class="fas fa-list sc-icon"></i>
    </a>

    <?php if (!empty($shop_orders)): ?>
        <?php 
        $colors = ['sc-blue', 'sc-purple', 'sc-orange', 'sc-red', 'sc-green'];
        $i = 0;
        foreach ($shop_orders as $so): 
            $color = $colors[$i % count($colors)];
            $i++;
        ?>
        <a href="<?php echo base_url('admin/orders'); ?>" class="stat-card <?php echo $color; ?>">
            <div>
                <div class="sc-label">Orders</div>
                <div class="sc-value"><?php echo $so['count']; ?></div>
                <div class="sc-sub"><?php echo htmlspecialchars($so['name']); ?></div>
            </div>
            <i class="fas fa-shopping-cart sc-icon"></i>
        </a>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<!-- ===================== RECENT ORDERS TABLE ===================== -->
<div class="section-card" style="margin-bottom: 30px;">
    <div class="section-card-header">
        <h4><i class="fas fa-clock" style="color:#3498db; margin-right:8px;"></i>Recent Orders</h4>
        <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-primary btn-sm" style="font-size:0.8rem; padding: 6px 16px;">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <table class="dash-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_orders)): ?>
                <?php foreach ($recent_orders as $order): ?>
                <tr>
                    <td><strong>#<?php echo $order->id; ?></strong></td>
                    <td><?php echo htmlspecialchars($order->customer_name ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($order->customer_phone ?? '—'); ?></td>
                    <td><strong>€<?php echo number_format($order->total_amount ?? 0, 2); ?></strong></td>
                    <td>
                        <?php
                        $status = strtolower($order->status ?? 'pending');
                        $cls = 'status-pending';
                        if ($status === 'accepted')  $cls = 'status-accepted';
                        if ($status === 'delivered') $cls = 'status-delivered';
                        if ($status === 'cancelled') $cls = 'status-cancelled';
                        ?>
                        <span class="status-badge <?php echo $cls; ?>">
                            <?php echo ucfirst($status); ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-primary btn-sm" style="padding:4px 12px; font-size:0.78rem;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#aaa; font-style:italic; padding: 30px;">
                        <i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:10px; opacity:0.3;"></i>
                        No orders yet
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
