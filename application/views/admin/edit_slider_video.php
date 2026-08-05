<div class="row" style="display: flex; justify-content: center;">
    <div style="width: 500px;">
        <div class="card">
            <div class="card-header">
                <h4>Edit Slider Video: <?php echo $video->title; ?></h4>
                <a href="<?php echo base_url('admin/slider_videos'); ?>" class="btn btn-sm" style="background: var(--secondary); color: #fff;">Back</a>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('admin/edit_slider_video/'.$video->id); ?>
                    <div class="form-group">
                        <label>Video Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($video->title); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Change Video File (Leave blank to keep current: <a href="<?php echo base_url('assets/videos/'.$video->video_url); ?>" target="_blank">View</a>)</label>
                        <input type="file" name="video_file" class="form-control" accept="video/*">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" <?php echo ($video->status == 1) ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo ($video->status == 0) ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Update Video</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
