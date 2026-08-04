<div class="container section-padding">
    <div class="row">
        <div class="col-md-4">
            <div class="pizza-card" style="padding: 1.5rem; margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px; color: var(--secondary);">My Account</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;">
                        <a href="<?php echo base_url('user/account'); ?>" style="color: var(--primary); font-weight: bold; text-decoration: none;">Profile Details</a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="<?php echo base_url('user/logout'); ?>" style="color: #666; text-decoration: none;">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="pizza-card" style="padding: 2rem;">
                <h2 style="color: var(--secondary); margin-bottom: 1.5rem;">Welcome, <?php echo htmlspecialchars($user->first_name); ?>!</h2>
                
                <div style="background: #f8f8f8; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Personal Information</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user->phone ? $user->phone : 'Not provided'); ?></p>
                </div>
                
                <div style="background: #f8f8f8; padding: 20px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Delivery Address</h4>
                    <p><?php echo $user->address ? nl2br(htmlspecialchars($user->address)) : 'No delivery address saved.'; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
