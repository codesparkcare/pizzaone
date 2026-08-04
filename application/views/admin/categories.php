<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Manage Categories</h4>
            <button class="btn btn-primary" onclick="showModal('addCategoryModal')">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
            <div class="card-body"><form action="<?php echo base_url('admin/delete_multiple_categories'); ?>" method="post">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td><input type="checkbox" name="category_ids[]" value="<?php echo $cat->id; ?>"></td>
                            <td>
                                <?php if($cat->image): ?>
                                    <img src="<?php echo base_url('assets/images/categories/'.$cat->image); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="" style="border-radius: 5px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $cat->name; ?></td>
                            <td>
                                <a href="<?php echo base_url('admin/edit_category/'.$cat->id); ?>" class="btn btn-primary btn-sm" style="background: var(--info);">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="showConfirm('<?php echo base_url('admin/delete_category/'.$cat->id); ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
<div class="mt-2">
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete selected categories?');">Delete Selected</button>
</div>
</form>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Modal for Add Category -->
<div id="addCategoryModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Category</h3>
            <span onclick="closeModal('addCategoryModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <?php echo form_open_multipart('admin/add_category'); ?>
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Veg Pizza" required>
                </div>
                <div class="form-group">
                    <label>Category Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Add Category</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
function toggleAll(source) {
    var checkboxes = document.getElementsByName('category_ids[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
