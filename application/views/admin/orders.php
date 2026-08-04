<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Customer Orders</h4>
        </div>
        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Shop</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $o): ?>
                    <tr>
                        <td>#<?php echo $o->id; ?></td>
                        <td><?php echo $o->shop_name ? $o->shop_name : 'N/A'; ?></td>
                        <td><?php echo $o->customer_name; ?></td>
                        <td><?php echo $o->customer_phone; ?></td>
                        <td>$<?php echo $o->total_amount; ?></td>
                        <td>
                            <span class="badge" style="background: <?php 
                                echo ($o->status == 'pending') ? '#f1c40f' : 
                                     (($o->status == 'delivered') ? '#2ecc71' : 
                                     (($o->status == 'cancelled') ? '#e74c3c' : '#3498db')); 
                            ?>; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 0.75rem;">
                                <?php echo ucfirst($o->status); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($o->created_at)); ?></td>
                        <td>
                            <div style="display: flex; gap: 5px; align-items: center;">
                                <button type="button" class="btn btn-info btn-sm view-order-btn" data-id="<?php echo $o->id; ?>" style="background: #17a2b8; color: #fff; padding: 4px 10px; font-size: 0.8rem; height: 31px; border:none; border-radius: 8px;">View</button>
                                <form action="<?php echo base_url('admin/update_order_status/'.$o->id); ?>" method="POST" style="display: flex; gap: 5px; margin: 0;">
                                    <select name="status" class="form-control" style="margin: 0; padding: 5px; width: 120px; font-size: 0.8rem; height: 31px;">
                                        <option value="pending" <?php echo ($o->status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo ($o->status == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="preparing" <?php echo ($o->status == 'preparing') ? 'selected' : ''; ?>>Preparing</option>
                                        <option value="on_the_way" <?php echo ($o->status == 'on_the_way') ? 'selected' : ''; ?>>On the Way</option>
                                        <option value="delivered" <?php echo ($o->status == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo ($o->status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm" style="height: 31px;">Update</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="viewOrderModal" class="custom-modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="modalOrderTitle">Order Details</h3>
            <span class="close-modal" onclick="closeOrderModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalOrderBody">
            <p>Loading...</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeOrderModal()" style="background: #95a5a6; color:#fff;">Close</button>
        </div>
    </div>
</div>

<script>
    function closeOrderModal() {
        document.getElementById('viewOrderModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var viewBtns = document.querySelectorAll('.view-order-btn');
        viewBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var orderId = this.getAttribute('data-id');
                var modal = document.getElementById('viewOrderModal');
                var modalBody = document.getElementById('modalOrderBody');
                var modalTitle = document.getElementById('modalOrderTitle');
                
                modalTitle.innerText = 'Order Details: #' + orderId;
                modalBody.innerHTML = '<div style="text-align:center; padding: 20px;"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>';
                modal.style.display = 'block';

                fetch('<?php echo base_url("admin/ajax_view_order/"); ?>' + orderId)
                    .then(response => response.json())
                    .then(data => {
                        if(data.status === 'success') {
                            var order = data.order;
                            var html = `
                                <div style="display:flex; gap: 20px; flex-wrap: wrap;">
                                    <div style="flex:1; min-width: 250px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
                                        <h5 style="margin-top:0; border-bottom: 1px solid #ddd; padding-bottom: 8px;">Customer</h5>
                                        <p><strong>Name:</strong> ${order.customer_name}</p>
                                        <p><strong>Phone:</strong> ${order.customer_phone}</p>
                                        <p><strong>Address:</strong> ${order.customer_address || 'N/A'}</p>
                                    </div>
                                    <div style="flex:1; min-width: 250px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
                                        <h5 style="margin-top:0; border-bottom: 1px solid #ddd; padding-bottom: 8px;">Order Info</h5>
                                        <p><strong>Type:</strong> <span style="text-transform:capitalize;">${order.order_type}</span></p>
                                        <p><strong>Shop:</strong> ${order.shop_name || 'N/A'}</p>
                                        <p><strong>Payment:</strong> <span style="text-transform:uppercase;">${order.payment_method}</span></p>
                                        <p><strong>Status:</strong> <span style="text-transform:capitalize;">${order.status}</span></p>
                                        <p><strong>Date:</strong> ${order.formatted_date}</p>
                                    </div>
                                </div>
                                <div style="margin-top: 15px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
                                    <h5 style="margin-top:0; border-bottom: 1px solid #ddd; padding-bottom: 8px;">Notes</h5>
                                    <p>${order.notes || '<em>No special notes provided.</em>'}</p>
                                </div>
                                <div style="margin-top: 15px; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #eee; text-align:right;">
                                    <p>Subtotal: $${order.subtotal}</p>
                                    <p>Delivery Fee: $${order.delivery_fee}</p>
                                    <h4 style="color: #e74c3c; margin-bottom:0; margin-top:10px;">Total: $${order.total_amount}</h4>
                                </div>
                            `;
                            modalBody.innerHTML = html;
                        } else {
                            modalBody.innerHTML = '<p style="color:red; text-align:center;">' + data.message + '</p>';
                        }
                    })
                    .catch(err => {
                        modalBody.innerHTML = '<p style="color:red; text-align:center;">Failed to load order details. Please try again.</p>';
                    });
            });
        });
        
        window.onclick = function(event) {
            var modal = document.getElementById('viewOrderModal');
            if (event.target == modal) {
                closeOrderModal();
            }
        }
    });
</script>
