<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid" style="padding: 15px 0;">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; border: 1px solid #badbcc; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
            <span><?php echo $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #842029; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; border: 1px solid #f5c2c7; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 1.2rem;"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1.6fr 1.1fr; gap: 25px; align-items: start;">
        
        <!-- Left Column: Schema Status & Settings -->
        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            <!-- 1. Database Schema Card -->
            <div class="card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--secondary);">
                            <i class="fas fa-database" style="color: var(--primary, #e21b1b); margin-right: 8px;"></i>
                            Database Tables &amp; Schema Status
                        </h3>
                        <small style="color: #64748b; display: block; margin-top: 4px;">Automatic database manager for Flutter mobile app integration</small>
                    </div>
                    <a href="<?php echo base_url('admin/run_db_migration'); ?>" class="btn" style="background: var(--primary, #e21b1b); color: #fff; padding: 9px 18px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 3px 10px rgba(226,27,27,0.25);">
                        <i class="fas fa-sync-alt"></i> Run Database Update Now
                    </a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- Table: orders.items_json -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-receipt" style="color: #64748b; font-size: 1.1rem;"></i>
                            <div>
                                <strong style="font-size: 0.95rem; color: #1e293b;">`orders`.`items_json`</strong>
                                <div style="font-size: 0.8rem; color: #64748b;">Stores order item snapshot breakdown for mobile app &amp; thermal receipts</div>
                            </div>
                        </div>
                        <?php if ($migration_report['orders_items_json']): ?>
                            <span class="badge" style="background: #10b981; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-check"></i> Installed
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                Missing
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Table: fcm_device_tokens -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-mobile-alt" style="color: #64748b; font-size: 1.1rem;"></i>
                            <div>
                                <strong style="font-size: 0.95rem; color: #1e293b;">`fcm_device_tokens`</strong>
                                <div style="font-size: 0.8rem; color: #64748b;">Stores active Flutter tablets/phones for instant audio push notifications</div>
                            </div>
                        </div>
                        <?php if ($migration_report['fcm_device_tokens']): ?>
                            <span class="badge" style="background: #10b981; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-check"></i> Installed
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                Missing
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Table: fcm_settings -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-bell" style="color: #64748b; font-size: 1.1rem;"></i>
                            <div>
                                <strong style="font-size: 0.95rem; color: #1e293b;">`fcm_settings`</strong>
                                <div style="font-size: 0.8rem; color: #64748b;">Stores Firebase server key &amp; project configuration</div>
                            </div>
                        </div>
                        <?php if ($migration_report['fcm_settings']): ?>
                            <span class="badge" style="background: #10b981; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-check"></i> Installed
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                Missing
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-top: 15px; padding: 12px 15px; background: #eff6ff; border-radius: 8px; border-left: 4px solid #3b82f6; font-size: 0.85rem; color: #1e40af;">
                    <i class="fas fa-info-circle"></i> <strong>Safe Execution:</strong> Clicking <em>Run Database Update Now</em> applies all SQL commands safely using <code>IF NOT EXISTS</code>. No existing data is ever altered or deleted.
                </div>
            </div>

            <!-- 2. FCM Push Notification Settings Card -->
            <div class="card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px;">
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--secondary);">
                        <i class="fab fa-google" style="color: #ea4335; margin-right: 8px;"></i>
                        Firebase Cloud Messaging (FCM) Settings
                    </h3>
                    <small style="color: #64748b; display: block; margin-top: 4px;">Sends high-priority notifications with custom ringtone to kitchen tablets when new orders arrive</small>
                </div>

                <form action="<?php echo base_url('admin/notification_settings'); ?>" method="POST" enctype="multipart/form-data">
                    
                    <div style="margin-bottom: 20px; background: #f8fafc; padding: 14px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #1e293b; margin: 0;">
                            <input type="checkbox" name="is_active" value="1" <?php echo ($fcm->is_active ?? 1) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary, #e21b1b);">
                            Enable Instant Push Notifications on New Orders
                        </label>
                        <small style="color: #64748b; margin-left: 28px; display: block; margin-top: 4px;">Automatically alerts staff devices immediately after customer checkout</small>
                    </div>

                    <!-- 1. Firebase Service Account JSON (Recommended - Google Modern Standard) -->
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 18px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                            <label style="font-weight: 700; font-size: 0.95rem; color: #166534; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-key"></i> Firebase Service Account Private Key (Recommended)
                            </label>
                            <?php if (!empty($fcm->service_account_json)): ?>
                                <span class="badge" style="background: #15803d; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Service Account Active
                                </span>
                            <?php else: ?>
                                <span class="badge" style="background: #eab308; color: #854d0e; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                    Not Uploaded Yet
                                </span>
                            <?php endif; ?>
                        </div>
                        <p style="font-size: 0.85rem; color: #15803d; margin: 0 0 12px 0; line-height: 1.4;">
                            In Firebase Console &rarr; <strong>Project settings</strong> &rarr; <strong>Service accounts</strong> &rarr; Click <strong>"Generate new private key"</strong> and upload the downloaded <code>.json</code> file below:
                        </p>
                        
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <input type="file" name="service_account_file" accept=".json,application/json" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font-size: 0.85rem;">
                            <button type="button" onclick="document.getElementById('rawJsonWrapper').style.display = document.getElementById('rawJsonWrapper').style.display === 'none' ? 'block' : 'none';" style="background: none; border: none; color: #15803d; text-decoration: underline; font-size: 0.85rem; cursor: pointer; padding: 0;">
                                Or paste JSON text
                            </button>
                        </div>

                        <div id="rawJsonWrapper" style="display: none; margin-top: 12px;">
                            <textarea name="service_account_json" rows="4" placeholder='{"type": "service_account", "project_id": "pizzaone-25548", ...}' style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: monospace; font-size: 0.8rem; box-sizing: border-box;"><?php echo htmlspecialchars($fcm->service_account_json ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- 2. Firebase Project ID -->
                    <div style="margin-bottom: 18px;">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">Firebase Project ID</label>
                        <input type="text" name="project_id" value="<?php echo htmlspecialchars($fcm->project_id ?? 'pizzaone-25548'); ?>" placeholder="pizzaone-25548" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>

                    <!-- 3. Legacy Server Key (Optional Alternative) -->
                    <div style="margin-bottom: 22px;">
                        <label style="font-weight: 600; font-size: 0.9rem; color: #334155; display: block; margin-bottom: 6px;">
                            Legacy Server Key <span style="font-weight: 400; color: #64748b;">(Optional / Fallback)</span>
                        </label>
                        <input type="password" name="server_key" value="<?php echo htmlspecialchars($fcm->server_key ?? ''); ?>" placeholder="AAAA..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box;">
                        <small style="color: #64748b; display: block; margin-top: 4px;">Only required if using Legacy Cloud Messaging API instead of Service Account</small>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px; flex-wrap: wrap; margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 18px;">
                        <a href="<?php echo base_url('admin/test_fcm_notification'); ?>" class="btn" style="background: #3b82f6; color: #fff; padding: 10px 18px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-paper-plane"></i> Send Test Notification
                        </a>

                        <button type="submit" class="btn" style="background: var(--success, #10b981); color: #fff; padding: 10px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-save"></i> Save Notification Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Registered Devices & Setup Guide -->
        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            <!-- Registered Staff Devices Card -->
            <div class="card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                    <h4 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: var(--secondary);">
                        <i class="fas fa-tablet-screen-button" style="color: #3b82f6; margin-right: 6px;"></i>
                        Registered Devices
                    </h4>
                    <span class="badge" style="background: #3b82f6; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">
                        <?php echo $total_devices; ?> Active
                    </span>
                </div>

                <?php if (empty($devices)): ?>
                    <div style="text-align: center; padding: 25px 15px; color: #94a3b8;">
                        <i class="fas fa-mobile-screen" style="font-size: 2.2rem; margin-bottom: 8px; opacity: 0.6;"></i>
                        <p style="margin: 0; font-size: 0.9rem; font-weight: 500;">No devices registered yet.</p>
                        <small style="display: block; margin-top: 4px; color: #94a3b8;">Devices will appear here once staff logs into the Flutter mobile app.</small>
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach ($devices as $dev): ?>
                            <div style="padding: 10px 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong style="color: #1e293b;"><?php echo htmlspecialchars($dev->device_name ?: 'Mobile Device'); ?></strong>
                                    <span style="color: #64748b; font-size: 0.75rem;"><?php echo strtoupper($dev->platform ?: 'APP'); ?></span>
                                </div>
                                <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    Token: <?php echo substr($dev->token, 0, 24); ?>...
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cloudflare Setup Checklist Card -->
            <div class="card" style="background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <h4 style="margin: 0 0 12px 0; font-size: 1.05rem; font-weight: 600; color: var(--secondary); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-shield-alt" style="color: #f59e0b;"></i>
                    Cloudflare Setup Reminder
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin-bottom: 12px;">
                    To prevent Cloudflare from blocking the mobile app with a JavaScript challenge or 403 error:
                </p>
                <ol style="margin: 0; padding-left: 20px; font-size: 0.85rem; color: #334155; line-height: 1.6;">
                    <li>Open <strong>Cloudflare Dashboard</strong> &rarr; <code>pizzaonerestaurant.com</code></li>
                    <li>Go to <strong>Security &rarr; WAF &rarr; Custom rules</strong></li>
                    <li>Create rule: <strong>URI Path</strong> starts with <code>/api</code></li>
                    <li>Set Action: <strong>Skip</strong> (Skip all security checks)</li>
                    <li>Click <strong>Deploy</strong></li>
                </ol>
            </div>

        </div>

    </div>
</div>
