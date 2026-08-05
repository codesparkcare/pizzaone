<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Manage Slider Videos</h4>
            <button class="btn btn-primary" onclick="showModal('addSliderVideoModal')">
                <i class="fas fa-plus"></i> Add Video
            </button>
        </div>
        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Video URL</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($videos as $video): ?>
                    <tr>
                        <td><?php echo $video->id; ?></td>
                        <td><?php echo $video->title; ?></td>
                        <td><a href="<?php echo base_url('assets/videos/'.$video->video_url); ?>" target="_blank">View Video</a></td>
                        <td>
                            <?php if($video->status == 1): ?>
                                <span class="badge bg-success" style="padding: 5px 10px; border-radius: 5px; color: #fff; background: var(--success);">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger" style="padding: 5px 10px; border-radius: 5px; color: #fff; background: var(--danger);">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($video->created_at)); ?></td>
                        <td>
                            <a href="<?php echo base_url('admin/edit_slider_video/'.$video->id); ?>" class="btn btn-primary btn-sm" style="background: var(--info);">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="showConfirm('<?php echo base_url('admin/delete_slider_video/'.$video->id); ?>')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add Slider Video -->
<div id="addSliderVideoModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Slider Video</h3>
            <span onclick="closeModal('addSliderVideoModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <?php echo form_open_multipart('admin/add_slider_video'); ?>
                <div class="form-group">
                    <label>Video Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Promo Video" required>
                </div>
                <div class="form-group">
                    <label>Video File (MP4, WebM, etc)</label>
                    <input type="file" name="video_file" class="form-control" accept="video/*" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Add Video</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
