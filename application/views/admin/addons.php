<style>
    /* ---- Addons Page ---- */
    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .addon-row { animation: rowIn 0.3s ease both; }
    .addon-row:nth-child(1)  { animation-delay: .03s; }
    .addon-row:nth-child(2)  { animation-delay: .06s; }
    .addon-row:nth-child(3)  { animation-delay: .09s; }
    .addon-row:nth-child(4)  { animation-delay: .12s; }
    .addon-row:nth-child(5)  { animation-delay: .15s; }

    .page-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 30px rgba(0,0,0,0.07);
        overflow: hidden;
        margin-bottom: 30px;
    }
    .page-card-header {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        padding: 22px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-card-header h3 {
        color: #fff;
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: 0.3px;
    }
    .btn-add {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.4);
        padding: 8px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.25s;
        text-decoration: none;
    }
    .btn-add:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Type badges */
    .type-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .type-extra     { background: #e8f4fd; color: #2980b9; }
    .type-protein   { background: #fef9e7; color: #d35400; }
    .type-sauce     { background: #fdedec; color: #c0392b; }
    .type-cheese    { background: #fef5e7; color: #e67e22; }
    .type-vegetable { background: #eafaf1; color: #1e8449; }

    .price-pill {
        display: inline-block;
        background: #f0fdf4;
        color: #16a34a;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 3px 10px;
        border-radius: 6px;
    }
    .price-free {
        background: #f8f9fa;
        color: #aaa;
        font-weight: 500;
    }

    .addon-table th {
        background: #fafafa;
        padding: 13px 18px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #999;
        border-bottom: 1px solid #f0f0f0;
    }
    .addon-table td {
        padding: 14px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f7f7f7;
        font-size: 0.9rem;
        color: #444;
    }
    .addon-table tr:last-child td { border-bottom: none; }

    .btn-act {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 13px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-act-edit   { background: #fff3e0; color: #e67e22; }
    .btn-act-edit:hover   { background: #e67e22; color: #fff; }
    .btn-act-delete { background: #fef0f0; color: #e74c3c; }
    .btn-act-delete:hover { background: #e74c3c; color: #fff; }

    /* Bootstrap modal overrides */
    .modal-header-purple {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        color: #fff;
        border-radius: 12px 12px 0 0;
        padding: 20px 24px;
    }
    .modal-header-purple .btn-close { filter: invert(1) brightness(2); }
    .modal-header-purple .modal-title { font-weight: 700; color: #fff; font-size: 1rem; }
    .modal .modal-content { border: none; border-radius: 14px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
    .modal-body { padding: 24px; }
    .f-label { font-weight: 600; font-size: 0.85rem; color: #555; margin-bottom: 6px; display: block; }
    .f-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e5e5e5;
        border-radius: 9px;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        box-sizing: border-box;
    }
    .f-input:focus { border-color: #9b59b6; box-shadow: 0 0 0 3px rgba(155,89,182,0.12); }
    .f-hint { font-size: 0.75rem; color: #aaa; margin-top: 4px; }
    .btn-submit-purple {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
        font-family: 'Poppins', sans-serif;
        margin-top: 8px;
    }
    .btn-submit-purple:hover { opacity: 0.9; transform: translateY(-2px); }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #bbb;
    }
    .empty-state i { font-size: 3.5rem; display: block; margin-bottom: 14px; opacity: 0.3; }
    .empty-state p { font-size: 0.95rem; margin: 0; }

    .bulk-bar {
        padding: 16px 24px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fafafa;
    }
    .btn-bulk-delete {
        background: #fef0f0;
        color: #e74c3c;
        border: 1.5px solid #fcc;
        padding: 7px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
    }
    .btn-bulk-delete:hover { background: #e74c3c; color: #fff; border-color: #e74c3c; }
    #bulkInfo { font-size: 0.82rem; color: #999; }
</style>

<!-- Page Card -->
<div class="page-card">
    <div class="page-card-header">
        <h3><i class="fas fa-plus-circle" style="margin-right:8px;opacity:0.85;"></i>Manage Addons</h3>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addAddonModal">
            <i class="fas fa-plus"></i> Add New Addon
        </button>
    </div>

    <div style="overflow-x:auto;">
        <table class="addon-table" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:40px;"><input type="checkbox" id="selectAllAddons" style="width:16px;height:16px;cursor:pointer;"></th>
                    <th>ID</th>
                    <th>Addon Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($addons)): ?>
                    <?php foreach ($addons as $addon): ?>
                    <tr class="addon-row">
                        <td><input type="checkbox" class="addon-checkbox" value="<?php echo $addon->id; ?>" style="width:16px;height:16px;cursor:pointer;"></td>
                        <td style="color:#bbb; font-size:0.82rem;">#<?php echo $addon->id; ?></td>
                        <td><strong style="color:#2c3e50;"><?php echo htmlspecialchars($addon->name); ?></strong></td>
                        <td>
                            <?php if ($addon->price > 0): ?>
                                <span class="price-pill">€<?php echo number_format($addon->price, 2); ?></span>
                            <?php else: ?>
                                <span class="price-pill price-free">Free</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $type = strtolower($addon->type ?? 'extra');
                            $typeClass = 'type-' . (in_array($type, ['extra','protein','sauce','cheese','vegetable']) ? $type : 'extra');
                            ?>
                            <span class="type-badge <?php echo $typeClass; ?>"><?php echo ucfirst($type); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_addon/'.$addon->id); ?>" class="btn-act btn-act-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="javascript:void(0)"
                               onclick="showConfirm('<?php echo base_url('admin/delete_addon/'.$addon->id); ?>', 'Delete addon &ldquo;<?php echo addslashes($addon->name); ?>&rdquo;? This cannot be undone.')"
                               class="btn-act btn-act-delete" style="margin-left:6px;">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-plus-circle"></i>
                                <p>No addons yet — click <strong>Add New Addon</strong> to get started.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bulk delete bar -->
    <?php if (!empty($addons)): ?>
    <div class="bulk-bar">
        <form method="post" action="<?php echo base_url('admin/delete_multiple_addons'); ?>" id="bulkDeleteForm">
            <input type="hidden" id="selectedAddonsInput" name="addon_ids[]" value="">
            <button type="submit" class="btn-bulk-delete" onclick="return confirmBulk()">
                <i class="fas fa-trash-alt"></i> Delete Selected
            </button>
        </form>
        <span id="bulkInfo">Select rows above to bulk delete</span>
    </div>
    <?php endif; ?>
</div>

<!-- ===================== ADD ADDON MODAL ===================== -->
<div class="modal fade" id="addAddonModal" tabindex="-1" aria-labelledby="addAddonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-purple">
                <span class="modal-title" id="addAddonModalLabel">
                    <i class="fas fa-plus-circle" style="margin-right:7px;"></i>Add New Addon
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo base_url('admin/add_addon'); ?>">
                <div class="modal-body">
                    <div style="margin-bottom:18px;">
                        <label class="f-label">Addon Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="f-input" name="name" placeholder="e.g. Extra Cheese" required>
                    </div>
                    <div style="margin-bottom:18px;">
                        <label class="f-label">Price (€) <span style="color:#aaa;font-weight:400;">— optional</span></label>
                        <input type="number" class="f-input" name="price" step="0.01" min="0" placeholder="0.00">
                        <p class="f-hint">Leave blank or 0 for a free addon.</p>
                    </div>
                    <div style="margin-bottom:18px;">
                        <label class="f-label">Type <span style="color:#e74c3c;">*</span></label>
                        <select class="f-input" name="type" required>
                            <option value="extra">Extra / Topping</option>
                            <option value="protein">Protein</option>
                            <option value="sauce">Sauce</option>
                            <option value="cheese">Cheese</option>
                            <option value="vegetable">Vegetable</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit-purple">
                        <i class="fas fa-plus" style="margin-right:6px;"></i>Create Addon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Select all
document.getElementById('selectAllAddons').addEventListener('change', function() {
    document.querySelectorAll('.addon-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
    updateBulkInfo();
});

document.querySelectorAll('.addon-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkInfo);
});

function updateBulkInfo() {
    const selected = document.querySelectorAll('.addon-checkbox:checked');
    const info = document.getElementById('bulkInfo');
    if (info) info.textContent = selected.length > 0
        ? selected.length + ' addon(s) selected'
        : 'Select rows above to bulk delete';
    const input = document.getElementById('selectedAddonsInput');
    if (input) input.value = Array.from(selected).map(cb => cb.value).join(',');
}

function confirmBulk() {
    const selected = document.querySelectorAll('.addon-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select at least one addon to delete.');
        return false;
    }
    return confirm('Delete ' + selected.length + ' selected addon(s)? This cannot be undone.');
}
</script>
