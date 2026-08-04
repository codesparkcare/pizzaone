<div class="row">
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <div class="card-header">
            <h4>Edit Offer</h4>
        </div>
        <div class="card-body">
            <?php echo form_open('admin/update_offer/'.$offer->id); ?>
                <div class="form-group">
                    <label>Offer Name</label>
                    <input type="text" name="offer_name" class="form-control" value="<?php echo htmlspecialchars($offer->offer_name); ?>" required>
                </div>
                
                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Update Offer</button>
                    <a href="<?php echo base_url('admin/offers'); ?>" class="btn btn-danger" style="flex: 1; text-align: center;">Cancel</a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
