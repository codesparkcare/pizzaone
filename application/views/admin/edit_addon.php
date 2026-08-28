<style>
.edit-wrap {
    max-width: 580px;
    margin: 0 auto;
}
.edit-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.09);
    overflow: hidden;
}
.edit-card-header {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    padding: 22px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.edit-card-header h3 { color:#fff; margin:0; font-weight:700; font-size:1.1rem; }
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    background: rgba(255,255,255,0.18);
    border: 1.5px solid rgba(255,255,255,0.35);
    color: #fff;
    border-radius: 9px;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-back:hover { background: rgba(255,255,255,0.32); color:#fff; text-decoration:none; }
.edit-body { padding: 30px; }
.f-label { font-weight:600; font-size:0.85rem; color:#555; margin-bottom:7px; display:block; }
.f-hint  { font-size:0.74rem; color:#aaa; margin-top:4px; }
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
.btn-save {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.2s;
    font-family: 'Poppins', sans-serif;
    margin-top: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-save:hover { opacity: 0.9; transform: translateY(-2px); }
.divider { border: none; border-top: 1px solid #f0f0f0; margin: 24px 0; }
</style>

<div class="edit-wrap">
    <div class="edit-card">
        <div class="edit-card-header">
            <h3><i class="fas fa-edit" style="margin-right:8px; opacity:0.8;"></i>Edit Addon</h3>
            <a href="<?php echo base_url('admin/addons'); ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="edit-body">
            <form method="post" action="<?php echo base_url('admin/update_addon/'.$addon->id); ?>">

                <div style="margin-bottom:20px;">
                    <label class="f-label">Addon Name <span style="color:#e74c3c;">*</span></label>
                    <input type="text" class="f-input" name="name"
                           value="<?php echo htmlspecialchars($addon->name); ?>" required
                           placeholder="e.g. Extra Mozzarella">
                </div>

                <div style="margin-bottom:20px;">
                    <label class="f-label">Base Price (€) <span style="color:#aaa; font-weight:400;">— optional fallback</span></label>
                    <input type="number" class="f-input" name="price"
                           step="0.01" min="0"
                           value="<?php echo $addon->price; ?>"
                           placeholder="0.00">
                    <p class="f-hint">Default price if size price is not specified.</p>
                </div>

                <div style="margin-bottom:24px;">
                    <label class="f-label"><i class="fas fa-expand-alt" style="margin-right:4px; opacity:0.7;"></i> Size-Wise Prices (€) <span style="color:#aaa;font-weight:400;">— optional</span></label>
                    <?php if (!empty($sizes)): ?>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; background:#f9f9f9; padding:14px; border-radius:10px; border:1px solid #eee;">
                            <?php foreach ($sizes as $sz): ?>
                                <?php $val = isset($addon->size_prices[$sz->id]) ? $addon->size_prices[$sz->id] : ''; ?>
                                <div>
                                    <label style="font-size:0.8rem; font-weight:600; color:#555; display:block; margin-bottom:4px;"><?php echo htmlspecialchars($sz->name); ?></label>
                                    <input type="number" class="f-input" name="size_prices[<?php echo $sz->id; ?>]"
                                           step="0.01" min="0" value="<?php echo ($val !== '') ? $val : ''; ?>"
                                           placeholder="0.00" style="padding:8px 11px; font-size:0.88rem;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="f-hint">Specify prices for specific sizes (e.g. Senior: 0.80, Mega: 0.90).</p>
                    <?php else: ?>
                        <p class="f-hint">No sizes configured in system.</p>
                    <?php endif; ?>
                </div>

                <div style="margin-bottom:28px;">
                    <label class="f-label">Type <span style="color:#e74c3c;">*</span></label>
                    <select class="f-input" name="type" required>
                        <option value="extra"     <?php echo ($addon->type=='extra')     ? 'selected' : ''; ?>>Extra / Topping</option>
                        <option value="protein"   <?php echo ($addon->type=='protein')   ? 'selected' : ''; ?>>Protein</option>
                        <option value="sauce"     <?php echo ($addon->type=='sauce')     ? 'selected' : ''; ?>>Sauce</option>
                        <option value="cheese"    <?php echo ($addon->type=='cheese')    ? 'selected' : ''; ?>>Cheese</option>
                        <option value="vegetable" <?php echo ($addon->type=='vegetable') ? 'selected' : ''; ?>>Vegetable</option>
                    </select>
                </div>

                <hr class="divider">

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
