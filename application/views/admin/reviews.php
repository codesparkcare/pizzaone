<div class="row">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Manage Reviews</h4>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('addReviewModal').style.display='block'">
                <i class="fas fa-plus"></i> Add Review
            </button>
        </div>
        <div class="card-body">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reviews as $r): ?>
                    <tr>
                        <td>#<?php echo $r->id; ?></td>
                        <td><?php echo $r->customer_name; ?></td>
                        <td>
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="fas fa-star" style="color: <?php echo ($i <= $r->rating) ? '#f1c40f' : '#ccc'; ?>;"></i>
                            <?php endfor; ?>
                        </td>
                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo $r->comment; ?>
                        </td>
                        <td>
                            <form action="<?php echo base_url('admin/update_review_status/'.$r->id); ?>" method="POST" style="display:inline;">
                                <select name="status" class="form-control" style="width: 100px; padding: 4px; display: inline-block; font-size: 0.8rem;" onchange="this.form.submit()">
                                    <option value="1" <?php echo ($r->status == 1) ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo ($r->status == 0) ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </form>
                        </td>
                        <td><?php echo date('d M Y', strtotime($r->created_at)); ?></td>
                        <td>
                            <a href="<?php echo base_url('admin/delete_review/'.$r->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?');" style="padding: 4px 10px; font-size: 0.8rem;">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Review Modal -->
<div id="addReviewModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Review</h3>
            <span class="close-modal" onclick="document.getElementById('addReviewModal').style.display='none'">&times;</span>
        </div>
        <form action="<?php echo base_url('admin/add_review'); ?>" method="POST">
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required placeholder="Enter customer name">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Rating (1-5)</label>
                    <select name="rating" class="form-control" required>
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Very Good</option>
                        <option value="3">3 - Good</option>
                        <option value="2">2 - Fair</option>
                        <option value="1">1 - Poor</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Comment</label>
                    <textarea name="comment" class="form-control" required placeholder="Enter review comment" rows="4"></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>
                        <input type="checkbox" name="status" value="1" checked> Active (Visible)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addReviewModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Review</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.onclick = function(event) {
        var modal = document.getElementById('addReviewModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
