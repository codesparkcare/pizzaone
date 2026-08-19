<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Admin Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #e74c3c;
            --secondary: #2c3e50;
            --success: #2ecc71;
            --warning: #f1c40f;
            --danger: #e74c3c;
            --info: #3498db;
            --light: #f8f9fa;
            --dark: #343a40;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--secondary);
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s;
            z-index: 1000;
            color: #fff;
            overflow-y: auto;
        }

        /* Customize Scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.4);
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            background: rgba(0,0,0,0.1);
        }

        .sidebar-header h3 {
            color: var(--primary);
            margin: 0;
            font-weight: 700;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left: 4px solid var(--primary);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 25px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            background: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h4 {
            margin: 0;
            color: var(--secondary);
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: #c0392b; transform: translateY(-2px); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: 0.85rem; }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .table th { color: var(--secondary); font-weight: 600; }

        /* Forms */
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        /* Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            overflow: auto;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            animation: modalSlide 0.3s ease-out;
        }

        @keyframes modalSlide {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 { margin: 0; color: var(--secondary); font-weight: 600; }
        .close-modal { font-size: 1.5rem; cursor: pointer; color: var(--gray); transition: 0.3s; }
        .close-modal:hover { color: var(--primary); }

        .modal-footer {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h3>Pizza One</h3>
    </div>
    <div class="sidebar-menu">
        <a href="<?php echo base_url(); ?>" target="_blank" style="background: rgba(255,255,255,0.05); color: var(--primary); font-weight: 500; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <i class="fas fa-external-link-alt"></i> Visit Site
        </a>
        <a href="<?php echo base_url('admin/dashboard'); ?>" class="<?php echo ($this->uri->segment(2) == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo base_url('admin/categories'); ?>" class="<?php echo ($this->uri->segment(2) == 'categories') ? 'active' : ''; ?>">
            <i class="fas fa-list"></i> Categories
        </a>
<a href="<?php echo base_url('admin/sub_categories'); ?>" class="<?php echo ($this->uri->segment(2) == 'sub_categories') ? 'active' : ''; ?>">
    <i class="fas fa-sitemap"></i> Sub Categories
</a>

        <a href="<?php echo base_url('admin/sizes'); ?>" class="<?php echo ($this->uri->segment(2) == 'sizes') ? 'active' : ''; ?>">
            <i class="fas fa-compress-arrows-alt"></i> Manage Sizes
        </a>
        <a href="<?php echo base_url('admin/addons'); ?>" class="<?php echo ($this->uri->segment(2) == 'addons') ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i> Addons
        </a>
        <a href="<?php echo base_url('admin/addon_groups'); ?>" class="<?php echo ($this->uri->segment(2) == 'addon_groups') ? 'active' : ''; ?>">
            <i class="fas fa-layer-group"></i> Addon Groups
        </a>
        <a href="<?php echo base_url('admin/offers'); ?>" class="<?php echo ($this->uri->segment(2) == 'offers') ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i> Manage Offers
        </a>

        <a href="<?php echo base_url('admin/products'); ?>" class="<?php echo ($this->uri->segment(2) == 'products') ? 'active' : ''; ?>">
            <i class="fas fa-pizza-slice"></i> Manage Products
        </a>
        <?php if ($this->session->userdata('admin_role') !== 'staff'): ?>
        <a href="<?php echo base_url('admin/shop_users'); ?>" class="<?php echo ($this->uri->segment(2) == 'shop_users') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Add User
        </a>
        <?php endif; ?>
        <a href="<?php echo base_url('admin/orders'); ?>" class="<?php echo ($this->uri->segment(2) == 'orders') ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i> Orders
        </a>
        <a href="<?php echo base_url('admin/customers'); ?>" class="<?php echo ($this->uri->segment(2) == 'customers') ? 'active' : ''; ?>">
            <i class="fas fa-user-friends"></i> Manage Customers
        </a>
        <a href="<?php echo base_url('admin/reviews'); ?>" class="<?php echo ($this->uri->segment(2) == 'reviews') ? 'active' : ''; ?>">
            <i class="fas fa-star"></i> Reviews
        </a>
        <a href="<?php echo base_url('admin/slider_videos'); ?>" class="<?php echo ($this->uri->segment(2) == 'slider_videos') ? 'active' : ''; ?>">
            <i class="fas fa-video"></i> Slider Videos
        </a>
        <a href="<?php echo base_url('admin/logout'); ?>" style="margin-top: 50px; color: #e74c3c;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="page-title">
            <h2 style="margin:0; font-weight: 600; color: var(--secondary);"><?php echo $title; ?></h2>
        </div>
        <div class="user-info">
            <span style="font-weight: 500; color: var(--secondary);">Hello, <?php echo $this->session->userdata('admin_username'); ?></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=e74c3c&color=fff" alt="User">
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
