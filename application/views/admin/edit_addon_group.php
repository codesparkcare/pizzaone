<div class="row" style="display: flex; justify-content: center; padding: 20px;">
    <div style="max-width: 800px; width: 100%;">
        <div class="card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 20px;">
            <div class="card-header" style="padding: 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 1.5rem; font-weight: 600; color: var(--secondary);">Edit Addon Group</h4>
                <a href="<?php echo base_url('admin/addon_groups'); ?>" class="btn btn-sm" style="background: var(--gray); color: #fff; padding: 8px 20px; border-radius: 50px; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            <div class="card-body" style="padding: 30px;">
                <?php echo form_open('admin/update_addon_group/'.$group->id); ?>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-weight: 600; margin-bottom: 8px; display: block;">Addon Group Title *</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $group->name; ?>" required style="border-radius: 10px; padding: 12px; margin-bottom: 0;">
                    </div>
                    <?php
                    $current_condition = 'choose_any';
                    if ($group->min_selections == 1 && $group->max_selections == 1) {
                        $current_condition = 'choose_1';
                    } elseif ($group->min_selections == 2 && $group->max_selections == 2) {
                        $current_condition = 'choose_2';
                    } elseif ($group->min_selections == 3 && $group->max_selections == 3) {
                        $current_condition = 'choose_3';
                    } elseif ($group->min_selections == 4 && $group->max_selections == 4) {
                        $current_condition = 'choose_4';
                    } elseif ($group->min_selections == 5 && $group->max_selections == 5) {
                        $current_condition = 'choose_5';
                    }
                    ?>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-weight: 600; margin-bottom: 8px; display: block;">Selection Condition *</label>
                        <select name="selection_condition" class="form-control" required style="border-radius: 10px; padding: 12px; margin-bottom: 0; height: auto;">
                            <option value="choose_1" <?php echo ($current_condition == 'choose_1') ? 'selected' : ''; ?>>Choose 1</option>
                            <option value="choose_2" <?php echo ($current_condition == 'choose_2') ? 'selected' : ''; ?>>Choose 2</option>
                            <option value="choose_3" <?php echo ($current_condition == 'choose_3') ? 'selected' : ''; ?>>Choose 3</option>
                            <option value="choose_4" <?php echo ($current_condition == 'choose_4') ? 'selected' : ''; ?>>Choose 4</option>
                            <option value="choose_5" <?php echo ($current_condition == 'choose_5') ? 'selected' : ''; ?>>Choose 5</option>
                            <option value="choose_any" <?php echo ($current_condition == 'choose_any') ? 'selected' : ''; ?>>Choose Any (Optional)</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-top: 30px; margin-bottom: 30px;">
                        <label style="display:block; margin-bottom:15px; font-weight:700; color: var(--secondary); font-size: 1.1rem; border-bottom: 2px solid var(--primary); padding-bottom: 5px; width: fit-content;">
                            <i class="fas fa-plus-circle"></i> Linked Addons
                        </label>
                        <div style="background: #fdfdfd; border: 1px solid #eee; border-radius: 15px; padding: 20px; max-height: 350px; overflow-y: auto;">
                            <?php if(!empty($addons)): ?>
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px;">
                                    <?php foreach($addons as $addon): ?>
                                        <label class="addon-item-label" style="display: flex; align-items: center; gap: 12px; margin: 0; font-size: 1rem; cursor: pointer; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #f0f0f0; transition: all 0.2s;">
                                            <input type="checkbox" name="addon_ids[]" value="<?php echo $addon->id; ?>" <?php echo in_array($addon->id, $linked_addon_ids) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                                            <span style="flex: 1;">
                                                <strong><?php echo $addon->name; ?></strong>
                                                <span style="display: block; font-size: 0.8rem; color: var(--gray);">
                                                    €<?php echo number_format($addon->price, 2); ?> | <?php echo ucfirst($addon->type); ?>
                                                </span>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="text-align: center; color: var(--gray); font-style: italic; margin: 0;">No addons available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; border-radius: 50px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<style>
    .addon-item-label:hover {
        border-color: var(--primary) !important;
        background-color: #fdf5f5 !important;
    }
</style>
