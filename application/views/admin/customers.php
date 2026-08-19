<style>
    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 25px;
    }

    .small-stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        width: 220px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .small-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    .stat-top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon-box.red {
        background: rgba(231, 76, 60, 0.12);
        color: #e74c3c;
    }

    .stat-icon-box.green {
        background: rgba(46, 204, 113, 0.12);
        color: #2ecc71;
    }

    .stat-icon-box.blue {
        background: rgba(37, 99, 235, 0.12);
        color: #2563eb;
    }

    .stat-bottom-row {
        margin-top: 15px;
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.1;
    }

    .stat-label-text {
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-btn-group {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .action-btn-group button, .action-btn-group a {
        padding: 6px 10px;
        font-size: 0.8rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-view-cust { background: #3498db; color: #fff; }
    .btn-view-cust:hover { background: #2980b9; color: #fff; }
    .btn-edit-cust { background: #f39c12; color: #fff; }
    .btn-edit-cust:hover { background: #d68910; color: #fff; }
    .btn-del-cust { background: #e74c3c; color: #fff; }
    .btn-del-cust:hover { background: #c0392b; color: #fff; }
</style>

<!-- Small Square Stats Row -->
<div class="stats-container">
    <div class="small-stat-card">
        <div class="stat-top-row">
            <div class="stat-icon-box red">
                <i class="fas fa-users"></i>
            </div>
            <span class="badge" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; font-size: 0.7rem; padding: 4px 8px; border-radius: 10px;">Total</span>
        </div>
        <div class="stat-bottom-row">
            <h3 class="stat-number"><?php echo count($customers); ?></h3>
            <div class="stat-label-text">Customers</div>
        </div>
    </div>

    <div class="small-stat-card">
        <div class="stat-top-row">
            <div class="stat-icon-box green">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <span class="badge" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71; font-size: 0.7rem; padding: 4px 8px; border-radius: 10px;">Orders</span>
        </div>
        <div class="stat-bottom-row">
            <?php $total_orders = array_sum(array_column($customers, 'total_orders')); ?>
            <h3 class="stat-number"><?php echo $total_orders; ?></h3>
            <div class="stat-label-text">Total Orders</div>
        </div>
    </div>

    <div class="small-stat-card">
        <div class="stat-top-row">
            <div class="stat-icon-box blue">
                <i class="fas fa-euro-sign"></i>
            </div>
            <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: #2563eb; font-size: 0.7rem; padding: 4px 8px; border-radius: 10px;">Revenue</span>
        </div>
        <div class="stat-bottom-row">
            <?php $total_spent = array_sum(array_column($customers, 'total_spent')); ?>
            <h3 class="stat-number">€<?php echo number_format($total_spent, 2); ?></h3>
            <div class="stat-label-text">Total Revenue</div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-users"></i> Customer Management</h4>
            <button class="btn btn-primary" onclick="openAddCustomerModal()" style="background: var(--primary); border: none; border-radius: 8px; padding: 8px 16px; font-weight: 600;">
                <i class="fas fa-user-plus"></i> Add New Customer
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable table-hover">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Customer Name</th>
                            <th>Contact Info</th>
                            <th>Address</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Joined Date</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $c): ?>
                        <tr>
                            <td><strong>#<?php echo $c->id; ?></strong></td>
                            <td>
                                <div style="font-weight: 600; color: #2c3e50;">
                                    <?php echo htmlspecialchars($c->first_name . ' ' . $c->last_name); ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; color: #34495e;">
                                    <div><i class="fas fa-envelope text-muted"></i> <?php echo htmlspecialchars($c->email); ?></div>
                                    <?php if(!empty($c->phone)): ?>
                                        <div><i class="fas fa-phone text-muted"></i> <?php echo htmlspecialchars($c->phone); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: #7f8c8d;">
                                    <?php echo !empty($c->address) ? htmlspecialchars(substr($c->address, 0, 35)) . (strlen($c->address) > 35 ? '...' : '') : '<em class="text-muted">N/A</em>'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(52, 152, 219, 0.15); color: #2980b9; padding: 5px 10px; border-radius: 12px; font-weight: 600;">
                                    <?php echo $c->total_orders; ?> Order(s)
                                </span>
                            </td>
                            <td>
                                <strong style="color: #27ae60;">€<?php echo number_format($c->total_spent, 2); ?></strong>
                            </td>
                            <td>
                                <span style="font-size: 0.82rem; color: #7f8c8d;">
                                    <?php echo date('d M Y', strtotime($c->created_at)); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <button type="button" class="btn-view-cust" onclick="viewCustomerDetails(<?php echo $c->id; ?>)" title="View Details & Orders">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn-edit-cust" onclick="openEditCustomerModal(<?php echo htmlspecialchars(json_encode($c)); ?>)" title="Edit Customer">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="javascript:void(0);" onclick="showConfirm('<?php echo base_url('admin/delete_customer/'.$c->id); ?>', 'Are you sure you want to delete customer <?php echo htmlspecialchars($c->first_name); ?>?')" class="btn-del-cust" title="Delete Customer">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add New Customer -->
<div id="addCustomerModal" class="custom-modal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Add New Customer</h3>
            <span onclick="closeModal('addCustomerModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <?php echo form_open('admin/add_customer'); ?>
                <div class="row" style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>First Name <span style="color:red;">*</span></label>
                        <input type="text" name="first_name" class="form-control" required placeholder="John">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Last Name <span style="color:red;">*</span></label>
                        <input type="text" name="last_name" class="form-control" required placeholder="Doe">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label>Email Address <span style="color:red;">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="john.doe@example.com">
                </div>

                <div class="row" style="display: flex; gap: 15px; margin-top: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="01 34 19 94 56">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Password <span style="color:red;">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="4" placeholder="••••••••">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Enter delivery address..."></textarea>
                </div>

                <div class="modal-footer" style="margin-top: 20px;">
                    <button type="button" onclick="closeModal('addCustomerModal')" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none;">Save Customer</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal: Edit Customer -->
<div id="editCustomerModal" class="custom-modal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Customer</h3>
            <span onclick="closeModal('editCustomerModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editCustomerForm" action="" method="POST">
                <div class="row" style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>First Name <span style="color:red;">*</span></label>
                        <input type="text" id="edit_first_name" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Last Name <span style="color:red;">*</span></label>
                        <input type="text" id="edit_last_name" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label>Email Address <span style="color:red;">*</span></label>
                    <input type="email" id="edit_email" name="email" class="form-control" required>
                </div>

                <div class="row" style="display: flex; gap: 15px; margin-top: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Phone Number</label>
                        <input type="text" id="edit_phone" name="phone" class="form-control">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>New Password <small style="color:#7f8c8d;">(Leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label>Address</label>
                    <textarea id="edit_address" name="address" class="form-control" rows="3"></textarea>
                </div>

                <div class="modal-footer" style="margin-top: 20px;">
                    <button type="button" onclick="closeModal('editCustomerModal')" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none;">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: View Customer Details & Order History -->
<div id="viewCustomerModal" class="custom-modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3><i class="fas fa-user-circle"></i> Customer Profile & Order History</h3>
            <span onclick="closeModal('viewCustomerModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body" id="customerModalBody" style="max-height: 75vh; overflow-y: auto;">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--primary);"></i>
                <p style="margin-top: 10px; color: #7f8c8d;">Loading customer details...</p>
            </div>
        </div>
    </div>
</div>

<script>
function openAddCustomerModal() {
    document.getElementById('addCustomerModal').style.display = 'flex';
}

function openEditCustomerModal(customer) {
    document.getElementById('editCustomerForm').action = '<?php echo base_url("admin/edit_customer/"); ?>' + customer.id;
    document.getElementById('edit_first_name').value = customer.first_name || '';
    document.getElementById('edit_last_name').value = customer.last_name || '';
    document.getElementById('edit_email').value = customer.email || '';
    document.getElementById('edit_phone').value = customer.phone || '';
    document.getElementById('edit_address').value = customer.address || '';
    document.getElementById('editCustomerModal').style.display = 'flex';
}

function viewCustomerDetails(customerId) {
    const modal = document.getElementById('viewCustomerModal');
    const body = document.getElementById('customerModalBody');
    modal.style.display = 'flex';
    body.innerHTML = `
        <div style="text-align: center; padding: 2rem;">
            <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--primary);"></i>
            <p style="margin-top: 10px; color: #7f8c8d;">Loading customer profile...</p>
        </div>
    `;

    fetch('<?php echo base_url("admin/customer_details_json/"); ?>' + customerId)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const c = data.customer;
                const orders = data.orders;
                
                let ordersHtml = '';
                if (orders.length > 0) {
                    ordersHtml = `
                        <table class="table table-bordered table-sm" style="margin-top: 15px;">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th>Order #</th>
                                    <th>Shop</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${orders.map(o => `
                                    <tr>
                                        <td><strong>#${o.id}</strong></td>
                                        <td>${o.shop_name || 'N/A'}</td>
                                        <td>€${parseFloat(o.total_amount).toFixed(2)}</td>
                                        <td><span class="badge bg-secondary">${o.status}</span></td>
                                        <td>${o.created_at}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                } else {
                    ordersHtml = `<p style="color: #7f8c8d; font-style: italic; margin-top: 10px;">No order history found for this customer.</p>`;
                }

                body.innerHTML = `
                    <div style="background: #fdf6f0; border-radius: 12px; padding: 18px; margin-bottom: 20px; border: 1px solid #f3d6c6;">
                        <h4 style="margin:0 0 10px 0; color: #d35400;"><i class="fas fa-id-card"></i> ${c.name}</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.9rem;">
                            <div><strong>Email:</strong> ${c.email}</div>
                            <div><strong>Phone:</strong> ${c.phone}</div>
                            <div><strong>Address:</strong> ${c.address}</div>
                            <div><strong>Joined:</strong> ${c.created_at}</div>
                        </div>
                    </div>
                    <div>
                        <h5 style="font-weight: 600; color: #2c3e50;"><i class="fas fa-shopping-basket"></i> Order History (${orders.length})</h5>
                        ${ordersHtml}
                    </div>
                `;
            } else {
                body.innerHTML = `<p style="color: red; padding: 1rem;">${data.message}</p>`;
            }
        })
        .catch(err => {
            console.error(err);
            body.innerHTML = `<p style="color: red; padding: 1rem;">Failed to load customer details.</p>`;
        });
}
</script>
