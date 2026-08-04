<div class="row" style="display: flex; justify-content: center;">
    <div style="width: 500px;">
        <div class="card">
            <div class="card-header">
                <h4>Edit Category: <?php echo $category->name; ?></h4>
                <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-sm" style="background: var(--secondary); color: #fff;">Back</a>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('admin/update_category/'.$category->id); ?>
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $category->name; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Parent Category</label>
                        <select name="parent_id" class="form-control">
                            <option value="0">None (Main Category)</option>
                            <?php foreach($parent_categories as $pcat): ?>
                                <option value="<?php echo $pcat->id; ?>" <?php echo ($pcat->id == $category->parent_id) ? 'selected' : ''; ?>>
                                    <?php echo $pcat->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Current Image</label>
                        <div style="margin-bottom: 10px;">
                            <?php if($category->image): ?>
                                <img src="<?php echo base_url('assets/images/categories/'.$category->image); ?>" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                            <?php else: ?>
                                <p style="color: var(--gray);">No image uploaded.</p>
                            <?php endif; ?>
                        </div>
                        <label>Change Image (Optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Update Category</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
