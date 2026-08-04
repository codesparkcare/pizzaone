<div class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin: 0 auto; max-width: 500px;">
            <div class="pizza-card" style="padding: 2rem;">
                <h2 style="text-align: center; color: var(--secondary); margin-bottom: 1.5rem;">User Login</h2>
                
                <?php if(isset($error)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo base_url('user/login'); ?>" method="POST">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
                        <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Password</label>
                        <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    
                    <button type="submit" class="btn-primary" style="width: 100%; border: none; cursor: pointer;">Login</button>
                </form>
                
                <p style="text-align: center; margin-top: 20px;">
                    Don't have an account? <a href="<?php echo base_url('user/register'); ?>" style="color: var(--primary); font-weight: bold;">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>
