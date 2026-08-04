<?php
// admin/sub_categories.php - Manage Sub Categories
?>
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Manage Sub Categories</h4>
            <button class="btn btn-primary" onclick="showModal('addSubCategoryModal')">
                <i class="fas fa-plus"></i> Add Sub Category
            </button>
        </div>
        <div class="card-body">
            <form action="<?php echo base_url('admin/delete_multiple_categories'); ?>" method="post">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllSub" onclick="toggleAllSub(this)"></th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sub_categories as $sub): ?>
                        <tr>
                            <td><input type="checkbox" name="category_ids[]" value="<?php echo $sub->id; ?>"></td>
                            <td>
                                <?php if($sub->image): ?>
                                    <img src="<?php echo base_url('assets/images/categories/'.$sub->image); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="" style="border-radius: 5px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $sub->name; ?></td>
                            <td>
                                <?php
                                    // Find parent name
                                    $parent = array_filter($parent_categories, function($c) use ($sub) { return $c->id == $sub->parent_id; });
                                    $parent_name = !empty($parent) ? reset($parent)->name : 'Unknown';
                                    echo '<span class="badge" style="background: var(--info); color:#fff; padding:3px 8px; border-radius:10px; font-size:0.7rem;">'.$parent_name.'</span>';
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url('admin/edit_category/'.$sub->id); ?>" class="btn btn-primary btn-sm" style="background: var(--info);">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="showConfirm('<?php echo base_url('admin/delete_category/'.$sub->id); ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="mt-2">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete selected sub categories?');">Delete Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Add Sub Category -->
<div id="addSubCategoryModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Sub Category</h3>
            <span onclick="closeModal('addSubCategoryModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <?php echo form_open_multipart('admin/add_sub_category'); ?>
                <div class="form-group">
                    <label>Sub Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Veg Pizza" required>
                </div>
                <div class="form-group">
                    <label>Parent Category</label>
                    <select name="parent_id" class="form-control" required>
                        <?php foreach($parent_categories as $pcat): ?>
                            <option value="<?php echo $pcat->id; ?>"><?php echo $pcat->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Add Sub Category</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function toggleAllSub(source) {
    var checkboxes = document.getElementsByName('category_ids[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
