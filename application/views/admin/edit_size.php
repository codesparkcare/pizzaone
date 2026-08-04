<div class="row" style="display: flex; justify-content: center;">
    <div style="width: 500px;">
        <div class="card">
            <div class="card-header">
                <h4>Edit Size: <?php echo $size->name; ?></h4>
                <a href="<?php echo base_url('admin/sizes'); ?>" class="btn btn-sm" style="background: var(--secondary); color: #fff;">Back</a>
            </div>
            <div class="card-body">
                <?php echo form_open('admin/update_size/'.$size->id); ?>
                    <div class="form-group">
                        <label>Size Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $size->name; ?>" required>
                    </div>

                    <div class="form-group">
                        <label style="display: block; margin-bottom: 10px;">Applicable Categories</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 10px;">
                            <?php foreach($categories as $cat): ?>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.9rem;">
                                    <input type="checkbox" name="category_ids[]" value="<?php echo $cat->id; ?>" <?php echo (in_array($cat->id, $selected_categories)) ? 'checked' : ''; ?>>
                                    <?php echo $cat->name; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Update Size</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
