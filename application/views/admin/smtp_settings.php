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

    <div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 25px; align-items: start;">
        
        <!-- SMTP Settings Form Card -->
        <div class="card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--secondary);">
                    <i class="fas fa-envelope-open-text" style="color: var(--primary); margin-right: 8px;"></i>
                    Mail / SMTP Server Configuration
                </h3>
            </div>

            <form action="<?php echo base_url('admin/smtp_settings'); ?>" method="POST">
                
                <div style="margin-bottom: 18px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #1e293b;">
                        <input type="checkbox" name="is_active" value="1" <?php echo ($smtp->is_active ?? 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                        Enable SMTP Email Service
                    </label>
                    <small style="color: #64748b; margin-left: 28px; display: block; margin-top: 2px;">When enabled, emails (order confirmations, password resets) will be routed through your SMTP server.</small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">SMTP Host <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="smtp_host" value="<?php echo htmlspecialchars($smtp->smtp_host ?? ''); ?>" placeholder="e.g. smtp.gmail.com" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">SMTP Port <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="smtp_port" value="<?php echo htmlspecialchars($smtp->smtp_port ?? 587); ?>" placeholder="e.g. 587 or 465" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">Encryption Protocol <span style="color: #ef4444;">*</span></label>
                    <select name="smtp_crypto" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; background: #ffffff; box-sizing: border-box;">
                        <option value="tls" <?php echo ($smtp->smtp_crypto ?? '') === 'tls' ? 'selected' : ''; ?>>TLS (Recommended for Port 587)</option>
                        <option value="ssl" <?php echo ($smtp->smtp_crypto ?? '') === 'ssl' ? 'selected' : ''; ?>>SSL (Recommended for Port 465)</option>
                        <option value="none" <?php echo ($smtp->smtp_crypto ?? '') === 'none' ? 'selected' : ''; ?>>None (Port 25)</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">SMTP Username / Email <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="smtp_user" value="<?php echo htmlspecialchars($smtp->smtp_user ?? ''); ?>" placeholder="user@domain.com" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">SMTP Password <span style="color: #ef4444;">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="smtp_pass" id="smtp_pass" value="<?php echo htmlspecialchars($smtp->smtp_pass ?? ''); ?>" placeholder="App Password / Account Password" required style="width: 100%; padding: 10px 40px 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                            <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b;">
                                <i class="fas fa-eye" id="togglePassIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 22px;">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">Sender Email (From Email) <span style="color: #ef4444;">*</span></label>
                        <input type="email" name="from_email" value="<?php echo htmlspecialchars($smtp->from_email ?? ''); ?>" placeholder="noreply@pizzaone.fr" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">Sender Name (From Name) <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="from_name" value="<?php echo htmlspecialchars($smtp->from_name ?? 'Pizza One'); ?>" placeholder="Pizza One" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>
                </div>

                <div style="text-align: right; border-top: 1px solid #f1f5f9; padding-top: 18px;">
                    <button type="submit" class="btn-primary" style="padding: 11px 24px; background: var(--primary); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);">
                        <i class="fas fa-save"></i> Save SMTP Configuration
                    </button>
                </div>

            </form>
        </div>

        <!-- Test SMTP Card -->
        <div class="card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 1.2rem; font-weight: 600; color: var(--secondary);">
                    <i class="fas fa-paper-plane" style="color: #3b82f6; margin-right: 8px;"></i>
                    Test SMTP Connection
                </h3>
            </div>

            <p style="color: #64748b; font-size: 0.88rem; line-height: 1.5; margin-bottom: 18px;">
                Send a real test email to verify that your SMTP host credentials and port settings are working correctly.
            </p>

            <form id="testSmtpForm" onsubmit="sendTestEmail(event)">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">Recipient Email Address</label>
                    <input type="email" id="test_email" placeholder="your-email@example.com" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                </div>

                <button type="submit" id="btnTestSmtp" style="width: 100%; padding: 11px; background: #3b82f6; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: background 0.2s ease;">
                    <i class="fas fa-paper-plane"></i> Send Test Email
                </button>
            </form>

            <div id="testResultBox" style="display: none; margin-top: 18px; padding: 14px; border-radius: 8px; font-size: 0.88rem; line-height: 1.4; word-break: break-word;"></div>
        </div>

    </div>
</div>

<script>
    function togglePasswordVisibility() {
        var input = document.getElementById('smtp_pass');
        var icon = document.getElementById('togglePassIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function sendTestEmail(e) {
        e.preventDefault();
        var email = document.getElementById('test_email').value;
        var btn = document.getElementById('btnTestSmtp');
        var box = document.getElementById('testResultBox');

        if (!email) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing Connection...';
        box.style.display = 'none';

        var formData = new FormData();
        formData.append('test_email', email);

        fetch('<?php echo base_url("admin/test_smtp"); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Test Email';
            box.style.display = 'block';

            if (data.status === 'success') {
                box.style.background = '#d1e7dd';
                box.style.color = '#0f5132';
                box.style.border = '1px solid #badbcc';
                box.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
            } else {
                box.style.background = '#f8d7da';
                box.style.color = '#842029';
                box.style.border = '1px solid #f5c2c7';
                box.innerHTML = '<i class="fas fa-times-circle"></i> <strong>Error:</strong> ' + data.message;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Test Email';
            box.style.display = 'block';
            box.style.background = '#f8d7da';
            box.style.color = '#842029';
            box.style.border = '1px solid #f5c2c7';
            box.innerHTML = '<i class="fas fa-times-circle"></i> Connection test failed or server error.';
        });
    }
</script>
