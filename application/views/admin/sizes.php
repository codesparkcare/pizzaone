<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Manage Pizza Sizes</h4>
            <button class="btn btn-primary" onclick="showModal('addSizeModal')">
                <i class="fas fa-plus"></i> Add Size
            </button>
        </div>
        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Size Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sizes as $s): ?>
                    <tr>
                        <td><?php echo $s->id; ?></td>
                        <td><?php echo $s->name; ?></td>
                        <td>
                            <span class="badge" style="background: var(--success); color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 0.75rem;">Active</span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_size/'.$s->id); ?>" class="btn btn-primary btn-sm" style="background: var(--info);">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="showConfirm('<?php echo base_url('admin/delete_size/'.$s->id); ?>')">
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

<!-- Modal for Add Size -->
<div id="addSizeModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Size</h3>
            <span onclick="closeModal('addSizeModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <?php echo form_open('admin/add_size'); ?>
                <div class="form-group">
                    <label>Size Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Medium, Large, 12-inch" required>
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 10px;">Select Categories</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 10px;">
                        <?php foreach($categories as $cat): ?>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.9rem;">
                                <input type="checkbox" name="category_ids[]" value="<?php echo $cat->id; ?>">
                                <?php echo $cat->name; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Save Size</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
