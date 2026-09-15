<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid" style="padding: 15px 0;">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #842029; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Header info banner -->
    <div style="background: #ffffff; border-radius: 14px; padding: 22px 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
                <i class="fas fa-credit-card" style="color: var(--primary); margin-right: 10px;"></i>
                Payment Methods Management
            </h2>
            <p style="color: #64748b; margin: 0; font-size: 0.92rem;">
                Control which payment methods are available to customers on the checkout page. Toggle ON or OFF anytime.
            </p>
        </div>
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 8px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 600;">
            <i class="fas fa-info-circle"></i> Live Checkout Sync
        </div>
    </div>

    <!-- Payment Methods Form -->
    <form action="<?php echo base_url('admin/payment_settings'); ?>" method="POST" id="paymentSettingsForm">
        <div style="display: flex; flex-direction: column; gap: 22px;">
            <?php foreach ($payment_methods as $pm): ?>
                <?php 
                    $isEnabled = (bool)$pm->is_enabled;
                    $badgeBg = $isEnabled ? '#ecfdf5' : '#fef2f2';
                    $badgeColor = $isEnabled ? '#047857' : '#b91c1c';
                    $badgeText = $isEnabled ? 'ACTIVE / ON' : 'DISABLED / OFF';
                    $cardBorder = $isEnabled ? '#cbd5e1' : '#e2e8f0';
                ?>
                <div class="card payment-method-card" id="card-method-<?php echo $pm->id; ?>" style="background: #ffffff; border-radius: 14px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid <?php echo $cardBorder; ?>; transition: all 0.25s ease;">
                    
                    <!-- Card Top Header -->
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: <?php echo ($pm->payment_key === 'cash') ? '#dcfce7' : '#dbeafe'; ?>; color: <?php echo ($pm->payment_key === 'cash') ? '#15803d' : '#1d4ed8'; ?>; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                                <i class="<?php echo htmlspecialchars($pm->icon ?: 'fas fa-credit-card'); ?>"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">
                                    <?php echo htmlspecialchars($pm->name_en); ?>
                                </h3>
                                <span style="font-size: 0.8rem; color: #64748b; font-family: monospace; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; margin-top: 4px; display: inline-block;">
                                    key: <?php echo htmlspecialchars($pm->payment_key); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Status Badge & Switch -->
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <span id="badge-<?php echo $pm->id; ?>" style="background: <?php echo $badgeBg; ?>; color: <?php echo $badgeColor; ?>; font-size: 0.78rem; font-weight: 700; padding: 5px 14px; border-radius: 20px; letter-spacing: 0.5px;">
                                <?php echo $badgeText; ?>
                            </span>

                            <label class="custom-switch" style="position: relative; display: inline-block; width: 54px; height: 28px; margin: 0;">
                                <input type="checkbox" name="methods[<?php echo $pm->id; ?>][is_enabled]" value="1" <?php echo $isEnabled ? 'checked' : ''; ?> id="switch-<?php echo $pm->id; ?>" onchange="ajaxTogglePayment(<?php echo $pm->id; ?>, this)" style="opacity: 0; width: 0; height: 0;">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Fields Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                        <div>
                            <label style="font-weight: 600; font-size: 0.88rem; color: #334155; display: block; margin-bottom: 6px;">
                                Display Title (French) <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="methods[<?php echo $pm->id; ?>][name_fr]" value="<?php echo htmlspecialchars($pm->name_fr); ?>" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="font-weight: 600; font-size: 0.88rem; color: #334155; display: block; margin-bottom: 6px;">
                                Display Title (English) <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="methods[<?php echo $pm->id; ?>][name_en]" value="<?php echo htmlspecialchars($pm->name_en); ?>" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="font-weight: 600; font-size: 0.88rem; color: #334155; display: block; margin-bottom: 6px;">
                                Description / Help Note (French)
                            </label>
                            <input type="text" name="methods[<?php echo $pm->id; ?>][description_fr]" value="<?php echo htmlspecialchars($pm->description_fr ?? ''); ?>" placeholder="Instructions pour le client" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="font-weight: 600; font-size: 0.88rem; color: #334155; display: block; margin-bottom: 6px;">
                                Description / Help Note (English)
                            </label>
                            <input type="text" name="methods[<?php echo $pm->id; ?>][description_en]" value="<?php echo htmlspecialchars($pm->description_en ?? ''); ?>" placeholder="Instructions for customer" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 25px; display: flex; align-items: center; justify-content: flex-end; gap: 15px;">
            <button type="submit" style="background: var(--primary); color: #ffffff; border: none; padding: 12px 28px; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.2s;">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Save All Labels & Settings
            </button>
        </div>
    </form>
</div>

<!-- Toast notification box -->
<div id="paymentToast" style="position: fixed; bottom: 30px; right: 30px; background: #0f172a; color: #ffffff; padding: 14px 22px; border-radius: 10px; font-size: 0.92rem; font-weight: 500; display: none; z-index: 9999; box-shadow: 0 10px 30px rgba(0,0,0,0.25); border-left: 4px solid var(--primary);">
    <span id="paymentToastMsg"></span>
</div>

<style>
/* Custom Switch Slider */
.custom-switch .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 34px;
}

.custom-switch .slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.custom-switch input:checked + .slider {
    background-color: #10b981;
}

.custom-switch input:checked + .slider:before {
    transform: translateX(26px);
}
</style>

<script>
function showToast(message) {
    var toast = document.getElementById('paymentToast');
    var msg = document.getElementById('paymentToastMsg');
    msg.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> ' + message;
    toast.style.display = 'block';
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(function() {
        toast.style.display = 'none';
    }, 3500);
}

function ajaxTogglePayment(id, checkbox) {
    var isChecked = checkbox.checked;
    var badge = document.getElementById('badge-' + id);
    
    // Immediate UI feedback
    if (isChecked) {
        badge.style.background = '#ecfdf5';
        badge.style.color = '#047857';
        badge.innerText = 'ACTIVE / ON';
    } else {
        badge.style.background = '#fef2f2';
        badge.style.color = '#b91c1c';
        badge.innerText = 'DISABLED / OFF';
    }

    fetch('<?php echo base_url("admin/toggle_payment_method/"); ?>' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.status === 'success') {
            showToast(data.message);
        } else {
            alert(data.message || 'Error updating payment method');
            // Revert checkbox state
            checkbox.checked = !isChecked;
        }
    })
    .catch(function(err) {
        console.error('Error toggling payment method:', err);
        checkbox.checked = !isChecked;
    });
}
</script>
