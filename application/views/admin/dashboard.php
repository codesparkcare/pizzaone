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

    /* ===================== LIVE MONITOR & CONTROLS ===================== */
    .live-monitor-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    }

    .live-status-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .live-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 700;
        transition: all 0.3s;
    }

    .live-status-pill.session-ok {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .live-status-pill.session-expired {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .live-pulse-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        position: relative;
    }

    .live-pulse-dot::after {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        background: #10b981;
        opacity: 0.5;
        animation: livePulse 1.8s infinite;
    }

    @keyframes livePulse {
        0% { transform: scale(1); opacity: 0.7; }
        50% { transform: scale(1.8); opacity: 0; }
        100% { transform: scale(1); opacity: 0; }
    }

    .countdown-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.82rem;
        color: #475569;
        font-weight: 600;
    }

    .countdown-val {
        color: #2563eb;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        min-width: 24px;
        display: inline-block;
        text-align: center;
    }

    .live-actions-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-ctrl {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 15px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-ctrl:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .btn-ctrl.btn-sound-on {
        background: #f0fdf4;
        border-color: #86efac;
        color: #166534;
    }

    .btn-ctrl.btn-sound-off {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #991b1b;
    }

    .btn-ctrl.btn-refresh:hover i {
        transform: rotate(180deg);
    }

    .btn-ctrl i {
        transition: transform 0.4s ease;
    }

    /* ===================== NEW ORDER ALARM BANNER ===================== */
    @keyframes alarmGlow {
        0% { box-shadow: 0 0 0 rgba(239, 68, 68, 0.4); border-color: #ef4444; }
        50% { box-shadow: 0 0 25px rgba(239, 68, 68, 0.85); border-color: #b91c1c; }
        100% { box-shadow: 0 0 0 rgba(239, 68, 68, 0.4); border-color: #ef4444; }
    }

    @keyframes bellShake {
        0% { transform: rotate(0); }
        15% { transform: rotate(14deg); }
        30% { transform: rotate(-14deg); }
        45% { transform: rotate(10deg); }
        60% { transform: rotate(-10deg); }
        75% { transform: rotate(4deg); }
        100% { transform: rotate(0); }
    }

    .new-order-alarm-banner {
        background: linear-gradient(135deg, #fff1f2, #ffe4e6);
        border: 2px solid #ef4444;
        border-radius: 16px;
        padding: 16px 22px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        animation: alarmGlow 1.6s infinite ease-in-out;
    }

    .alarm-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .alarm-icon-box {
        width: 48px;
        height: 48px;
        background: #ef4444;
        color: #ffffff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        animation: bellShake 1s infinite ease-in-out;
        flex-shrink: 0;
    }

    .alarm-text-box .alarm-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #991b1b;
    }

    .alarm-text-box .alarm-subtitle {
        margin: 3px 0 0 0;
        font-size: 0.85rem;
        color: #b91c1c;
        font-weight: 500;
    }

    .alarm-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-alarm-view {
        background: #e11d48;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s, transform 0.2s;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.3);
    }

    .btn-alarm-view:hover {
        background: #be123c;
        transform: translateY(-2px);
    }

    .btn-alarm-stop {
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-alarm-stop:hover {
        background: #f1f5f9;
        color: #0f172a;
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
                <div class="sc-value shop-stat-count" data-shop-id="<?php echo $so['shop_id'] ?? $i; ?>"><?php echo $so['count']; ?></div>
                <div class="sc-sub"><?php echo htmlspecialchars($so['name']); ?></div>
            </div>
            <i class="fas fa-shopping-cart sc-icon"></i>
        </a>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<!-- ===================== SESSION EXPIRED WARNING ===================== -->
<div id="sessionExpiredAlert" style="display:none; background:#fef2f2; border:2px solid #ef4444; border-radius:14px; padding:16px 22px; margin-bottom:20px; box-shadow:0 4px 15px rgba(239,68,68,0.15);">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <i class="fas fa-exclamation-triangle" style="font-size:1.6rem; color:#dc2626;"></i>
            <div>
                <strong style="color:#991b1b; font-size:1rem; display:block;">Session Expired on Live Server!</strong>
                <span style="color:#7f1d1d; font-size:0.85rem;">Automatic dashboard synchronization paused. Please log in again to continue.</span>
            </div>
        </div>
        <a href="<?php echo base_url('admin/login'); ?>" class="btn btn-danger btn-sm" style="padding:8px 18px; font-weight:700; border-radius:8px; text-decoration:none;">
            <i class="fas fa-sign-in-alt me-1"></i> Log In Again
        </a>
    </div>
</div>

<!-- ===================== NEW ORDER ALARM BANNER ===================== -->
<div id="newOrderAlarmBanner" class="new-order-alarm-banner" style="display:none;">
    <div class="alarm-content">
        <div class="alarm-icon-box">
            <i class="fas fa-bell"></i>
        </div>
        <div class="alarm-text-box">
            <h4 class="alarm-title">NEW ORDER RECEIVED! (<span id="alarmPendingCount">1</span> Pending)</h4>
            <p class="alarm-subtitle" id="alarmOrderSubtitle">A new order was just placed on the live server.</p>
        </div>
    </div>
    <div class="alarm-actions">
        <button type="button" id="alarmViewBtn" class="btn-alarm-view" style="cursor:pointer;">
            <i class="fas fa-eye"></i> View Order
        </button>
        <button type="button" id="alarmStopBtn" class="btn-alarm-stop" style="cursor:pointer;">
            <i class="fas fa-volume-mute"></i> Stop Alarm
        </button>
    </div>
</div>

<!-- ===================== LIVE DASHBOARD MONITOR & CONTROLS ===================== -->
<div class="live-monitor-bar">
    <div class="live-status-group">
        <span id="sessionStatusPill" class="live-status-pill session-ok" title="Server session active and monitored">
            <span class="live-pulse-dot" id="sessionPulseDot"></span>
            <span id="sessionStatusText">Live Server: Session Active</span>
        </span>
        <span class="countdown-box" title="Auto-refreshing every 15 seconds">
            <i class="fas fa-clock" style="color:#64748b;"></i>
            Auto-Refresh: <strong id="refreshCountdownVal" class="countdown-val">15s</strong>
        </span>
        <span id="lastRefreshedTime" style="color:#64748b; font-size:0.8rem; font-weight:500;">Synced: Just now</span>
    </div>
    <div class="live-actions-group">
        <button type="button" id="manualRefreshBtn" class="btn-ctrl btn-refresh" title="Refresh dashboard right now">
            <i class="fas fa-sync-alt" id="refreshIcon"></i> Refresh Now
        </button>
        <button type="button" id="toggleAlarmSoundBtn" class="btn-ctrl btn-sound-on" title="Turn order alarm chime ON/OFF">
            <i class="fas fa-volume-up" id="alarmSoundIcon"></i> <span id="alarmSoundLabel">Alarm: ON</span>
        </button>
        <button type="button" id="testAlarmSoundBtn" class="btn-ctrl" title="Click to test restaurant order chime">
            <i class="fas fa-bell" style="color:#e67e22;"></i> Test Sound
        </button>
    </div>
</div>

<!-- ===================== NEW & PENDING ORDERS TABLE ===================== -->
<div class="section-card" style="margin-bottom: 30px;">
    <div class="section-card-header">
        <div style="display:flex; align-items:center; gap:10px;">
            <h4><i class="fas fa-bell" style="color:#e67e22; margin-right:8px;"></i>New &amp; Pending Orders</h4>
            <span id="pendingOrdersCountBadge" class="badge" style="background:#e67e22; color:#fff; border-radius:50px; font-size:0.75rem; padding:4px 10px; font-weight:700; <?php echo empty($recent_orders) ? 'display:none;' : ''; ?>">
                <?php echo count($recent_orders ?? []); ?> Pending
            </span>
        </div>
        <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-primary btn-sm" style="font-size:0.8rem; padding: 6px 16px;">
            View All Orders <i class="fas fa-arrow-right ms-1"></i>
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
        <tbody id="pendingOrdersTbody">
            <?php if (!empty($recent_orders)): ?>
                <?php foreach ($recent_orders as $order): ?>
                <tr>
                    <td><strong>#<?php echo $order->id; ?></strong></td>
                    <td><?php echo htmlspecialchars($order->customer_name ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($order->customer_phone ?? '—'); ?></td>
                    <td><strong>€<?php echo number_format($order->total_amount ?? 0, 2); ?></strong></td>
                    <td>
                        <span class="status-badge status-pending">
                            <?php echo ucfirst($order->status ?? 'pending'); ?>
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm view-order-btn" data-id="<?php echo $order->id; ?>" style="padding:4px 14px; font-size:0.78rem; display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#888; font-style:italic; padding: 35px 20px;">
                        <i class="fas fa-check-circle" style="font-size:2.4rem; color:#2ecc71; display:block; margin-bottom:10px; opacity:0.8;"></i>
                        <strong style="color:#2c3e50; font-size:1rem; display:block;">No pending orders!</strong>
                        <span style="font-size:0.85rem; color:#95a5a6;">All new orders have been attended to.</span>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ===================== ORDER DETAILS POPUP MODAL ===================== -->
<div id="viewOrderModal" class="custom-modal">
    <div class="modal-content" style="max-width: 650px; border-radius: 16px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf2f7; padding-bottom: 15px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 id="modalOrderTitle" style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #2c3e50;">Order Details</h3>
                <span id="modalOrderStatusBadge" class="status-badge status-pending" style="font-size: 0.75rem; text-transform: capitalize;">Pending</span>
            </div>
            <span class="close-modal" onclick="closeOrderModal()" style="font-size: 1.8rem; cursor: pointer; color: #a0aec0; line-height: 1;">&times;</span>
        </div>
        <div class="modal-body" id="modalOrderBody" style="max-height: 68vh; overflow-y: auto; padding-right: 5px;">
            <div style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary, #e21b1b);"></i><p style="margin-top:10px; color:#666;">Loading order details...</p></div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #edf2f7; padding-top: 15px; margin-top: 20px; flex-wrap: wrap; gap: 10px;">
            <!-- Status Update Action -->
            <form id="modalStatusForm" action="" method="POST" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                <input type="hidden" name="redirect_to" value="admin/dashboard">
                <label style="font-size: 0.85rem; font-weight: 600; color: #4a5568; margin: 0;">Status:</label>
                <select name="status" id="modalStatusSelect" class="form-control" style="padding: 6px 10px; font-size: 0.85rem; height: 34px; border-radius: 6px; border: 1px solid #cbd5e1; margin: 0; width: auto;">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="on_the_way">On the Way</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="height: 34px; padding: 0 14px; font-weight: 600; border-radius: 6px; background: #3498db; border: none; color: #fff;">Update</button>
            </form>
            <button type="button" class="btn btn-secondary" onclick="closeOrderModal()" style="background: #95a5a6; color:#fff; border: none; padding: 7px 18px; border-radius: 6px; font-weight: 600; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

<script>
    // Global Dashboard & Alarm State
    var lastKnownMaxOrderId = <?php echo (int)($initial_max_order_id ?? 0); ?>;
    var COUNTDOWN_INTERVAL = 15;
    var countdownSec = COUNTDOWN_INTERVAL;
    var countdownTimerId = null;
    var isPolling = false;
    var alarmIntervalId = null;
    var audioCtx = null;
    var isSoundAlarmEnabled = (localStorage.getItem('pizzaone_alarm_sound') !== 'false');

    /* ===================== WEB AUDIO API CHIME ===================== */
    function getAudioContext() {
        if (!audioCtx) {
            var AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) {
                audioCtx = new AudioContextClass();
            }
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    function playTone(freq, startTime, duration, volume) {
        try {
            var ctx = getAudioContext();
            if (!ctx) return;
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, startTime);

            gain.gain.setValueAtTime(volume, startTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(startTime);
            osc.stop(startTime + duration);
        } catch (e) {
            console.warn('Audio tone error:', e);
        }
    }

    // High-quality restaurant dual-tone bell chime ("Ding-dong!")
    function playRestaurantChime() {
        try {
            var ctx = getAudioContext();
            if (!ctx) return;
            var now = ctx.currentTime;
            
            // First bell note: A5 (880 Hz) + octave overtone
            playTone(880, now, 0.65, 0.35);
            playTone(1760, now, 0.30, 0.12);
            
            // Second harmonious bell note: E5 (659.25 Hz) + overtone
            playTone(659.25, now + 0.28, 0.85, 0.40);
            playTone(1318.5, now + 0.28, 0.45, 0.12);
        } catch (e) {
            console.warn('Play chime error:', e);
        }
    }

    function startOrderAlarm(orderId) {
        if (!isSoundAlarmEnabled) return;
        playRestaurantChime();
        if (alarmIntervalId) clearInterval(alarmIntervalId);
        alarmIntervalId = setInterval(function() {
            playRestaurantChime();
        }, 2500);
    }

    function stopOrderAlarm() {
        if (alarmIntervalId) {
            clearInterval(alarmIntervalId);
            alarmIntervalId = null;
        }
        var banner = document.getElementById('newOrderAlarmBanner');
        if (banner) {
            banner.style.display = 'none';
        }
    }

    function syncSoundToggleUI() {
        var btn = document.getElementById('toggleAlarmSoundBtn');
        var icon = document.getElementById('alarmSoundIcon');
        var label = document.getElementById('alarmSoundLabel');
        if (!btn || !icon || !label) return;

        if (isSoundAlarmEnabled) {
            btn.className = 'btn-ctrl btn-sound-on';
            icon.className = 'fas fa-volume-up';
            label.textContent = 'Alarm: ON';
        } else {
            btn.className = 'btn-ctrl btn-sound-off';
            icon.className = 'fas fa-volume-mute';
            label.textContent = 'Alarm: OFF';
        }
    }

    /* ===================== SESSION STATUS & ALERTS ===================== */
    function updateSessionStatus(isActive, username, errorMsg) {
        var pill = document.getElementById('sessionStatusPill');
        var text = document.getElementById('sessionStatusText');
        var dot = document.getElementById('sessionPulseDot');
        var alertBox = document.getElementById('sessionExpiredAlert');

        if (!pill || !text) return;

        if (isActive) {
            pill.className = 'live-status-pill session-ok';
            var userSuffix = username ? ' (' + username + ')' : '';
            text.textContent = 'Live Server: Session Active' + userSuffix;
            if (dot) dot.style.display = 'inline-block';
            if (alertBox) alertBox.style.display = 'none';
        } else {
            pill.className = 'live-status-pill session-expired';
            text.textContent = errorMsg || 'Live Server: Session Expired';
            if (dot) dot.style.display = 'none';
            if (alertBox) alertBox.style.display = 'block';
        }
    }

    function showNewOrderAlarm(orderId, pendingCount) {
        var banner = document.getElementById('newOrderAlarmBanner');
        var countEl = document.getElementById('alarmPendingCount');
        var subtitleEl = document.getElementById('alarmOrderSubtitle');
        var viewBtn = document.getElementById('alarmViewBtn');

        if (countEl) countEl.textContent = pendingCount || 1;
        if (subtitleEl) subtitleEl.textContent = 'Order #' + orderId + ' has just arrived! Total pending: ' + (pendingCount || 1);
        if (viewBtn) {
            viewBtn.setAttribute('data-id', orderId);
            viewBtn.onclick = function() {
                stopOrderAlarm();
                openOrderModal(orderId);
            };
        }
        if (banner) {
            banner.style.display = 'flex';
        }

        startOrderAlarm(orderId);
    }

    /* ===================== COUNTDOWN & 15s POLLING ===================== */
    function updateCountdownUI(sec) {
        var el = document.getElementById('refreshCountdownVal');
        if (el) el.textContent = sec + 's';
    }

    function startCountdown() {
        if (countdownTimerId) clearInterval(countdownTimerId);
        countdownSec = COUNTDOWN_INTERVAL;
        updateCountdownUI(countdownSec);

        countdownTimerId = setInterval(function() {
            countdownSec--;
            if (countdownSec <= 0) {
                countdownSec = COUNTDOWN_INTERVAL;
                updateCountdownUI(countdownSec);
                pollDashboardOrders(false);
            } else {
                updateCountdownUI(countdownSec);
            }
        }, 1000);
    }

    function pollDashboardOrders(isManual) {
        if (isPolling) return;
        isPolling = true;

        var refreshIcon = document.getElementById('refreshIcon');
        if (refreshIcon) refreshIcon.classList.add('fa-spin');

        var pollUrl = '<?php echo base_url("admin/ajax_check_new_orders"); ?>';

        fetch(pollUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(function(response) {
            if (response.status === 401) {
                throw new Error('SESSION_EXPIRED');
            }
            return response.json();
        })
        .then(function(data) {
            if (!data || data.status !== 'success' || data.session_active === false) {
                updateSessionStatus(false, null, data && data.message ? data.message : 'Session Expired');
                return;
            }

            // 1. Session is active on live server
            updateSessionStatus(true, data.admin_username);

            // 2. Detect brand new order
            var latestId = parseInt(data.latest_order_id, 10) || 0;
            if (lastKnownMaxOrderId > 0 && latestId > lastKnownMaxOrderId) {
                showNewOrderAlarm(latestId, data.pending_count);
            }
            if (latestId > lastKnownMaxOrderId) {
                lastKnownMaxOrderId = latestId;
            }

            // 3. Update pending orders count badge
            var badge = document.getElementById('pendingOrdersCountBadge');
            if (badge) {
                if (data.pending_count > 0) {
                    badge.textContent = data.pending_count + ' Pending';
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }

            // 4. Update shop stat cards count
            if (Array.isArray(data.shop_orders)) {
                data.shop_orders.forEach(function(so) {
                    var el = document.querySelector('.shop-stat-count[data-shop-id="' + so.shop_id + '"]');
                    if (el) {
                        el.textContent = so.count;
                    }
                });
            }

            // 5. Update pending orders table
            if (data.pending_orders) {
                renderPendingOrdersTable(data.pending_orders);
            }
        })
        .catch(function(err) {
            if (err.message === 'SESSION_EXPIRED') {
                updateSessionStatus(false, null, 'Session Expired on Server');
            } else {
                console.warn('Dashboard poll error:', err);
                var text = document.getElementById('sessionStatusText');
                if (text) text.textContent = 'Live Server: Syncing...';
            }
        })
        .finally(function() {
            isPolling = false;
            if (refreshIcon) refreshIcon.classList.remove('fa-spin');
            var now = new Date();
            var timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            var timeEl = document.getElementById('lastRefreshedTime');
            if (timeEl) timeEl.textContent = 'Synced: ' + timeStr;
        });
    }

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderPendingOrdersTable(orders) {
        var tbody = document.getElementById('pendingOrdersTbody');
        if (!tbody) return;

        if (!orders || orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center; color:#888; font-style:italic; padding: 35px 20px;">
                        <i class="fas fa-check-circle" style="font-size:2.4rem; color:#2ecc71; display:block; margin-bottom:10px; opacity:0.8;"></i>
                        <strong style="color:#2c3e50; font-size:1rem; display:block;">No pending orders!</strong>
                        <span style="font-size:0.85rem; color:#95a5a6;">All new orders have been attended to.</span>
                    </td>
                </tr>
            `;
            return;
        }

        var html = '';
        orders.forEach(function(order) {
            var custName = escapeHtml(order.customer_name || 'N/A');
            var custPhone = escapeHtml(order.customer_phone || '—');
            var amount = order.formatted_amount || parseFloat(order.total_amount || 0).toFixed(2);
            var status = escapeHtml(order.status || 'pending');
            var statusClass = 'status-' + status.toLowerCase();

            html += `
                <tr>
                    <td><strong>#${order.id}</strong></td>
                    <td>${custName}</td>
                    <td>${custPhone}</td>
                    <td><strong>€${amount}</strong></td>
                    <td>
                        <span class="status-badge ${statusClass}">
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm view-order-btn" data-id="${order.id}" style="padding:4px 14px; font-size:0.78rem; display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        attachViewOrderEvents();
    }

    /* ===================== ORDER DETAILS POPUP MODAL ===================== */
    function closeOrderModal() {
        var modal = document.getElementById('viewOrderModal');
        if (modal) modal.style.display = 'none';
    }

    function openOrderModal(orderId) {
        stopOrderAlarm();
        var modal = document.getElementById('viewOrderModal');
        var modalBody = document.getElementById('modalOrderBody');
        var modalTitle = document.getElementById('modalOrderTitle');
        var modalBadge = document.getElementById('modalOrderStatusBadge');
        var statusForm = document.getElementById('modalStatusForm');
        var statusSelect = document.getElementById('modalStatusSelect');
        
        if (!modal) return;
        modalTitle.innerText = 'Order Details: #' + orderId;
        modalBody.innerHTML = '<div style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary, #e21b1b);"></i><p style="margin-top:10px; color:#666;">Loading order details...</p></div>';
        modal.style.display = 'block';

        if (statusForm) {
            statusForm.action = '<?php echo base_url("admin/update_order_status/"); ?>' + orderId;
        }

        fetch('<?php echo base_url("admin/ajax_view_order/"); ?>' + orderId)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    var order = data.order;
                    var ordStatus = (order.status || 'pending').toLowerCase();
                    
                    if (modalBadge) {
                        modalBadge.innerText = ordStatus.toUpperCase();
                        modalBadge.className = 'status-badge status-' + ordStatus;
                    }
                    if (statusSelect) {
                        statusSelect.value = ordStatus;
                    }

                    // Build Items List HTML
                    var itemsHtml = '';
                    if (order.items && order.items.length > 0) {
                        itemsHtml += '<div style="margin-top: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;">';
                        itemsHtml += '<div style="background: #f8fafc; padding: 10px 15px; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 0.9rem; color: #2d3748;"><i class="fas fa-pizza-slice" style="color:#e74c3c; margin-right:6px;"></i> Ordered Items</div>';
                        itemsHtml += '<table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">';
                        itemsHtml += '<thead><tr style="border-bottom: 1px solid #edf2f7; background: #fafafa; color: #718096; text-align: left;"><th style="padding: 8px 15px;">Item</th><th style="padding: 8px 10px; text-align:center;">Qty</th><th style="padding: 8px 15px; text-align:right;">Total</th></tr></thead>';
                        itemsHtml += '<tbody>';
                        order.items.forEach(function(it) {
                            var itName = it.name || it.product_name || 'Item';
                            var itSize = it.size_name || it.size || '';
                            var itQty = it.quantity || it.qty || 1;
                            var itTotal = parseFloat(it.item_total || (it.price * itQty) || 0).toFixed(2);
                            
                            var addonsHtml = '';
                            var rawAddons = it.addons || it.selected_addons || it.addon_items || [];
                            if (typeof rawAddons === 'string' && rawAddons.trim() !== '') {
                                try { rawAddons = JSON.parse(rawAddons); } catch(e) { rawAddons = [rawAddons]; }
                            }
                            if (rawAddons && (Array.isArray(rawAddons) || typeof rawAddons === 'object')) {
                                var addonList = Array.isArray(rawAddons) ? rawAddons : Object.values(rawAddons);
                                if (addonList.length > 0) {
                                    addonsHtml += '<div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 4px;">';
                                    addonList.forEach(function(a) {
                                        var aName = '';
                                        var aPrice = 0;
                                        if (typeof a === 'object' && a !== null) {
                                            aName = a.name || a.addon_name || a.title || '';
                                            aPrice = parseFloat(a.price || 0);
                                        } else if (typeof a === 'string' || typeof a === 'number') {
                                            aName = String(a);
                                        }
                                        if (!aName) return;
                                        
                                        var isSans = aName.toLowerCase().startsWith('sans ') || (typeof a === 'object' && a.type === 'exclude');
                                        var bg = isSans ? '#fef2f2' : '#f0fdf4';
                                        var textCol = isSans ? '#b91c1c' : '#15803d';
                                        var borderCol = isSans ? '#fecaca' : '#bbf7d0';
                                        var icon = isSans ? 'fa-ban' : 'fa-check';
                                        var priceText = (aPrice > 0) ? ' (+€' + aPrice.toFixed(2) + ')' : '';

                                        addonsHtml += '<span style="display:inline-flex; align-items:center; gap:3px; background:' + bg + '; color:' + textCol + '; border:1px solid ' + borderCol + '; border-radius:4px; padding:2px 7px; font-size:0.75rem; font-weight:600;">' +
                                            '<i class="fas ' + icon + '" style="font-size:0.65rem;"></i> ' + escapeHtml(aName) + priceText +
                                        '</span>';
                                    });
                                    addonsHtml += '</div>';
                                }
                            }

                            var notesText = '';
                            if (it.instructions || it.notes) {
                                notesText = '<div style="font-size:0.78rem; color:#d97706; font-style:italic; margin-top:4px;"><i class="fas fa-info-circle" style="font-size:0.7rem;"></i> ' + escapeHtml(it.instructions || it.notes) + '</div>';
                            }

                            itemsHtml += '<tr style="border-bottom: 1px solid #f1f5f9;">';
                            itemsHtml += '<td style="padding: 10px 15px; vertical-align: top;"><strong style="color:#1e293b; font-size:0.92rem;">' + escapeHtml(itName) + '</strong> ' + 
                                (itSize ? '<span class="badge" style="background:#eff6ff; color:#1d4ed8; font-size:0.75rem; padding:2px 8px; border-radius:4px; margin-left:6px; font-weight:600; border:1px solid #dbeafe;">' + escapeHtml(itSize) + '</span>' : '') + 
                                addonsHtml + notesText + '</td>';
                            itemsHtml += '<td style="padding: 10px; text-align:center; font-weight:700; color:#475569; vertical-align: top;">' + itQty + '</td>';
                            itemsHtml += '<td style="padding: 10px 15px; text-align:right; font-weight:700; color:#1e293b; vertical-align: top;">€' + itTotal + '</td>';
                            itemsHtml += '</tr>';
                        });
                        itemsHtml += '</tbody></table></div>';
                    }

                    var orderTypeBadge = (order.order_type === 'collect' || order.order_type === 'takeaway') 
                        ? '<span class="badge" style="background:#e0e7ff; color:#3730a3; padding:3px 8px; border-radius:6px; font-weight:600;">À emporter (Pickup)</span>' 
                        : '<span class="badge" style="background:#dcfce7; color:#166534; padding:3px 8px; border-radius:6px; font-weight:600;">Livraison (Delivery)</span>';

                    var html = `
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <h5 style="margin: 0 0 10px 0; font-size: 0.92rem; font-weight: 700; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                    <i class="fas fa-user" style="color:#3498db; margin-right:6px;"></i> Customer Info
                                </h5>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Name:</strong> ${escapeHtml(order.customer_name || 'N/A')}</p>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Phone:</strong> <a href="tel:${escapeHtml(order.customer_phone)}" style="color:#3498db; font-weight:600; text-decoration:none;"><i class="fas fa-phone-alt" style="font-size:0.8rem; margin-right:4px;"></i>${escapeHtml(order.customer_phone || '—')}</a></p>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Address:</strong> ${escapeHtml(order.customer_address || 'N/A (Pickup)')}</p>
                            </div>
                            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <h5 style="margin: 0 0 10px 0; font-size: 0.92rem; font-weight: 700; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                    <i class="fas fa-info-circle" style="color:#3498db; margin-right:6px;"></i> Order Details
                                </h5>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Type:</strong> ${orderTypeBadge}</p>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Shop:</strong> <span style="font-weight:600; color:#2d3748;">${escapeHtml(order.shop_name || 'Pizza One')}</span></p>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Payment:</strong> <span style="text-transform:uppercase; font-weight:600; color:#4a5568;">${escapeHtml(order.payment_method || 'Cash')}</span></p>
                                <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Placed At:</strong> <span style="color:#64748b;">${order.formatted_date || ''}</span></p>
                            </div>
                        </div>

                        ${itemsHtml}

                        ${order.notes ? `
                        <div style="margin-top: 15px; background: #fffbeb; border: 1px solid #fef3c7; padding: 12px 15px; border-radius: 8px;">
                            <strong style="color: #92400e; font-size: 0.85rem;"><i class="fas fa-comment-alt" style="margin-right:5px;"></i> Customer Notes:</strong>
                            <p style="margin: 4px 0 0 0; color: #78350f; font-size: 0.88rem; font-style: italic;">"${escapeHtml(order.notes)}"</p>
                        </div>
                        ` : ''}

                        <div style="margin-top: 15px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px 18px; border-radius: 10px; text-align: right;">
                            <div style="font-size: 0.88rem; color: #64748b; margin-bottom: 4px;">Subtotal: <strong style="color: #2d3748;">€${order.subtotal}</strong></div>
                            <div style="font-size: 0.88rem; color: #64748b; margin-bottom: 6px;">Delivery Fee: <strong style="color: #2d3748;">€${order.delivery_fee}</strong></div>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #e74c3c; border-top: 1px dashed #cbd5e1; padding-top: 8px;">Total: €${order.total_amount}</div>
                        </div>
                    `;
                    modalBody.innerHTML = html;
                } else {
                    modalBody.innerHTML = '<div style="color:#e74c3c; text-align:center; padding:30px;"><i class="fas fa-exclamation-triangle fa-2x"></i><p style="margin-top:10px;">' + (data.message || 'Error loading order') + '</p></div>';
                }
            })
            .catch(function(err) {
                modalBody.innerHTML = '<div style="color:#e74c3c; text-align:center; padding:30px;"><i class="fas fa-exclamation-triangle fa-2x"></i><p style="margin-top:10px;">Failed to load order details. Please try again.</p></div>';
            });
    }

    function attachViewOrderEvents() {
        var viewBtns = document.querySelectorAll('.view-order-btn');
        viewBtns.forEach(function(btn) {
            btn.onclick = function(e) {
                e.preventDefault();
                var orderId = this.getAttribute('data-id');
                openOrderModal(orderId);
            };
        });
    }

    /* ===================== DOM INITIALIZATION ===================== */
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Audio unlock on any first user gesture (satisfies browser autoplay policies)
        function unlockAudio() {
            getAudioContext();
        }
        ['click', 'touchstart', 'keydown'].forEach(function(evt) {
            document.addEventListener(evt, unlockAudio, { once: true });
        });

        // 2. Sound alarm toggle button
        syncSoundToggleUI();
        var toggleBtn = document.getElementById('toggleAlarmSoundBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                isSoundAlarmEnabled = !isSoundAlarmEnabled;
                localStorage.setItem('pizzaone_alarm_sound', isSoundAlarmEnabled ? 'true' : 'false');
                syncSoundToggleUI();
                if (isSoundAlarmEnabled) {
                    getAudioContext();
                    playRestaurantChime();
                } else {
                    stopOrderAlarm();
                }
            });
        }

        // 3. Test Sound button
        var testSoundBtn = document.getElementById('testAlarmSoundBtn');
        if (testSoundBtn) {
            testSoundBtn.addEventListener('click', function() {
                getAudioContext();
                playRestaurantChime();
            });
        }

        // 4. Stop alarm button on banner
        var stopAlarmBtn = document.getElementById('alarmStopBtn');
        if (stopAlarmBtn) {
            stopAlarmBtn.addEventListener('click', function() {
                stopOrderAlarm();
            });
        }

        // 5. Manual refresh button
        var manualRefreshBtn = document.getElementById('manualRefreshBtn');
        if (manualRefreshBtn) {
            manualRefreshBtn.addEventListener('click', function() {
                countdownSec = COUNTDOWN_INTERVAL;
                updateCountdownUI(countdownSec);
                pollDashboardOrders(true);
            });
        }

        // 6. Attach modal view triggers
        attachViewOrderEvents();

        // 7. Modal close on backdrop click
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('viewOrderModal');
            if (event.target == modal) {
                closeOrderModal();
            }
        });

        // 8. Start auto-refresh countdown
        startCountdown();
    });
</script>
