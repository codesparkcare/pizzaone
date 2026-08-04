<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Add Shop User</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo base_url('admin/add_shop_user'); ?>" method="POST">
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Shop</label>
                        <select name="shop_id" class="form-control" required>
                            <option value="">-- Select Shop --</option>
                            <?php foreach($shops as $s): ?>
                                <option value="<?php echo $s->id; ?>"><?php echo htmlspecialchars($s->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create User</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Shop Users List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Assigned Shop</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($users)): foreach($users as $u): ?>
                            <tr>
                                <td><?php echo $u->id; ?></td>
                                <td><?php echo htmlspecialchars($u->name); ?></td>
                                <td><?php echo htmlspecialchars($u->username); ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($u->shop_name ?: 'Unknown'); ?></span></td>
                                <td><?php echo $u->created_at ? date('d M Y', strtotime($u->created_at)) : 'N/A'; ?></td>
                                <td>
                                    <button onclick="showConfirm('<?php echo base_url('admin/delete_shop_user/'.$u->id); ?>', 'Are you sure you want to delete this user?')" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
