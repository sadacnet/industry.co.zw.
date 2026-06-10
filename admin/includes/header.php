<?php
require_once __DIR__ . '/auth-check.php';
requireAdminLogin();
$currentAdmin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - industry.co.zw</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #1b5e20;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
            font-size: 14px;
        }
        
        .sidebar-menu li a:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.2);
            border-left: 3px solid #fff;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }
        
        .top-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #2e7d32;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1b5e20;
        }
        
        .btn-danger {
            background: #c62828;
            color: white;
        }
        
        .btn-danger:hover {
            background: #b71c1c;
        }
        
        .btn-warning {
            background: #f57c00;
            color: white;
        }
        
        .btn-info {
            background: #1565c0;
            color: white;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        
        table th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }
        
        table tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-active {
            background: #c8e6c9;
            color: #2e7d32;
        }
        
        .badge-inactive {
            background: #ffcdd2;
            color: #c62828;
        }
        
        .badge-czi {
            background: #bbdefb;
            color: #1565c0;
        }
        
        .badge-cifoz {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .alert-success {
            background: #c8e6c9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
        
        .alert-error {
            background: #ffcdd2;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            🏭 industry.co.zw
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">📊 Dashboard</a></li>
            <li><a href="members.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : ''; ?>">🏢 Members</a></li>
            <li><a href="events.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>">📅 Events</a></li>
            <li><a href="tenders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tenders.php' ? 'active' : ''; ?>">📄 Tenders</a></li>
            <li><a href="advertisements.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'advertisements.php' ? 'active' : ''; ?>">📢 Advertisements</a></li>
            <li><a href="exports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'exports.php' ? 'active' : ''; ?>">📦 Exports</a></li>
            <li><a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">🖼️ Gallery</a></li>
            <li><a href="videos.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'videos.php' ? 'active' : ''; ?>">🎥 Videos</a></li>
            <li><a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">📧 Messages</a></li>
            <li><a href="upload-images.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'upload-test.php' ? 'active' : ''; ?>">📤 Upload Images</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <h2>Admin Panel</h2>
            </div>
            <div>
                Welcome, <strong><?php echo htmlspecialchars($currentAdmin['username']); ?></strong>
                <a href="logout.php" class="btn btn-danger" style="margin-left: 10px;">Logout</a>
            </div>
        </div>