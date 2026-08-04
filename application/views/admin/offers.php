<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Manage Offers</h4>
            <button class="btn btn-primary" onclick="showModal('addOfferModal')">
                <i class="fas fa-plus"></i> Add Offer
            </button>
        </div>
        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Offer Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($offers as $o): ?>
                    <tr>
                        <td><?php echo $o->id; ?></td>
                        <td><?php echo htmlspecialchars($o->offer_name); ?></td>
                        <td>
                            <span class="badge" style="background: var(--success); color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 0.75rem;">Active</span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($o->created_at)); ?></td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_offer/'.$o->id); ?>" class="btn btn-primary btn-sm" style="background: var(--info);">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="showConfirm('<?php echo base_url('admin/delete_offer/'.$o->id); ?>')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add Offer -->
<div id="addOfferModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Offer</h3>
            <span onclick="closeModal('addOfferModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <?php echo form_open('admin/add_offer'); ?>
                <div class="form-group">
                    <label>Offer Name</label>
                    <input type="text" name="offer_name" class="form-control" placeholder="e.g. Buy 1 Get 1 Free, 50% Off" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Save Offer</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
