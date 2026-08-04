<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container mt-4">
    <h2 class="mb-4">Admin Users Management</h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <a href="<?php echo site_url('admin/add_admin'); ?>" class="btn btn-primary mb-3">Add New Admin</a>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?php echo $admin->id; ?></td>
                    <td><?php echo $admin->username; ?></td>
                    <td><?php echo $admin->role; ?></td>
                    <td>
                        <a href="<?php echo site_url('admin/edit_admin/' . $admin->id); ?>" class="btn btn-sm btn-warning mr-2">Edit</a>
                        <a href="<?php echo site_url('admin/delete_admin/' . $admin->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
