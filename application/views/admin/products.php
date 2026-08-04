<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Manage Products</h4>
            <button class="btn btn-primary" onclick="showModal('addProductModal')">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td>
                            <?php if($p->image): ?>
                                <img src="<?php echo base_url('assets/images/products/'.$p->image); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/50" alt="" style="border-radius: 5px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo $p->name; ?></td>
                        <td><?php echo $p->category_name; ?></td>
                        <td><?php echo $p->subcategory_name ? $p->subcategory_name : '-'; ?></td>
                        <td>€<?php echo $p->price; ?></td>
                        <td>
                            <span class="badge" style="background: var(--success); color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 0.75rem;">Active</span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_product/'.$p->id); ?>" class="btn btn-primary btn-sm" style="background: var(--info);">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="showConfirm('<?php echo base_url('admin/delete_product/'.$p->id); ?>')">
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

<!-- Modal for Add Product -->
<div id="addProductModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Product</h3>
            <span onclick="closeModal('addProductModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
            <?php echo form_open_multipart('admin/add_product'); ?>
                <!-- Step 1: Select Category -->
                <div class="form-group">
                    <label><strong>1. Category</strong></label>
                    <select name="category_id" id="categorySelect" class="form-control" required onchange="loadSubCategoriesAndSizes(this.value)">
                        <option value="">Select Category</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Step 2: Select Sub Category -->
                <div class="form-group">
                    <label><strong>2. Sub Category</strong></label>
                    <select name="subcategory_id" id="subCategorySelect" class="form-control" disabled>
                        <option value="">Select Sub Category</option>
                    </select>
                </div>

                <!-- Select Offer (Optional) -->
                <div class="form-group">
                    <label><strong>Select Offer</strong> <span style="color: var(--gray); font-weight: 400;">(Optional)</span></label>
                    <select name="offer_id" class="form-control">
                        <option value="">None</option>
                        <?php foreach($offers as $offer): ?>
                            <option value="<?php echo $offer->id; ?>"><?php echo htmlspecialchars($offer->offer_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Step 3: Select Size -->
                <div id="sizeContainer" style="margin-bottom: 20px;">
                    <!-- Sizes will be loaded here via AJAX -->
                </div>

                <!-- Step 4: Addon Groups (with addons listed inside each group) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label><strong>4. Addon Groups &amp; Addons</strong></label>
                    <div id="addonGroupsPanel" style="border: 1px solid #ddd; border-radius: 10px; overflow: hidden; max-height: 320px; overflow-y: auto;">
                        <?php if (!empty($addon_groups)): ?>
                            <?php foreach ($addon_groups as $gi => $group): ?>
                            <div class="ag-block" style="border-bottom: 1px solid #eee;">
                                <!-- Group header row -->
                                <div class="ag-header" style="
                                    display: flex; align-items: center; gap: 10px;
                                    padding: 10px 14px;
                                    background: #f7f7fb;
                                    cursor: pointer;
                                    user-select: none;
                                " onclick="toggleGroup(<?php echo $gi; ?>)">
                                    <!-- Group checkbox -->
                                    <input type="checkbox"
                                           name="product_addon_group_ids[]"
                                           value="<?php echo $group->id; ?>"
                                           id="grp_<?php echo $group->id; ?>"
                                           style="width:16px;height:16px;accent-color:#e74c3c;flex-shrink:0;"
                                           onclick="event.stopPropagation();">
                                    <!-- Group icon + name -->
                                    <i class="fas fa-layer-group" style="color:#9b59b6;font-size:0.85rem;"></i>
                                    <strong style="font-size:0.9rem;color:#2c3e50;flex:1;">
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
                                    <span style="font-size:0.7rem;background:#ede7f6;color:#7b1fa2;padding:2px 8px;border-radius:20px;font-weight:600;">
                                        <?php echo $cond; ?>
                                    </span>
                                    <!-- Addon count badge -->
                                    <span style="font-size:0.72rem;background:#eee;color:#888;padding:2px 8px;border-radius:20px;">
                                        <?php echo count($group->addons ?? []); ?> addon<?php echo count($group->addons ?? []) != 1 ? 's' : ''; ?>
                                    </span>
                                    <!-- Toggle arrow -->
                                    <i class="fas fa-chevron-down ag-arrow" id="arrow_<?php echo $gi; ?>"
                                       style="color:#aaa;font-size:0.75rem;transition:transform 0.2s;"></i>
                                </div>

                                <!-- Addons inside this group -->
                                <div class="ag-addons" id="ag_<?php echo $gi; ?>" style="display:none; background:#fff; padding:8px 14px 12px 36px;">
                                    <?php if (!empty($group->addons)): ?>
                                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:7px;margin-top:6px;">
                                            <?php foreach ($group->addons as $addon): ?>
                                            <label style="display:flex;align-items:center;gap:8px;padding:7px 10px;
                                                          background:#fafafa;border:1px solid #eee;border-radius:7px;
                                                          cursor:pointer;font-size:0.84rem;transition:background 0.15s;"
                                                   onmouseover="this.style.background='#f3e5f5'"
                                                   onmouseout="this.style.background='#fafafa'">
                                                <input type="checkbox"
                                                       name="addon_ids[]"
                                                       value="<?php echo $addon->id; ?>"
                                                       style="width:14px;height:14px;accent-color:#9b59b6;flex-shrink:0;">
                                                <span>
                                                    <strong style="color:#2c3e50;"><?php echo htmlspecialchars($addon->name); ?></strong>
                                                    <span style="display:block;font-size:0.72rem;color:#aaa;">
                                                        <?php echo ($addon->price > 0) ? '+€'.number_format($addon->price,2) : 'Free'; ?>
                                                        &bull; <?php echo ucfirst($addon->type ?? 'extra'); ?>
                                                    </span>
                                                </span>
                                            </label>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p style="color:#ccc;font-size:0.82rem;margin:8px 0 0;font-style:italic;">
                                            No addons linked to this group yet.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="padding:20px;text-align:center;color:#ccc;">
                                <i class="fas fa-layer-group" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                <p style="margin:0;font-size:0.85rem;">No addon groups yet.
                                    <a href="<?php echo base_url('admin/addon_groups'); ?>">Create groups →</a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <small style="color:#999;margin-top:6px;display:block;">
                        <i class="fas fa-info-circle"></i>
                        Check a <strong>group</strong> to link it to the product. Check individual <strong>addons</strong> to attach them directly.
                    </small>
                </div>

                <!-- Step 5: Base Price (optional) -->
                <div class="form-group">
                    <label><strong>5. Base Price (€)</strong> <span style="color: var(--gray); font-weight: 400;">(Optional)</span></label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g., 10.00">
                </div>

                <!-- Product Name -->
                <div class="form-group">
                    <label><strong>Product Name</strong></label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label><strong>Description</strong></label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Enter product description"></textarea>
                </div>

                <!-- Product Image -->
                <div class="form-group">
                    <label><strong>Product Image</strong></label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Save Product</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function loadSubCategoriesAndSizes(categoryId) {
    const subSelect = document.getElementById('subCategorySelect');
    const container = document.getElementById('sizeContainer');
    
    // Reset sub-categories
    subSelect.innerHTML = '<option value="">Select Sub Category</option>';
    subSelect.disabled = true;
    
    // Reset sizes
    container.innerHTML = '';
    
    if (!categoryId) {
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
                    subSelect.appendChild(opt);
                });
                subSelect.disabled = false;
            } else {
                subSelect.disabled = true;
            }
        })
        .catch(error => console.error('Error loading subcategories:', error));

    // Load Sizes
    container.innerHTML = '<p style="font-size:0.8rem; color:var(--gray);">Loading sizes...</p>';
    
    fetch('<?php echo base_url('admin/get_sizes_by_category/'); ?>' + categoryId)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let html = '<label style="display:block; margin-bottom:10px; font-weight:600;"><strong>3. Size & Pricing</strong></label>';
                html += '<div style="background:#f9f9f9; padding:15px; border-radius:10px; display:grid; gap:10px;">';
                data.forEach(size => {
                    html += `
                        <div style="display:grid; grid-template-columns: 100px 1fr; align-items:center; gap:10px;">
                            <label style="display:flex; align-items:center; gap:8px; margin:0; font-size:0.9rem;">
                                <input type="checkbox" name="size_ids[]" value="${size.id}" checked> ${size.name}
                            </label>
                            <input type="number" step="0.01" name="size_prices[]" class="form-control" style="margin:0; padding:5px 10px;" placeholder="Price for ${size.name}">
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error loading sizes:', error);
            container.innerHTML = '<p style="font-size:0.8rem; color:var(--danger);">Error loading sizes</p>';
        });
}

// Accordion toggle for addon groups
function toggleGroup(index) {
    const panel  = document.getElementById('ag_' + index);
    const arrow  = document.getElementById('arrow_' + index);
    const isOpen = panel.style.display === 'block';
    panel.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}
</script>
