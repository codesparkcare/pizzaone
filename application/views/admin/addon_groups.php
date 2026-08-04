<style>
    /* ---- Addon Groups Page ---- */
    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .page-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 30px rgba(0,0,0,0.07);
        overflow: hidden;
        margin-bottom: 30px;
    }
    .page-card-header {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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
    }
    .btn-add-white {
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
    }
    .btn-add-white:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    .grp-table th {
        background: #fafafa;
        padding: 13px 18px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #999;
        border-bottom: 1px solid #f0f0f0;
    }
    .grp-table td {
        padding: 16px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f7f7f7;
        font-size: 0.9rem;
        color: #444;
    }
    .grp-table tr:last-child td { border-bottom: none; }
    .grp-table tr:hover td { background: #fffdf5; }
    .grp-row { animation: rowIn 0.3s ease both; }

    /* Condition badge */
    .cond-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .cond-1   { background: #fdebd0; color: #d35400; }
    .cond-2   { background: #d5f5e3; color: #1e8449; }
    .cond-3   { background: #d6eaf8; color: #1a5276; }
    .cond-any { background: #f0f0f0; color: #777; }

    /* Linked addon tags */
    .addon-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .addon-tag {
        background: #f8f0ff;
        color: #8e44ad;
        border: 1px solid #dcc6f0;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 600;
    }
    .addon-tag-price { font-weight: 400; opacity: 0.7; margin-left: 3px; }
    .no-addons { font-size: 0.82rem; color: #ccc; font-style: italic; }

    /* Action buttons */
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
    .btn-act-edit:hover   { background: #e67e22; color: #fff; text-decoration: none; }
    .btn-act-delete { background: #fef0f0; color: #e74c3c; }
    .btn-act-delete:hover { background: #e74c3c; color: #fff; }

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

    /* Modal */
    .modal-header-orange {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: #fff;
        border-radius: 12px 12px 0 0;
        padding: 20px 24px;
    }
    .modal-header-orange .btn-close { filter: invert(1) brightness(2); }
    .modal-header-orange .modal-title { font-weight: 700; color: #fff; font-size: 1rem; }
    .modal .modal-content { border: none; border-radius: 14px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
    .modal-body-pad { padding: 24px; }
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
    .f-input:focus { border-color: #f39c12; box-shadow: 0 0 0 3px rgba(243,156,18,0.12); }

    .addons-checklist {
        background: #fefefe;
        border: 1.5px solid #eee;
        border-radius: 10px;
        padding: 14px;
        max-height: 260px;
        overflow-y: auto;
    }
    .addons-checklist::-webkit-scrollbar { width: 5px; }
    .addons-checklist::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

    .addon-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s;
        margin-bottom: 4px;
        border: 1.5px solid transparent;
    }
    .addon-check-item:hover { background: #fff8e1; border-color: #fce4a0; }
    .addon-check-item input[type="checkbox"] { width: 16px; height: 16px; accent-color: #f39c12; flex-shrink: 0; }
    .addon-check-item .ac-name { font-weight: 600; font-size: 0.88rem; color: #2c3e50; }
    .addon-check-item .ac-meta { font-size: 0.76rem; color: #aaa; margin-top: 1px; }

    .btn-submit-orange {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
        font-family: 'Poppins', sans-serif;
        margin-top: 10px;
    }
    .btn-submit-orange:hover { opacity: 0.9; transform: translateY(-2px); }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #bbb;
    }
    .empty-state i { font-size: 3.5rem; display: block; margin-bottom: 14px; opacity: 0.3; }
</style>

<!-- Page Card -->
<div class="page-card">
    <div class="page-card-header">
        <h3><i class="fas fa-layer-group" style="margin-right:9px; opacity:0.85;"></i>Manage Addon Groups</h3>
        <button class="btn-add-white" data-bs-toggle="modal" data-bs-target="#addAddonGroupModal">
            <i class="fas fa-plus"></i> Add Addon Group
        </button>
    </div>

    <div style="overflow-x:auto;">
        <table class="grp-table" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:40px;"><input type="checkbox" id="selectAllGroups" style="width:16px;height:16px;cursor:pointer;" onclick="toggleAllGroups(this)"></th>
                    <th>ID</th>
                    <th>Group Title</th>
                    <th>Selection Rule</th>
                    <th>Addons in Group</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($groups)): ?>
                    <?php foreach ($groups as $i => $group): ?>
                    <tr class="grp-row" style="animation-delay:<?php echo $i * 0.05; ?>s;">
                        <td><input type="checkbox" class="group-checkbox" value="<?php echo $group->id; ?>" style="width:16px;height:16px;cursor:pointer;"></td>
                        <td style="color:#bbb; font-size:0.82rem;">#<?php echo $group->id; ?></td>
                        <td><strong style="color:#2c3e50;"><?php echo htmlspecialchars($group->name); ?></strong></td>
                        <td>
                            <?php 
                            if ($group->min_selections == 1 && $group->max_selections == 1) {
                                echo '<span class="cond-badge cond-1"><i class="fas fa-hand-pointer"></i> Choose 1</span>';
                            } elseif ($group->min_selections == 2 && $group->max_selections == 2) {
                                echo '<span class="cond-badge cond-2"><i class="fas fa-check-double"></i> Choose 2</span>';
                            } elseif ($group->min_selections == 3 && $group->max_selections == 3) {
                                echo '<span class="cond-badge cond-3"><i class="fas fa-list-check"></i> Choose 3</span>';
                            } elseif ($group->min_selections == 4 && $group->max_selections == 4) {
                                echo '<span class="cond-badge" style="background:#fce4ec;color:#c62828;"><i class="fas fa-th"></i> Choose 4</span>';
                            } elseif ($group->min_selections == 5 && $group->max_selections == 5) {
                                echo '<span class="cond-badge" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-grip-horizontal"></i> Choose 5</span>';
                            } else {
                                echo '<span class="cond-badge cond-any"><i class="fas fa-infinity"></i> Choose Any</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($group->addons)): ?>
                                <div class="addon-tags">
                                    <?php foreach ($group->addons as $addon): ?>
                                        <span class="addon-tag">
                                            <?php echo htmlspecialchars($addon->name); ?>
                                            <?php if ($addon->price > 0): ?>
                                                <span class="addon-tag-price">+€<?php echo number_format($addon->price, 2); ?></span>
                                            <?php else: ?>
                                                <span class="addon-tag-price">free</span>
                                            <?php endif; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="no-addons">No addons linked yet</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_addon_group/'.$group->id); ?>" class="btn-act btn-act-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="javascript:void(0)"
                               onclick="showConfirm('<?php echo base_url('admin/delete_addon_group/'.$group->id); ?>', 'Delete group &ldquo;<?php echo addslashes($group->name); ?>&rdquo;? All linked addons will be unlinked.')"
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
                                <i class="fas fa-layer-group"></i>
                                <p>No addon groups yet — click <strong>Add Addon Group</strong> to get started.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bulk delete bar -->
    <?php if (!empty($groups)): ?>
    <div class="bulk-bar">
        <form method="post" action="<?php echo base_url('admin/delete_multiple_addon_groups'); ?>" id="bulkGroupForm">
            <input type="hidden" id="selectedGroupsInput" name="group_ids[]" value="">
            <button type="submit" class="btn-bulk-delete" onclick="return confirmBulkGroups()">
                <i class="fas fa-trash-alt"></i> Delete Selected
            </button>
        </form>
        <span id="bulkGroupInfo" style="font-size:0.82rem; color:#999;">Select rows above to bulk delete</span>
    </div>
    <?php endif; ?>
</div>

<!-- ===================== ADD ADDON GROUP MODAL ===================== -->
<div class="modal fade" id="addAddonGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:580px;">
        <div class="modal-content">
            <div class="modal-header-orange">
                <span class="modal-title">
                    <i class="fas fa-layer-group" style="margin-right:7px;"></i>Add New Addon Group
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo base_url('admin/add_addon_group'); ?>">
                <div class="modal-body-pad">

                    <div style="margin-bottom:18px;">
                        <label class="f-label">Group Title <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="f-input" name="name" placeholder="e.g. Choose Your Sauce" required>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label class="f-label">Selection Rule <span style="color:#e74c3c;">*</span></label>
                        <select class="f-input" name="selection_condition" required>
                            <option value="choose_1">Choose 1 — must pick exactly 1</option>
                            <option value="choose_2">Choose 2 — must pick exactly 2</option>
                            <option value="choose_3">Choose 3 — must pick exactly 3</option>
                            <option value="choose_4">Choose 4 — must pick exactly 4</option>
                            <option value="choose_5">Choose 5 — must pick exactly 5</option>
                            <option value="choose_any" selected>Choose Any — optional, pick as many as you like</option>
                        </select>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label class="f-label">Link Addons to this Group</label>
                        <?php if (!empty($addons)): ?>
                        <div class="addons-checklist">
                            <?php foreach ($addons as $addon): ?>
                            <label class="addon-check-item">
                                <input type="checkbox" name="addon_ids[]" value="<?php echo $addon->id; ?>">
                                <div>
                                    <div class="ac-name"><?php echo htmlspecialchars($addon->name); ?></div>
                                    <div class="ac-meta">
                                        <?php echo ($addon->price > 0) ? '€'.number_format($addon->price,2) : 'Free'; ?>
                                        &bull; <?php echo ucfirst($addon->type); ?>
                                    </div>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <p style="color:#aaa; font-size:0.85rem; margin:0;">
                                No addons found. <a href="<?php echo base_url('admin/addons'); ?>">Create addons first →</a>
                            </p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit-orange">
                        <i class="fas fa-layer-group" style="margin-right:7px;"></i>Create Addon Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleAllGroups(src) {
    document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = src.checked);
    updateGroupBulk();
}
document.querySelectorAll('.group-checkbox').forEach(cb => {
    cb.addEventListener('change', updateGroupBulk);
});
function updateGroupBulk() {
    const sel = document.querySelectorAll('.group-checkbox:checked');
    const info = document.getElementById('bulkGroupInfo');
    if (info) info.textContent = sel.length > 0
        ? sel.length + ' group(s) selected'
        : 'Select rows above to bulk delete';
    const inp = document.getElementById('selectedGroupsInput');
    if (inp) inp.value = Array.from(sel).map(cb => cb.value).join(',');
}
function confirmBulkGroups() {
    const sel = document.querySelectorAll('.group-checkbox:checked');
    if (sel.length === 0) { alert('Please select at least one group to delete.'); return false; }
    return confirm('Delete ' + sel.length + ' selected group(s)? This cannot be undone.');
}
</script>
