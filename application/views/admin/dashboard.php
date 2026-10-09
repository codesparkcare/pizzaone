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

<!-- ===================== NEW & PENDING ORDERS TABLE ===================== -->
<div class="section-card" style="margin-bottom: 30px;">
    <div class="section-card-header">
        <div style="display:flex; align-items:center; gap:10px;">
            <h4><i class="fas fa-bell" style="color:#e67e22; margin-right:8px;"></i>New &amp; Pending Orders</h4>
            <?php if (!empty($recent_orders)): ?>
                <span class="badge" style="background:#e67e22; color:#fff; border-radius:50px; font-size:0.75rem; padding:4px 10px; font-weight:700;">
                    <?php echo count($recent_orders); ?> Pending
                </span>
            <?php endif; ?>
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
        <tbody>
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
    function closeOrderModal() {
        var modal = document.getElementById('viewOrderModal');
        if (modal) modal.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var viewBtns = document.querySelectorAll('.view-order-btn');
        viewBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var orderId = this.getAttribute('data-id');
                var modal = document.getElementById('viewOrderModal');
                var modalBody = document.getElementById('modalOrderBody');
                var modalTitle = document.getElementById('modalOrderTitle');
                var modalBadge = document.getElementById('modalOrderStatusBadge');
                var statusForm = document.getElementById('modalStatusForm');
                var statusSelect = document.getElementById('modalStatusSelect');
                
                modalTitle.innerText = 'Order Details: #' + orderId;
                modalBody.innerHTML = '<div style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary, #e21b1b);"></i><p style="margin-top:10px; color:#666;">Loading order details...</p></div>';
                modal.style.display = 'block';

                if (statusForm) {
                    statusForm.action = '<?php echo base_url("admin/update_order_status/"); ?>' + orderId;
                }

                fetch('<?php echo base_url("admin/ajax_view_order/"); ?>' + orderId)
                    .then(response => response.json())
                    .then(data => {
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

                            // Build Items List HTML if available
                            var itemsHtml = '';
                            if (order.items && order.items.length > 0) {
                                itemsHtml += '<div style="margin-top: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;">';
                                itemsHtml += '<div style="background: #f8fafc; padding: 10px 15px; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 0.9rem; color: #2d3748;"><i class="fas fa-pizza-slice" style="color:#e74c3c; margin-right:6px;"></i> Ordered Items</div>';
                                itemsHtml += '<table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">';
                                itemsHtml += '<thead><tr style="border-bottom: 1px solid #edf2f7; background: #fafafa; color: #718096; text-align: left;"><th style="padding: 8px 15px;">Item</th><th style="padding: 8px 10px; text-align:center;">Qty</th><th style="padding: 8px 15px; text-align:right;">Total</th></tr></thead>';
                                itemsHtml += '<tbody>';
                                order.items.forEach(function(it) {
                                    var itName = it.name || it.product_name || 'Item';
                                    var itSize = it.size || it.size_name || '';
                                    var itQty = it.quantity || it.qty || 1;
                                    var itTotal = parseFloat(it.item_total || (it.price * itQty) || 0).toFixed(2);
                                    
                                    var addonsText = '';
                                    if (it.addons && it.addons.length > 0) {
                                        var addonNames = it.addons.map(function(a){ return typeof a === 'object' ? (a.name || a.addon_name || '') : a; });
                                        addonsText = '<div style="font-size:0.78rem; color:#718096; margin-top:2px;"><i class="fas fa-plus" style="font-size:0.65rem;"></i> ' + addonNames.join(', ') + '</div>';
                                    }
                                    var notesText = '';
                                    if (it.instructions || it.notes) {
                                        notesText = '<div style="font-size:0.78rem; color:#d97706; font-style:italic; margin-top:2px;"><i class="fas fa-info-circle" style="font-size:0.7rem;"></i> ' + (it.instructions || it.notes) + '</div>';
                                    }

                                    itemsHtml += '<tr style="border-bottom: 1px solid #f7fafc;">';
                                    itemsHtml += '<td style="padding: 10px 15px;"><strong style="color:#2d3748;">' + itName + '</strong> ' + (itSize ? '<span class="badge" style="background:#edf2f7; color:#4a5568; font-size:0.75rem; padding:2px 6px; border-radius:4px; margin-left:4px;">' + itSize + '</span>' : '') + addonsText + notesText + '</td>';
                                    itemsHtml += '<td style="padding: 10px; text-align:center; font-weight:600; color:#4a5568;">' + itQty + '</td>';
                                    itemsHtml += '<td style="padding: 10px 15px; text-align:right; font-weight:700; color:#2d3748;">€' + itTotal + '</td>';
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
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Name:</strong> ${order.customer_name || 'N/A'}</p>
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Phone:</strong> <a href="tel:${order.customer_phone}" style="color:#3498db; font-weight:600; text-decoration:none;"><i class="fas fa-phone-alt" style="font-size:0.8rem; margin-right:4px;"></i>${order.customer_phone || '—'}</a></p>
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Address:</strong> ${order.customer_address || '<span style="color:#94a3b8;">N/A (Pickup)</span>'}</p>
                                    </div>
                                    <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                        <h5 style="margin: 0 0 10px 0; font-size: 0.92rem; font-weight: 700; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                                            <i class="fas fa-info-circle" style="color:#3498db; margin-right:6px;"></i> Order Details
                                        </h5>
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Type:</strong> ${orderTypeBadge}</p>
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Shop:</strong> <span style="font-weight:600; color:#2d3748;">${order.shop_name || 'Pizza One'}</span></p>
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Payment:</strong> <span style="text-transform:uppercase; font-weight:600; color:#4a5568;">${order.payment_method || 'Cash'}</span></p>
                                        <p style="margin: 5px 0; font-size: 0.88rem;"><strong>Placed At:</strong> <span style="color:#64748b;">${order.formatted_date}</span></p>
                                    </div>
                                </div>

                                ${itemsHtml}

                                ${order.notes ? `
                                <div style="margin-top: 15px; background: #fffbeb; border: 1px solid #fef3c7; padding: 12px 15px; border-radius: 8px;">
                                    <strong style="color: #92400e; font-size: 0.85rem;"><i class="fas fa-comment-alt" style="margin-right:5px;"></i> Customer Notes:</strong>
                                    <p style="margin: 4px 0 0 0; color: #78350f; font-size: 0.88rem; font-style: italic;">"${order.notes}"</p>
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
                    .catch(err => {
                        modalBody.innerHTML = '<div style="color:#e74c3c; text-align:center; padding:30px;"><i class="fas fa-exclamation-triangle fa-2x"></i><p style="margin-top:10px;">Failed to load order details. Please try again.</p></div>';
                    });
            });
        });

        window.addEventListener('click', function(event) {
            var modal = document.getElementById('viewOrderModal');
            if (event.target == modal) {
                closeOrderModal();
            }
        });
    });
</script>
