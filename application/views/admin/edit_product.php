<div class="row" style="display: flex; justify-content: center; padding: 20px;">
    <div style="max-width: 900px; width: 100%;">
        <div class="card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 20px;">
            <div class="card-header" style="padding: 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 1.5rem; font-weight: 600; color: var(--secondary);">Edit Product: <?php echo $product->name; ?></h4>
                <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-sm" style="background: var(--gray); color: #fff; padding: 8px 20px; border-radius: 50px; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            <div class="card-body" style="padding: 30px;">
                <?php echo form_open_multipart('admin/update_product/'.$product->id); ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 40px;">
                        <!-- Left Column -->
                        <div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Product Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $product->name; ?>" required style="border-radius: 10px; padding: 12px;">
                            </div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Category</label>
                                <select name="category_id" id="categorySelectEdit" class="form-control" required onchange="loadSubCatsAndSizesEdit(this.value)" style="border-radius: 10px; padding: 12px;">
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat->id; ?>" <?php echo ($cat->id == $product->category_id) ? 'selected' : ''; ?>>
                                            <?php echo $cat->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Sub Category</label>
                                <select name="subcategory_id" id="subCategorySelectEdit" class="form-control" style="border-radius: 10px; padding: 12px;">
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Select Offer <span style="color: var(--gray); font-weight: 400;">(Optional)</span></label>
                                <select name="offer_id" class="form-control" style="border-radius: 10px; padding: 12px;">
                                    <option value="">None</option>
                                    <?php foreach($offers as $offer): ?>
                                        <option value="<?php echo $offer->id; ?>" <?php echo (isset($product->offer_id) && $product->offer_id == $offer->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($offer->offer_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Base Price (€)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product->price; ?>" required style="border-radius: 10px; padding: 12px;">
                            </div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Description</label>
                                <textarea name="description" class="form-control" rows="5" style="border-radius: 10px; padding: 12px;"><?php echo $product->description; ?></textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <div class="form-group">
                                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Product Image</label>
                                <div style="margin-bottom: 15px; background: #f8f9fa; padding: 10px; border-radius: 15px; text-align: center; border: 2px dashed #ddd;">
                                    <?php if($product->image): ?>
                                        <img src="<?php echo base_url('assets/images/products/'.$product->image); ?>" alt="" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    <?php else: ?>
                                        <p style="color: #999; margin: 40px 0;">No image available</p>
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="image" class="form-control" style="border-radius: 10px; padding: 10px; font-size: 0.9rem;">
                            </div>
                            
                            <div id="sizeContainerEdit" style="margin-top: 30px;">
                                <label style="display:block; margin-bottom:15px; font-weight:700; color: var(--secondary); font-size: 1.1rem; border-bottom: 2px solid var(--primary); padding-bottom: 5px; width: fit-content;">
                                    <i class="fas fa-tags"></i> Sizes & Prices
                                </label>
                                <div style="background:#fcfcfc; padding:20px; border-radius:15px; border: 1px solid #eee; display:grid; gap:15px;">
                                    <?php foreach($sizes as $size): ?>
                                        <div style="display:grid; grid-template-columns: 1fr 120px; align-items:center; gap:15px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0;">
                                            <label style="display:flex; align-items:center; gap:12px; margin:0; font-size:1rem; cursor: pointer; font-weight: 500;">
                                                <input type="checkbox" name="size_ids[]" value="<?php echo $size->id; ?>" <?php echo ($size->ps_id) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                                                <?php echo $size->name; ?>
                                            </label>
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #666; font-weight: 600;">€</span>
                                                <input type="number" step="0.01" name="size_prices[]" class="form-control" style="margin:0; padding:8px 8px 8px 25px; border-radius: 8px; font-weight: 600;" placeholder="0.00" value="<?php echo $size->size_price; ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Addon Groups & Addons Section -->
                    <div style="margin-top: 40px; padding: 20px; background: #f9f9f9; border-radius: 15px; border: 1px solid #eee;">
                        <label style="display:block; margin-bottom:15px; font-weight:700; color: var(--secondary); font-size: 1.1rem; border-bottom: 2px solid var(--primary); padding-bottom: 5px; width: fit-content;">
                            <i class="fas fa-layer-group"></i> Addon Groups &amp; Addons
                        </label>
                        <div id="addonGroupsPanelEdit" style="background: #fff; border: 1px solid #ddd; border-radius: 10px; overflow: hidden; max-height: 400px; overflow-y: auto;">
                            <?php if (!empty($addon_groups)): ?>
                                <?php foreach ($addon_groups as $gi => $group): ?>
                                <?php $linked_group = isset($product_addon_groups[$group->id]); ?>
                                <div class="ag-block-edit" style="border-bottom: 1px solid #eee;">
                                    <!-- Group header row -->
                                    <div class="ag-header-edit" style="
                                        display: flex; align-items: center; gap: 10px;
                                        padding: 12px 16px;
                                        background: #f7f7fb;
                                        cursor: pointer;
                                        user-select: none;
                                    " onclick="toggleGroupEdit(<?php echo $gi; ?>)">
                                        <!-- Group checkbox -->
                                        <input type="checkbox"
                                               name="product_addon_group_ids[]"
                                               value="<?php echo $group->id; ?>"
                                               id="grp_edit_<?php echo $group->id; ?>"
                                               <?php echo $linked_group ? 'checked' : ''; ?>
                                               style="width:18px;height:18px;accent-color:#e74c3c;flex-shrink:0;"
                                               onclick="event.stopPropagation();">
                                        <!-- Group icon + name -->
                                        <i class="fas fa-layer-group" style="color:#9b59b6;font-size:0.9rem;"></i>
                                        <strong style="font-size:1rem;color:#2c3e50;flex:1;">
                                            <?php echo htmlspecialchars($group->name); ?>
                                        </strong>
                                        <!-- Condition badge -->
                                        <?php
                                            $min = $group->min_selections ?? 0;
                                            $max = $group->max_selections ?? 999;
                                            if ($min==1&&$max==1)      $cond = 'Choose 1';
                                            elseif ($min==2&&$max==2)  $cond = 'Choose 2';
                                            elseif ($min==3&&$max==3)  $cond = 'Choose 3';
                                            elseif ($min==4&&$max==4)  $cond = 'Choose 4';
                                            elseif ($min==5&&$max==5)  $cond = 'Choose 5';
                                            else                        $cond = 'Choose Any';
                                        ?>
                                        <span style="font-size:0.75rem;background:#ede7f6;color:#7b1fa2;padding:3px 10px;border-radius:20px;font-weight:600;">
                                            <?php echo $cond; ?>
                                        </span>
                                        <!-- Addon count badge -->
                                        <span style="font-size:0.75rem;background:#eee;color:#888;padding:3px 10px;border-radius:20px;">
                                            <?php echo count($group->addons ?? []); ?> addon<?php echo count($group->addons ?? []) != 1 ? 's' : ''; ?>
                                        </span>
                                        <!-- Toggle arrow -->
                                        <i class="fas fa-chevron-down ag-arrow-edit" id="arrow_edit_<?php echo $gi; ?>"
                                           style="color:#aaa;font-size:0.8rem;transition:transform 0.2s;"></i>
                                    </div>

                                    <!-- Addons inside this group -->
                                    <div class="ag-addons-edit" id="ag_edit_<?php echo $gi; ?>" style="display:none; background:#fff; padding:10px 16px 16px 40px;">
                                        <?php if (!empty($group->addons)): ?>
                                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-top:8px;">
                                                <?php foreach ($group->addons as $addon): ?>
                                                <?php $linked_addon = in_array($addon->id, isset($product_addon_ids) ? $product_addon_ids : []); ?>
                                                <label style="display:flex;align-items:center;gap:10px;padding:8px 12px;
                                                              background:#fafafa;border:1px solid #eee;border-radius:8px;
                                                              cursor:pointer;font-size:0.9rem;transition:background 0.15s;"
                                                       onmouseover="this.style.background='#f3e5f5'"
                                                       onmouseout="this.style.background='#fafafa'">
                                                    <input type="checkbox"
                                                           name="addon_ids[]"
                                                           value="<?php echo $addon->id; ?>"
                                                           <?php echo $linked_addon ? 'checked' : ''; ?>
                                                           style="width:16px;height:16px;accent-color:#9b59b6;flex-shrink:0;">
                                                    <span>
                                                        <strong style="color:#2c3e50;"><?php echo htmlspecialchars($addon->name); ?></strong>
                                                        <span style="display:block;font-size:0.75rem;color:#aaa;">
                                                            <?php echo ($addon->price > 0) ? '+€'.number_format($addon->price,2) : 'Free'; ?>
                                                            &bull; <?php echo ucfirst($addon->type ?? 'extra'); ?>
                                                        </span>
                                                    </span>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p style="color:#ccc;font-size:0.85rem;margin:8px 0 0;font-style:italic;">
                                                No addons linked to this group yet.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="padding:25px;text-align:center;color:#ccc;">
                                    <i class="fas fa-layer-group" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:0.3;"></i>
                                    <p style="margin:0;font-size:0.95rem;">No addon groups yet.
                                        <a href="<?php echo base_url('admin/addon_groups'); ?>">Create groups →</a>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <small style="color:#999;margin-top:8px;display:block;">
                            <i class="fas fa-info-circle"></i>
                            Check a <strong>group</strong> to link it to the product. Check individual <strong>addons</strong> to attach them directly.
                        </small>
                    </div>

<script>
// Accordion toggle for addon groups in edit view
function toggleGroupEdit(index) {
    const panel  = document.getElementById('ag_edit_' + index);
    const arrow  = document.getElementById('arrow_edit_' + index);
    const isOpen = panel.style.display === 'block';
    panel.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}
</script>

                    <div style="margin-top: 40px; display: flex; gap: 15px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1; padding: 15px; border-radius: 50px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
function loadSubCatsAndSizesEdit(categoryId) {
    const subSelect = document.getElementById('subCategorySelectEdit');
    const container = document.getElementById('sizeContainerEdit');
    
    // Reset subcategories
    subSelect.innerHTML = '<option value="">Select Sub Category</option>';
    
    if (!categoryId) {
        container.innerHTML = '';
        return;
    }

    // Load Sub Categories
    fetch('<?php echo base_url('admin/get_subcategories_by_category/'); ?>' + categoryId)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.name;
                    if (<?php echo json_encode($product->subcategory_id); ?> == sub.id) {
                        opt.selected = true;
                    }
                    subSelect.appendChild(opt);
                });
            }
        })
        .catch(error => console.error('Error loading subcategories:', error));

    // Load Sizes
    container.innerHTML = '<p style="font-size:0.8rem; color:var(--gray);">Loading sizes...</p>';
    
    fetch('<?php echo base_url('admin/get_sizes_by_category/'); ?>' + categoryId)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let html = '<label style="display:block; margin-bottom:15px; font-weight:700; color: var(--secondary); font-size: 1.1rem; border-bottom: 2px solid var(--primary); padding-bottom: 5px; width: fit-content;"><i class="fas fa-tags"></i> Sizes & Prices</label>';
                html += '<div style="background:#fcfcfc; padding:20px; border-radius:15px; border: 1px solid #eee; display:grid; gap:15px;">';
                data.forEach(size => {
                    html += `
                        <div style="display:grid; grid-template-columns: 1fr 120px; align-items:center; gap:15px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0;">
                            <label style="display:flex; align-items:center; gap:12px; margin:0; font-size:1rem; cursor: pointer; font-weight: 500;">
                                <input type="checkbox" name="size_ids[]" value="${size.id}" ${size.ps_id ? 'checked' : ''} style="width: 18px; height: 18px; accent-color: var(--primary);">
                                ${size.name}
                            </label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #666; font-weight: 600;">€</span>
                                <input type="number" step="0.01" name="size_prices[]" class="form-control" style="margin:0; padding:8px 8px 8px 25px; border-radius: 8px; font-weight: 600;" placeholder="0.00" value="${size.size_price || ''}">
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '';
            }
        });
}
</script>
